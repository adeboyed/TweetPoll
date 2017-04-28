<?php

/*
 * TweetPoll Twitter Interface
 */

require_once('Exchange.php');
class TwitterInterface {

    // App Keys
    private $keysets = 4;

    // 1
    private $consumerKey_1 = 'GB56YcodmfbyedX5x9Ng0Zivh';
    private $consumerSecret_1 = '6eTgxabpQjBrxZeA1wIdrsCfEEZez3flZBM6unnwuO6MsjuRyh';
    private $oAuthToken_1 = '856985557385269248-GJ02EjTKodYEFantX90ARhJY1ZiiJFe';
    private $oAuthSecret_1 = '5T5khxcai72db7crbeudVY4z2lw0qgExQqu2dKd2xnk2F';

    // 2
    private $consumerKey_2 = 'ifsO6DHghG5a9lBDV9W9EEuLM';
    private $consumerSecret_2 = 'zBYlOzanoTmjC7TULY3ndOkmo5Ahagzzku8MmwzzpAFgxzwH7Q';
    private $oAuthToken_2 = '856985557385269248-RTJCkfmNOJb1qBzERECGWecWY3hbzxZ';
    private $oAuthSecret_2 = 'cvZ8adXSECMl7VLorCoNA4mWB8vhKDatlGrLQoN1aKtm7';

    // 3
    private $consumerKey_3 = 'cbT9hdwPgCgcRH1NA0enIZTIx';
    private $consumerSecret_3 = 'u0l9sE4rVpIQBDJXDVkXEETblfToKFpMsBwsWI2EdBcqCzaB6q';
    private $oAuthToken_3 = '856985557385269248-IJnB0cQ1RdgRf7nYgRjTjifmM0WhyqW';
    private $oAuthSecret_3 = 'jBPa6VmxNaXuN9EeAz1uV2OLrBQEq5YgKi7yoeLfIaNbP';

    // 4
    private $consumerKey_4 = '1gjylV87gbqFOBUSD6SqerRLJ';
    private $consumerSecret_4 = 'jhsG7nGmqqgXaFxuCbqxxuYe5CorQLPwY2ocZCHRdj8bwTLmPl';
    private $oAuthToken_4 = '856985557385269248-4EJ35JJOjGfgKT59QTszpqocvNNwV3a';
    private $oAuthSecret_4 = 'NOl5G00nOqUCKmRQWvADopbrQ8rrIYmkAjk7mnWjbObrC';

    private $settings;
    private $exchange;
    private $target = '_TweetPoll';
    private $data;
    private $debug = true;

    public
    function __construct() {
       $this->exchange = new TwitterAPIExchange($this->settings);
    }

    private function useSettings($i) {
        $this->settings = array(
           'oauth_access_token' => $this->oAuthToken_$i,
           'oauth_access_token_secret' => $this->oAuthSecret_$i,
           'consumer_key' => $this->consumerKey_$i,
           'consumer_secret' => $this->consumerSecret_$i
       );
    }

    public function getTweets($query, $number, $cursor = '') {

        $this->useSettings((date('i') % $this->keysets) + 1);

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $cursor_param = '';
        $next = $number;

        if($cursor !== '') $cursor_param = '&max_id='.$cursor;
        else $this->data = [];

        $getfield = '?q=' . urlencode($query) . "&lang=en&count=100" . $cursor_param;

        $twitter = new TwitterAPIExchange($this->settings);
        $results = $twitter->setGetfield($getfield)->buildOauth($url, 'GET')->performRequest();
        $results = json_decode($results, true);

        if(!isset($results['errors'])){
            $cursor = 0;
            foreach ($results['statuses'] as $tweet) {
                if($tweet['user']['followers_count'] > 50) {
                    if($next > 0) {
                        array_push($this->data, $tweet['text']);
                        $next--;
                    }
                }
                $cursor = $tweet['id'];
            }

            if($cursor > 0 && $next > 0) {
                $this->getTweets($query, $next, $cursor);
            } else {
                return $this->data;
            }
        }

        else {
            if($this->debug) print($results['errors'][0]['message']);
            return $this->data;
        }
    }
}

/**
 * Example Implementation
 */

 // require_once('twitterInterface.php');
 // function test() {
 //     $interface = new TwitterInterface();
 //     $interface->getTweets("Theresa May", 250);
 //     $interface->data;
 // }
