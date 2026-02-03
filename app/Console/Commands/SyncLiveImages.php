<?php

namespace App\Console\Commands;

use App\Models\Page;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class SyncLiveImages extends Command
{
    protected $signature = 'images:sync-live
        {--apply : Write changes to disk}
        {--threshold=10 : Hamming distance threshold for similarity}
        {--memory-limit=512M : PHP memory limit for this command}
        {--max-image-bytes=8388608 : Skip images larger than this many bytes}
        {--download-missing : Download live images when no local match is found}
        {--update-page-content : Update stored page image URLs to match live paths}
        {--update-site-logo : Update SITE_LOGO to match the live logo path when possible}
        {--local-base-url= : Override local base URL for link discovery}
        {--live-base-url= : Override live base URL (defaults to LIVE_COMPARE_BASE_URL)}';

    protected $description = 'Match live images to local files and copy/download them into local storage.';

    private Client $client;
    private int $maxImageBytes;

    public function handle(): int
    {
        $liveBaseUrl = rtrim((string) ($this->option('live-base-url') ?: env('LIVE_COMPARE_BASE_URL', '')), '/');
        $localBaseUrl = rtrim((string) ($this->option('local-base-url') ?: env('LOCAL_COMPARE_BASE_URL', config('app.url', 'http://localhost'))), '/');

        if ($liveBaseUrl === '') {
            $this->error('LIVE_COMPARE_BASE_URL is not set.');
            return self::FAILURE;
        }

        if (! function_exists('imagecreatefromstring')) {
            $this->error('GD extension is required for image comparison.');
            return self::FAILURE;
        }

        $this->client = new Client([
            'timeout' => 15,
            'connect_timeout' => 5,
            'http_errors' => false,
            'allow_redirects' => true,
        ]);

        $memoryLimit = (string) $this->option('memory-limit');
        if ($memoryLimit !== '') {
            ini_set('memory_limit', $memoryLimit);
        }

        $threshold = (int) $this->option('threshold');
        $apply = (bool) $this->option('apply');
        $downloadMissing = (bool) $this->option('download-missing');
        $updatePageContent = (bool) $this->option('update-page-content');
        $updateSiteLogo = (bool) $this->option('update-site-logo');
        $this->maxImageBytes = (int) $this->option('max-image-bytes');

        $this->info('Discovering live pages...');
        $pagePaths = $this->discoverLivePages($liveBaseUrl, $localBaseUrl);
        $this->line('Pages: ' . implode(', ', $pagePaths));

        $this->info('Collecting live images...');
        $liveImages = $this->collectLiveImages($liveBaseUrl, $pagePaths);
        $this->line('Live images: ' . count($liveImages));

        $this->info('Indexing local images...');
        $localImages = $this->indexLocalImages();
        $this->line('Local images indexed: ' . count($localImages));

        $localToLive = $this->buildLocalToLiveMap($localImages, $liveImages, $threshold);

        $copied = 0;
        $downloaded = 0;
        $matched = 0;
        $missing = 0;

        foreach ($liveImages as $livePath => $liveHash) {
            $target = $this->mapLivePathToLocalTarget($livePath);
            if ($target === null) {
                continue;
            }

            if (is_file($target['path'])) {
                $matched++;
                continue;
            }

            $best = $this->findBestMatch($liveHash, $localImages);
            if ($best && $best['distance'] <= $threshold) {
                $matched++;
                if ($apply) {
                    $this->ensureDir($target['path']);
                    copy($best['file'], $target['path']);
                    $copied++;
                    $this->line("Copied {$best['file']} -> {$target['path']}");
                } else {
                    $this->line("Would copy {$best['file']} -> {$target['path']}");
                }
                continue;
            }

            $missing++;
            if ($downloadMissing) {
                $bytes = $this->fetchImageBytes($liveBaseUrl . $livePath);
                if ($bytes !== null) {
                    if ($apply) {
                        $this->ensureDir($target['path']);
                        file_put_contents($target['path'], $bytes);
                        $downloaded++;
                        $this->line("Downloaded {$livePath} -> {$target['path']}");
                    } else {
                        $this->line("Would download {$livePath} -> {$target['path']}");
                    }
                } else {
                    $this->warn("Could not download {$livePath}");
                }
            } else {
                $this->warn("No local match for {$livePath}");
            }
        }

        $this->info('Summary:');
        $this->line("Matched: {$matched}");
        $this->line("Copied: {$copied}");
        $this->line("Downloaded: {$downloaded}");
        $this->line("Missing: {$missing}");

        if ($updatePageContent) {
            $this->info('Updating page content image URLs...');
            $updatedPages = $this->updatePages($localToLive, $apply, $localBaseUrl);
            $this->line("Pages updated: {$updatedPages}");
        }

        if ($updateSiteLogo) {
            $this->info('Updating SITE_LOGO...');
            if (! $this->updateSiteLogo($localToLive, $liveImages, $apply)) {
                $this->warn('SITE_LOGO was not updated.');
            }
        }

        if (! $apply) {
            $this->warn('Dry run: no files were written. Re-run with --apply to write changes.');
        }

        return self::SUCCESS;
    }

    /**
        * @return string[]
        */
    private function discoverLivePages(string $liveBaseUrl, string $localBaseUrl): array
    {
        $liveHome = $this->fetchHtml($liveBaseUrl . '/');
        if ($liveHome === null) {
            $this->error('Unable to fetch live home page.');
            return ['/'];
        }

        $localHome = $this->fetchHtml($localBaseUrl . '/');
        $localBaseForLinks = $localBaseUrl;

        if ($localHome === null && $this->isLoopbackHost($localBaseUrl)) {
            $localBaseForLinks = 'http://nginx';
            $localHome = $this->fetchHtml($localBaseForLinks . '/');
        }

        $liveLinks = $this->extractLinksWithText($liveHome, $liveBaseUrl . '/');
        $liveMap = $this->buildTextToPathMap($liveLinks);

        $paths = ['/'];

        if ($localHome !== null) {
            $localLinks = $this->extractLinksWithText($localHome, $localBaseForLinks . '/');
            $localMap = $this->buildTextToPathMap($localLinks);

            foreach (array_intersect(array_keys($localMap), array_keys($liveMap)) as $text) {
                if (count($localMap[$text]) !== 1 || count($liveMap[$text]) !== 1) {
                    continue;
                }
                $paths[] = $liveMap[$text][0];
            }
        } else {
            foreach ($liveMap as $pathsForText) {
                if (count($pathsForText) === 1) {
                    $paths[] = $pathsForText[0];
                }
            }
        }

        $paths = array_values(array_unique($paths));
        sort($paths);

        return $paths;
    }

    private function isLoopbackHost(string $baseUrl): bool
    {
        $host = (string) parse_url($baseUrl, PHP_URL_HOST);
        return in_array($host, ['localhost', '127.0.0.1'], true);
    }

    /**
        * @param  string[]  $pagePaths
        * @return array<string, string>
        */
    private function collectLiveImages(string $liveBaseUrl, array $pagePaths): array
    {
        $images = [];

        foreach ($pagePaths as $path) {
            $html = $this->fetchHtml($liveBaseUrl . $path);
            if ($html === null) {
                $this->warn("Skipping {$path}; failed to fetch HTML.");
                continue;
            }

            $urls = $this->extractUrls($html, 'img', 'src');
            foreach ($urls as $url) {
                $pathOnly = $this->resolveInternalPath($url, $liveBaseUrl . $path);
                if ($pathOnly === null) {
                    continue;
                }

                if (! str_starts_with($pathOnly, '/storage/') && ! str_starts_with($pathOnly, '/img/')) {
                    continue;
                }

                if (isset($images[$pathOnly])) {
                    continue;
                }

                $bytes = $this->fetchImageBytes($liveBaseUrl . $pathOnly);
                if ($bytes === null) {
                    $this->warn("Skipping {$pathOnly}; download failed.");
                    continue;
                }

                if ($this->maxImageBytes > 0 && strlen($bytes) > $this->maxImageBytes) {
                    $this->warn("Skipping {$pathOnly}; exceeds max-image-bytes.");
                    continue;
                }

                $hash = $this->averageHash($bytes);
                if ($hash === null) {
                    $this->warn("Skipping {$pathOnly}; hash failed.");
                    continue;
                }

                $images[$pathOnly] = $hash;
            }
        }

        return $images;
    }

    /**
     * @return array<int, array{hash:string,file:string,path:string}>
     */
    private function indexLocalImages(): array
    {
        $roots = [
            storage_path('app/public') => '/storage',
            public_path('img') => '/img',
        ];

        $images = [];

        foreach ($roots as $root => $prefix) {
            if (! is_dir($root)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
            /** @var SplFileInfo $file */
            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }

                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                    continue;
                }

                $bytes = file_get_contents($file->getPathname());
                if ($this->maxImageBytes > 0 && strlen($bytes) > $this->maxImageBytes) {
                    continue;
                }

                $hash = $this->averageHash($bytes);
                if ($hash === null) {
                    continue;
                }

                $relative = str_replace($root, '', $file->getPathname());
                $relative = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
                $path = rtrim($prefix, '/') . '/' . ltrim($relative, '/');

                $images[] = [
                    'hash' => $hash,
                    'file' => $file->getPathname(),
                    'path' => $path,
                ];
            }
        }

        return $images;
    }

    private function findBestMatch(string $hash, array $localImages): ?array
    {
        $best = null;
        $bestDistance = PHP_INT_MAX;

        foreach ($localImages as $local) {
            $distance = $this->hammingDistance($hash, $local['hash']);
            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $best = [
                    'hash' => $local['hash'],
                    'file' => $local['file'],
                    'distance' => $distance,
                ];
            }
        }

        return $best;
    }

    /**
     * @param  array<int, array{hash:string,file:string,path:string}>  $localImages
     * @param  array<string, string>  $liveImages
     * @return array<string, string>
     */
    private function buildLocalToLiveMap(array $localImages, array $liveImages, int $threshold): array
    {
        $liveList = [];
        foreach ($liveImages as $path => $hash) {
            $liveList[] = ['path' => $path, 'hash' => $hash];
        }

        $map = [];
        foreach ($localImages as $local) {
            $bestDistance = PHP_INT_MAX;
            $bestPath = null;
            foreach ($liveList as $live) {
                $distance = $this->hammingDistance($local['hash'], $live['hash']);
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestPath = $live['path'];
                }
            }

            if ($bestPath !== null && $bestDistance <= $threshold) {
                $map[$local['path']] = $bestPath;
            }
        }

        return $map;
    }

    private function mapLivePathToLocalTarget(string $livePath): ?array
    {
        if (str_starts_with($livePath, '/storage/')) {
            $relative = ltrim(substr($livePath, strlen('/storage/')), '/');
            return [
                'path' => storage_path('app/public/' . $relative),
            ];
        }

        if (str_starts_with($livePath, '/img/')) {
            $relative = ltrim(substr($livePath, strlen('/img/')), '/');
            return [
                'path' => public_path('img/' . $relative),
            ];
        }

        return null;
    }

    private function ensureDir(string $path): void
    {
        $dir = dirname($path);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function fetchHtml(string $url): ?string
    {
        try {
            $response = $this->client->get($url);
        } catch (\Throwable $e) {
            $this->warn("Failed to fetch HTML: {$url}");
            return null;
        }

        if ($response->getStatusCode() >= 400) {
            return null;
        }

        return (string) $response->getBody();
    }

    /**
     * @param  array<string, string>  $localToLive
     */
    private function updatePages(array $localToLive, bool $apply, string $pageBaseUrl): int
    {
        if ($localToLive === []) {
            $this->warn('No local-to-live image matches found; skipping page updates.');
            return 0;
        }

        $updated = 0;

        foreach (Page::all() as $page) {
            $changed = false;

            if ($page->body !== null && $page->body !== '') {
                [$newBody, $bodyChanged] = $this->rewriteImageUrls($page->body, $localToLive, $pageBaseUrl);
                if ($bodyChanged) {
                    $page->body = $newBody;
                    $changed = true;
                }
            }

            if (! empty($page->image)) {
                $imagePath = str_starts_with($page->image, '/')
                    ? $page->image
                    : '/storage/' . ltrim($page->image, '/');

                if (isset($localToLive[$imagePath])) {
                    $mapped = $localToLive[$imagePath];
                    if (str_starts_with($mapped, '/storage/')) {
                        $page->image = ltrim(substr($mapped, strlen('/storage/')), '/');
                        $changed = true;
                    }
                }
            }

            if ($changed) {
                $updated++;
                if ($apply) {
                    $page->save();
                }
            }
        }

        if (! $apply && $updated > 0) {
            $this->warn('Dry run: page updates were not written. Re-run with --apply.');
        }

        return $updated;
    }

    /**
     * @param  array<string, string>  $localToLive
     * @param  array<string, string>  $liveImages
     */
    private function updateSiteLogo(array $localToLive, array $liveImages, bool $apply): bool
    {
        $current = (string) env('SITE_LOGO', '');
        if ($current === '') {
            $this->warn('SITE_LOGO is not set.');
            return false;
        }

        if (preg_match('#^https?://#i', $current)) {
            $currentPath = parse_url($current, PHP_URL_PATH) ?: '';
        } else {
            $currentPath = '/' . ltrim($current, '/');
        }

        if ($currentPath === '') {
            $this->warn('SITE_LOGO path could not be resolved.');
            return false;
        }

        $target = $localToLive[$currentPath] ?? null;
        if ($target === null) {
            $settings = array_values(array_filter(
                array_keys($liveImages),
                fn (string $path): bool => str_starts_with($path, '/storage/settings/')
            ));
            if (count($settings) === 1) {
                $target = $settings[0];
            }
        }

        if ($target === null) {
            $this->warn('No matching live logo path found.');
            return false;
        }

        $newValue = ltrim($target, '/');
        if ($newValue === ltrim($currentPath, '/')) {
            $this->line('SITE_LOGO is already set to the matching path.');
            return true;
        }

        $envPath = base_path('.env');
        if (! is_file($envPath)) {
            $this->warn('Unable to locate .env file.');
            return false;
        }

        if (! $apply) {
            $this->line("Would update SITE_LOGO={$newValue}");
            return true;
        }

        $contents = file_get_contents($envPath);
        if ($contents === false) {
            $this->warn('Unable to read .env file.');
            return false;
        }

        if (preg_match('/^SITE_LOGO=.*/m', $contents) === 1) {
            $contents = preg_replace('/^SITE_LOGO=.*/m', "SITE_LOGO={$newValue}", $contents);
        } else {
            $contents .= "\nSITE_LOGO={$newValue}\n";
        }

        file_put_contents($envPath, $contents);
        $this->line("Updated SITE_LOGO={$newValue}");

        return true;
    }

    /**
     * @param  array<string, string>  $localToLive
     * @return array{0:string,1:bool}
     */
    private function rewriteImageUrls(string $html, array $localToLive, string $pageBaseUrl): array
    {
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $container = $dom->getElementsByTagName('div')->item(0);
        if (! $container) {
            return [$html, false];
        }

        $changed = false;
        foreach ($container->getElementsByTagName('img') as $node) {
            $src = trim((string) $node->getAttribute('src'));
            if ($src === '') {
                continue;
            }

            $resolved = $this->resolveInternalPath($src, $pageBaseUrl);
            if ($resolved === null) {
                continue;
            }

            $path = strtok($resolved, '?') ?: $resolved;
            if (! str_starts_with($path, '/storage/') && ! str_starts_with($path, '/img/')) {
                continue;
            }

            $replacement = $localToLive[$path] ?? $path;
            if ($replacement !== $src) {
                $node->setAttribute('src', $replacement);
                $changed = true;
            }
        }

        if (! $changed) {
            return [$html, false];
        }

        $newHtml = '';
        foreach ($container->childNodes as $child) {
            $newHtml .= $dom->saveHTML($child);
        }

        return [$newHtml, true];
    }

    private function fetchImageBytes(string $url): ?string
    {
        try {
            $response = $this->client->get($url);
        } catch (\Throwable $e) {
            return null;
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 400) {
            return null;
        }

        $contentType = $response->getHeaderLine('Content-Type');
        if ($contentType !== '' && ! str_starts_with(strtolower($contentType), 'image/')) {
            return null;
        }

        $contentLength = (int) $response->getHeaderLine('Content-Length');
        if ($this->maxImageBytes > 0 && $contentLength > $this->maxImageBytes) {
            return null;
        }

        return (string) $response->getBody();
    }

    private function averageHash(string $bytes): ?string
    {
        $image = @imagecreatefromstring($bytes);
        if ($image === false) {
            return null;
        }

        $size = 8;
        $scaled = imagescale($image, $size, $size, IMG_BILINEAR_FIXED);
        imagedestroy($image);
        if ($scaled === false) {
            return null;
        }

        $pixels = [];
        $sum = 0;
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $rgb = imagecolorat($scaled, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $gray = (int) round(($r + $g + $b) / 3);
                $pixels[] = $gray;
                $sum += $gray;
            }
        }
        imagedestroy($scaled);

        $avg = (int) floor($sum / count($pixels));

        $hash = '';
        foreach ($pixels as $gray) {
            $hash .= $gray >= $avg ? '1' : '0';
        }

        return $hash;
    }

    private function hammingDistance(string $a, string $b): int
    {
        $length = min(strlen($a), strlen($b));
        $distance = abs(strlen($a) - strlen($b));

        for ($i = 0; $i < $length; $i++) {
            if ($a[$i] !== $b[$i]) {
                $distance++;
            }
        }

        return $distance;
    }

    /**
        * @return array<int, array{text:string,path:string}>
        */
    private function extractLinksWithText(string $html, string $pageUrl): array
    {
        $links = [];

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName('a') as $node) {
            $href = trim((string) $node->getAttribute('href'));
            $text = trim((string) $node->textContent);
            $text = preg_replace('/\s+/', ' ', $text ?? '');

            if ($text === '' || $this->isSkippableUrl($href)) {
                continue;
            }

            $path = $this->resolveInternalPath($href, $pageUrl);
            if ($path === null) {
                continue;
            }

            $links[] = [
                'text' => mb_strtolower($text),
                'path' => $path,
            ];
        }

        return $links;
    }

    /**
        * @param  array<int, array{text:string,path:string}>  $links
        * @return array<string, string[]>
        */
    private function buildTextToPathMap(array $links): array
    {
        $map = [];

        foreach ($links as $link) {
            $map[$link['text']] ??= [];
            $map[$link['text']][] = $link['path'];
        }

        foreach ($map as $text => $paths) {
            $paths = array_values(array_unique($paths));
            sort($paths);
            $map[$text] = $paths;
        }

        return $map;
    }

    /**
        * @return string[]
        */
    private function extractUrls(string $html, string $tag, string $attribute): array
    {
        $urls = [];

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML($html);
        libxml_clear_errors();

        foreach ($dom->getElementsByTagName($tag) as $node) {
            $value = trim((string) $node->getAttribute($attribute));
            if ($value !== '') {
                $urls[] = $value;
            }
        }

        return array_values(array_unique($urls));
    }

    private function resolveInternalPath(string $url, string $pageUrl): ?string
    {
        $pageUri = new Uri($pageUrl);
        $allowedHosts = array_filter(array_unique([
            $pageUri->getHost(),
            parse_url($pageUrl, PHP_URL_HOST),
            parse_url((string) env('LOCAL_COMPARE_BASE_URL', ''), PHP_URL_HOST),
            parse_url((string) env('LIVE_COMPARE_BASE_URL', ''), PHP_URL_HOST),
            'localhost',
            '127.0.0.1',
        ]));

        $abs = UriResolver::resolve($pageUri, new Uri($url));
        $host = $abs->getHost();

        if ($host !== '' && ! in_array($host, $allowedHosts, true)) {
            return null;
        }

        $path = $abs->getPath() ?: '/';
        $query = $abs->getQuery();

        return $query ? "{$path}?{$query}" : $path;
    }

    private function isSkippableUrl(string $url): bool
    {
        $lower = strtolower(trim($url));
        return $lower === '#' ||
            str_starts_with($lower, '#') ||
            str_starts_with($lower, 'mailto:') ||
            str_starts_with($lower, 'tel:') ||
            str_starts_with($lower, 'javascript:') ||
            str_starts_with($lower, 'data:');
    }
}
