<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
            if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
            $this->app->useStoragePath('/tmp/storage');
        }
    }

    public function boot(): void
    {
        if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL'])) {
            $directories = [
                '/tmp/storage/framework/views',
                '/tmp/storage/framework/cache/data',
                '/tmp/storage/framework/sessions',
                '/tmp/storage/logs',
                '/tmp/storage/app/public',
                '/tmp/storage/app/private',
            ];

            foreach ($directories as $dir) {
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
            }

            if (!is_dir(public_path('storage'))) {
                symlink('/tmp/storage/app/public', public_path('storage'));
            }
        }
    }
}
