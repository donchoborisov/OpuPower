<?php

namespace Tests\Feature;

use App\Models\Page;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\PagesTableSeeder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\Psr7\UriResolver;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('live')]
class LiveComparisonTest extends TestCase
{
    private Client $client;
    private string $liveBaseUrl;
    private string $localBaseUrl;

    protected function setUp(): void
    {
        parent::setUp();

        ini_set('memory_limit', '512M');

        $this->client = new Client([
            'timeout' => 10,
            'connect_timeout' => 5,
            'http_errors' => false,
            'allow_redirects' => true,
        ]);

        $this->liveBaseUrl = rtrim((string) env('LIVE_COMPARE_BASE_URL', ''), '/');
        $this->localBaseUrl = rtrim((string) env('LOCAL_COMPARE_BASE_URL', config('app.url', 'http://localhost')), '/');

        $this->ensurePagesSeeded();
    }

    public function test_live_pages_match_local_pages()
    {
        if (! $this->isLiveCompareEnabled()) {
            $this->markTestSkipped('LIVE_COMPARE_ENABLED is not true.');
        }

        if ($this->liveBaseUrl === '') {
            $this->markTestSkipped('LIVE_COMPARE_BASE_URL is not set.');
        }

        if (! function_exists('imagecreatefromstring')) {
            $this->markTestSkipped('GD extension is required for image comparison.');
        }

        $comparisons = $this->resolveComparisons();

        foreach ($comparisons as [$localPath, $livePath]) {
            $label = "{$localPath} (local) vs {$livePath} (live)";

            $localResponse = $this->get($localPath);
            $this->assertTrue(
                $localResponse->getStatusCode() >= 200 && $localResponse->getStatusCode() < 400,
                "Local path {$localPath} returned status {$localResponse->getStatusCode()}."
            );

            $liveResponse = $this->client->get($this->liveBaseUrl . $livePath);
            $this->assertTrue(
                $liveResponse->getStatusCode() >= 200 && $liveResponse->getStatusCode() < 400,
                "Live path {$livePath} returned status {$liveResponse->getStatusCode()}."
            );

            $localHtml = (string) $localResponse->getContent();
            $liveHtml = (string) $liveResponse->getBody();

            $localImages = $this->normalizeUrls(
                $this->extractUrls($localHtml, 'img', 'src'),
                $this->localBaseUrl . $localPath
            );
            $liveImages = $this->normalizeUrls(
                $this->extractUrls($liveHtml, 'img', 'src'),
                $this->liveBaseUrl . $livePath
            );

            $this->assertImagesSimilar($localImages, $liveImages, $label);
        }
    }

