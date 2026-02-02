<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        if (!app()->environment('testing')) {
            return;
        }

        $hotFile = storage_path('framework/testing/vite.hot');
        File::ensureDirectoryExists(dirname($hotFile));

        if (!File::exists($hotFile)) {
            File::put($hotFile, 'http://localhost:5173');
        }

        Vite::useHotFile($hotFile);
    }
}
