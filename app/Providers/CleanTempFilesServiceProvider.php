<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class CleanTempFilesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $tempDirectory = storage_path('app/livewire-tmp');

        if (File::exists($tempDirectory)) {
            foreach (File::allFiles($tempDirectory) as $file) {
                if ($file->getMTime() < time() - 600) { // 600 segundos = 10 minutos
                    File::delete($file);
                }
            }
        }
    }
}