    private function isLiveCompareEnabled(): bool
    {
        return filter_var(env('LIVE_COMPARE_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
    }

    private function ensurePagesSeeded(): void
    {
        $requiredSlugs = [
            'network-maintenance',
            'it-support-services',
            'network-installation',
            'telephone-systems',
            'cloud-solutions',
            'cctv',
        ];

        if (Page::whereIn('slug', $requiredSlugs)->count() === count($requiredSlugs)) {
            return;
        }

        $this->seed(AdminUserSeeder::class);
        $this->seed(PagesTableSeeder::class);
    }

    /**
        * @return string[]
        */
    private function resolveComparisons(): array
    {
        return $this->buildAutoComparisons();
    }

    /**
        * @return array<int, array{0:string,1:string}>
        */
    private function buildAutoComparisons(): array
    {
        $localResponse = $this->get('/');
        $this->assertTrue(
            $localResponse->getStatusCode() >= 200 && $localResponse->getStatusCode() < 400,
            "Local home returned status {$localResponse->getStatusCode()}."
        );

        $liveResponse = $this->client->get($this->liveBaseUrl . '/');
        $this->assertTrue(
            $liveResponse->getStatusCode() >= 200 && $liveResponse->getStatusCode() < 400,
            "Live home returned status {$liveResponse->getStatusCode()}."
        );

        $localLinks = $this->extractLinksWithText((string) $localResponse->getContent(), $this->localBaseUrl . '/');
        $liveLinks = $this->extractLinksWithText((string) $liveResponse->getBody(), $this->liveBaseUrl . '/');

        $localMap = $this->buildTextToPathMap($localLinks);
        $liveMap = $this->buildTextToPathMap($liveLinks);

        $comparisons = [
            ['/', '/'],
        ];

        foreach (array_intersect(array_keys($localMap), array_keys($liveMap)) as $text) {
            if (count($localMap[$text]) !== 1 || count($liveMap[$text]) !== 1) {
                continue;
            }

            $comparisons[] = [$localMap[$text][0], $liveMap[$text][0]];
        }

        $comparisons = array_values(array_unique($comparisons, SORT_REGULAR));
        sort($comparisons);

        if (count($comparisons) === 1) {
            $this->markTestSkipped('AUTO_MAP found no matching link texts to compare.');
        }

        return $comparisons;
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

            if ($text === '' || $this->isSkippableUrl($href, allowEmpty: false)) {
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

    /**
        * @param  string[]  $urls
        * @return string[]
        */
    private function normalizeUrls(array $urls, string $pageUrl, bool $allowEmpty = true): array
    {
        $normalized = [];

        foreach ($urls as $url) {
            if ($this->isSkippableUrl($url, $allowEmpty)) {
                continue;
            }

            $path = $this->resolveInternalPath($url, $pageUrl);
            if ($path !== null) {
                $normalized[] = $path;
            }
        }

        $normalized = array_values(array_unique($normalized));
        sort($normalized);

        return $normalized;
    }

    private function resolveInternalPath(string $url, string $pageUrl): ?string
    {
        $pageUri = new Uri($pageUrl);
        $allowedHosts = array_filter(array_unique([
            $pageUri->getHost(),
            parse_url($this->localBaseUrl, PHP_URL_HOST),
            parse_url($this->liveBaseUrl, PHP_URL_HOST),
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

    /**
        * @param  string[]  $localPaths
        * @param  string[]  $livePaths
        */
    private function assertImagesSimilar(array $localPaths, array $livePaths, string $label): void
    {
        $threshold = (int) (env('LIVE_COMPARE_IMAGE_HASH_THRESHOLD', '10'));

        [$localHashes, $localErrors] = $this->buildImageHashes($this->localBaseUrl, $localPaths);
        [$liveHashes, $liveErrors] = $this->buildImageHashes($this->liveBaseUrl, $livePaths);

        $message = [];
        if ($localErrors) {
            $message[] = $label . ' - local image fetch errors: ' . implode(', ', array_slice($localErrors, 0, 10));
        }
        if ($liveErrors) {
            $message[] = $label . ' - live image fetch errors: ' . implode(', ', array_slice($liveErrors, 0, 10));
        }

        if (! $localHashes || ! $liveHashes) {
            $message[] = $label . ' - image comparison skipped due to missing hashes.';
            $this->assertTrue(false, implode("\n", $message));
        }

        $remainingLive = $liveHashes;
        $missingLocally = [];

        foreach ($localHashes as $local) {
            $bestIndex = null;
            $bestDistance = PHP_INT_MAX;

            foreach ($remainingLive as $index => $live) {
                $distance = $this->hammingDistance($local['hash'], $live['hash']);
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestIndex = $index;
                }
            }

            if ($bestIndex !== null && $bestDistance <= $threshold) {
                unset($remainingLive[$bestIndex]);
            } else {
                $missingLocally[] = $local['path'];
            }
        }

        $missingOnLive = array_values(array_map(
            fn (array $item): string => $item['path'],
            $remainingLive
        ));

        if ($missingLocally) {
            $message[] = $label . ' - no similar image on live for local paths: ' . implode(', ', array_slice($missingLocally, 0, 10));
        }
        if ($missingOnLive) {
            $message[] = $label . ' - no similar image on local for live paths: ' . implode(', ', array_slice($missingOnLive, 0, 10));
        }

        $this->assertTrue($missingLocally === [] && $missingOnLive === [] && $message === [], implode("\n", $message));
    }

    /**
        * @param  string[]  $paths
        * @return array{0:array<int, array{path:string,hash:string}>,1:string[]}
        */
    private function buildImageHashes(string $baseUrl, array $paths): array
    {
        $hashes = [];
        $errors = [];

        foreach ($paths as $path) {
            $bytes = $this->fetchImage($baseUrl . $path);
            if ($bytes === null) {
                $errors[] = $path;
                continue;
            }

            $hash = $this->averageHash($bytes);
            if ($hash === null) {
                $errors[] = $path;
                continue;
            }

            $hashes[] = [
                'path' => $path,
                'hash' => $hash,
            ];
        }

        return [$hashes, $errors];
    }

    private function fetchImage(string $url): ?string
    {
        $response = $this->client->get($url);
        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 400) {
            return null;
        }

        $contentType = $response->getHeaderLine('Content-Type');
        if ($contentType !== '' && ! str_starts_with(strtolower($contentType), 'image/')) {
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

    private function isSkippableUrl(string $url, bool $allowEmpty): bool
    {
        if ($allowEmpty && trim($url) === '') {
            return true;
        }

        $lower = strtolower(trim($url));
        return $lower === '#' ||
            str_starts_with($lower, '#') ||
            str_starts_with($lower, 'mailto:') ||
            str_starts_with($lower, 'tel:') ||
            str_starts_with($lower, 'javascript:') ||
            str_starts_with($lower, 'data:');
    }

    // Image-only comparisons use perceptual hashing; link comparisons removed by request.
}
