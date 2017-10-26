<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Log;
use GuzzleHttp;

class GuzzleHttpServiceProvider extends ServiceProvider
{

    protected $proxy;
    protected $proxy_auth;
    protected $options;

    public function buildGuzzleHttpClient()
    {

        // get a proxy if there is one
        $this->GetProxy();

       // set guzzle options
        $this->setGuzzleOptions();

        // finally setup guzzle
        return new GuzzleHttp\Client($this->options);

    }

    protected function getProxy()
    {
        $this->proxy = null;

        $random_proxy = app()->make('file_finder')->getRandom('proxies');

        // check if proxy has username:auth
        if (Str::length($random_proxy) >= 1) {
            $proxyParts = explode(':', $random_proxy);
            $this->proxy = trim($proxyParts[0]) . ':' . trim($proxyParts[1]);
//            \Log::info('Proxy = [' . $this->proxy . ']');
            if (isset($proxyParts[2])) {
                $this->proxy_auth = trim($proxyParts[2]) . ':' . trim($proxyParts[3]);
//                \Log::info('Auth = [' . $this->proxy_auth . ']');
            }
        }
    }

    protected function setGuzzleOptions()
    {
        $this->options = [
            'verify' => false,
            'defaults' => [
                'verify' => false,
            ],
            'cookies' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
            ]
        ];

//        $this->proxy = '127.0.0.1:10000';

        // No proxy
        if(!isset($this->proxy) || Str::length($this->proxy) <= 1)
            return;

        // set proxy options if proxy present
        if(Str::length($this->proxy_auth) >= 1)
        {
            $this->options['proxy'] = sprintf('http://%s@%s', $this->proxy_auth, $this->proxy);
        } else
        {
            $this->options['proxy'] = sprintf('http://%s', $this->proxy);
            $this->options['defaults']['proxy'] = sprintf('http://%s', $this->proxy);
        }
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
        $this->app->singleton('guzzle_http', function () {
            return $this;
        });
    }
}
