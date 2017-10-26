<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use File;

class FileFinderServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    protected $cache = [];

    public function getRandom($fileKey)
    {
        $ret = '';
        // try plural first
        $path = base_path('storage/files/' . $fileKey . 's.txt');
        if (!File::exists($path)) {
            // fallback - try raw file name
            $path = base_path('storage/files/' . $fileKey . '.txt');
            if (!File::exists($path)) {
                throw new \Exception('No file found for key: [' . $fileKey . '] at location: [' . $path . ']');
            }
        }

        if (!array_key_exists($fileKey, $this->cache)) {
            // load in the cache file
            $this->cache[$fileKey] = collect(explode("\n", File::get($path)))->filter(function ($item) {
                // trim any that are not at-least 1 char
                return Str::length(trim($item)) >= 1;
            })->map(function ($item) {
                // convert to lowercase
                $item = Str::lower($item);
                return $item;
            })->toArray();

            $this->cache[$fileKey] = array_values($this->cache[$fileKey]);
        }

        $count = count($this->cache[$fileKey]);
        if($count < 1) {
            return $ret;
        }

        return $this->cache[$fileKey][mt_rand(0, $count - 1)];
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('file_finder', function() {
            return $this;
        });
    }
}
