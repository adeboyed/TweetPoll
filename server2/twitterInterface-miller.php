<?php

/*
 * TweetPoll Twitter Interface
 */

require_once('Exchange.php');
class TwitterInterface {

    // App Keys
    private $keysets = 2;

    // 1
    private $consumerKey_1 = 'poYRp6UiZQYinFRU7cHGkSk0F';
    private $consumerSecret_1 = 'Xk7OMC8AeOuevD6Npsei5gyB7pgwQm85mZ4SxJuyxEWqd0gJr8';
    private $oAuthToken_1 = '2688125125-LxMFKiDt5wCvoQoPd9IYD2Szffe8C1TpdqbdM5V';
    private $oAuthSecret_1 = '0qfIMHOc7lngzCcnOqVKAQwooMNjtCRqrZSPNsd5FZwrL';

    // 2
    private $consumerKey_2 = 'kUc1i9gVrojkhzCnLVs1HwAtf';
    private $consumerSecret_2 = 'FMc5u9C8sA8U43LchBT6mC1jypl0de0g85iSaCEzdejKb80sCR';
    private $oAuthToken_2 = '2688125125-0l6aBTGpL362nduFMQmCN0FDg85MpeRSevAwunU';
    private $oAuthSecret_2 = 'TIDkf6ePWgcjWuPIwhrV5bgg810t82sVzltHeO21PKtGH';

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
    public $data;
    private $debug = true;


    private function useSettings($i) {
        $oauth_access_token = "oAuthToken_" . $i;
        $oauth_access_token_secret = "oAuthSecret_" . $i;
        $consumer_key = "consumerKey_" . $i;
        $consumer_secret = "consumerSecret_" . $i;

        $this->settings = array(
           'oauth_access_token' => $this->$oauth_access_token,
           'oauth_access_token_secret' => $this->$oauth_access_token_secret,
           'consumer_key' => $this->$consumer_key,
           'consumer_secret' => $this->$consumer_secret
       );

       $this->exchange = new TwitterAPIExchange($this->settings);
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
