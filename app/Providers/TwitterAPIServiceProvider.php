<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp;
use Illuminate\Support\Str;

class  TwitterAPIServiceProvider extends ServiceProvider
{

    // all auth values
    protected $oauth_consumer_key;
    protected $oauth_consumer_secret;
    protected $bear_token;
    protected $authorization_header;

    /**
     * @var GuzzleHttp\Client $client
     */
    protected $client;

/**
 * searches twitter for users tweets
 * @param $user
 * @return null
 */
    public function searchTweets($user)
    {

        // make sure that we have @ with username for proper query
        if(!Str::contains($user, '@'))
            $user = urlencode(sprintf('@%s', $user));


        $search_response = [];

        $url = sprintf('https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=%s&count=200', $user);
        $this->client->requestAsync('GET', $url, [
            'headers' => [
                'Host' => 'api.twitter.com',
                'User-Agent' => 'Twitter-Handle-Mentions',
                'Authorization' => $this->authorization_header,
                'Accept-Encoding' => 'gzip',
            ]
        ])->then(function ($response) use (&$user, &$search_response, &$empty_search) {
            if ($response->getStatusCode() !== 200)
                \Log::error(sprintf('Failed to search user %s' , $user));

            // decode the data
            $json_obj =  json_decode($response->getBody()->getContents(), true);
            $search_response[] = $json_obj;
        },
            function (GuzzleHttp\Exception\GuzzleException $e) {
                \Log::error($e->getMessage() . "\n");
                \Log::error($e->getRequest()->getMethod());
            }
        )->wait();


        return $this->parseSearchData($search_response);

    }

    public function searchUser($user)
    {
        // make sure that we strip @ off username
        if(Str::contains($user, '@'))
            $user = str_replace('@', '', $user);


        $search_response = [];

        $url = sprintf('https://api.twitter.com/1.1/users/show.json?screen_name=%s', $user);
        $json = null;
        $this->client->requestAsync('GET', $url, [
            'headers' => [
                'Host' => 'api.twitter.com',
                'User-Agent' => 'Twitter-Handle-Mentions',
                'Authorization' => $this->authorization_header,
                'Accept-Encoding' => 'gzip',
            ]
        ])->then(function ($response) use (&$user, &$json) {
            if ($response->getStatusCode() !== 200)
                \Log::error(sprintf('Failed to search user %s' , $user));


            $json =  $response->getBody()->getContents();

//            $search_response[] = $json_obj;
        },
            function (GuzzleHttp\Exception\GuzzleException $e) {
                \Log::error($e->getMessage() . "\n");
                \Log::error($e->getRequest()->getMethod());
            }
        )->wait();

        return $json;
    }


    public function getTotalTweets($data)
    {
        $ret = 0;
        collect($data)->sum(function ($item) use (&$ret) {
            $ret += $item['count'];
        });

        return $ret;
    }

    /**
     * parses searched data
     * @param $data array
     * @return array
     */
    protected function parseSearchData($data)
    {

       $tweets = $this->pullOutTweets($data);
       return  $this->sortTweets($tweets);

    }

    protected function pullOutTweets($data)
    {
        $ret = [];
        // parse out username, time, and tweet
        collect($data)->each(function($item) use (&$ret){
            collect($item)->each(function ($item) use (&$ret) {

                $time_parts = explode('+', $item['created_at']);
                $time = $time_parts[0];

                $text = $item['text'];
                preg_match_all('/@([^:][\\w\\W\\D]+?)\\s|:/', $text, $matches);

                if(!isset($matches))
                {
                    return;
                }

                foreach ($matches[1] as $match)
                {
                    if(Str::length($match) >= 1)
                    {
                        if (Str::contains($match,':'))
                            $match = str_replace(':', '', $match);

                        $ret[] = ['username' => $match,
                            'tweet' => $text,
                            'time' => $time,
                        ];
                    }
                }
            });
        });

        return $ret;
    }

    protected function sortTweets($data)
    {
        $unsorted = [];
        collect($data)->each(function ($item) use(&$unsorted, &$max, &$ordered_accounts) {

            // Build unsorted array mapping all tweets of user to user
            if (!array_key_exists($item['username'], $unsorted))
            {
                $unsorted[$item['username']] = [
                    'user' => $item['username'],
                    'count' => 1
                ];

                $unsorted[$item['username']]['tweets'][] = ['tweet' => $item['tweet'], 'time' => $item['time']];

            }
            else {
                $unsorted[$item['username']]['count'] += 1;
                // only grab the 5 most recent tweets
                if($unsorted[$item['username']]['count'] <= 5)
                    $unsorted[$item['username']]['tweets'][] = ['tweet' => $item['tweet'], 'time' => $item['time']];
            }
        });

        // sort tweets users by most tweets
        $sorted = collect($unsorted)->sortByDesc('count')->values()->all();

        // finalize data
        $ret = [];
        collect($sorted)->each(function ($item) use (&$ret ,&$max) {

            $ret[] = ['user'=> $item['user'],
                'count' => $item['count'],
                'tweets' => $item['tweets']
            ];
        });

        return $ret;
    }



    /**
     * builds http client
     */
    protected function buildGuzzleClient()
    {
        $this->client = app()->make('guzzle_http')->buildGuzzleHttpClient();
    }

    /**
     * gets bearer authorization from twitter
     */
    protected function getBearerToken()
    {
        $this->oauth_consumer_key= urlencode('AEZHWHnBrAf7L9213t5xryBJM');
        $this->oauth_consumer_secret = urlencode('5q1J5WRVeYejqgomD4Y9F0Gs8Ojyv0UZH9J2zb0kKsZlJ9v71K');

        $key_secret = sprintf('%s:%s', $this->oauth_consumer_key, $this->oauth_consumer_secret);
        $key_secret = base64_encode($key_secret);
        $authorization = sprintf('Basic %s', $key_secret);

        $url = 'https://api.twitter.com/oauth2/token';
        $this->client->requestAsync('POST', $url, [
            'headers' => [
                'Host' => 'api.twitter.com',
                'User-Agent' => 'Twitter-Handle-Mentions',
                'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8',
                'Authorization' => $authorization,
                'Accept-Encoding' => 'gzip',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]

        ])->then(function($response) {

            if ($response->getStatusCode() !== 200)
                \Log::error('Failed to get barer token');

            $jsonObj = json_decode($response->getBody()->getContents(), true);
            $this->bear_token =  urldecode($jsonObj['access_token']);

        })->wait();

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
        $this->app->singleton('twitter_api', function() {

            // build http client
            $this->buildGuzzleClient();

            // get bearer token for authentication (from cache or fresh)
            $this->authorization_header = \Cache::remember('twitter::authorization_header', 10, function() {

                $this->getBearerToken();

                return sprintf('Bearer %s', $this->bear_token);
            });

            return $this;


        });
    }
}
