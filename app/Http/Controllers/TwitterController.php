<?php

namespace App\Http\Controllers;

use App\Providers\TwitterAPIServiceProvider;
use Carbon\Carbon;
use GuzzleHttp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TwitterController extends Controller
{

    /**
     * @var GuzzleHttp\Client $client
     */
    protected $client;


    public function index()
    {

        return view('twitter.index');

    }

    public function searchUserTweets(Request $request)
    {
        $user = trim($request->input('username'));

        /**
         * @var TwitterAPIServiceProvider $twitter
         */
        $twitter = app()->make('twitter_api');

        $twitter_accounts = \Cache::remember(sprintf('tweets-by-user::%s', $user), 60, function() use ($twitter, $user) {

            return $twitter->searchTweets($user);

        });

        $total_tweets = $twitter->getTotalTweets($twitter_accounts);


        return view('twitter.search', compact('twitter_accounts', 'user', 'total_tweets'));

    }

    public function searchUser(Request $request)
    {
        $user = trim($request->input('username'));

        /*
         * @var TwitterAPIServiceProvider $twitter
         */
        $twitter = app()->make('twitter_api');


        $twitter_user = \Cache::remember(sprintf('twitter-user-profile::%s', $user), 60, function() use ($twitter, $user) {

             return $twitter->searchUser($user);

        });

        return response()->json($twitter_user);

    }




}
