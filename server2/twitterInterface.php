<?php

/*
 * TweetPoll Twitter Interface
 */

require_once('Exchange.php');
class TwitterInterface {

    // App Keys
    private $consumerKey = 'GB56YcodmfbyedX5x9Ng0Zivh';
    private $consumerSecret = '6eTgxabpQjBrxZeA1wIdrsCfEEZez3flZBM6unnwuO6MsjuRyh';
    private $oAuthToken = '856985557385269248-GJ02EjTKodYEFantX90ARhJY1ZiiJFe';
    private $oAuthSecret = '5T5khxcai72db7crbeudVY4z2lw0qgExQqu2dKd2xnk2F';

    private $settings;
    private $exchange;
    private $target = '_TweetPoll';
    private $data;
    private $debug = true;

    public function __construct() {
        $this->settings = array(
           'oauth_access_token' => $this->oAuthToken,
           'oauth_access_token_secret' => $this->oAuthSecret,
           'consumer_key' => $this->consumerKey,
           'consumer_secret' => $this->consumerSecret
       );

       $this->exchange = new TwitterAPIExchange($this->settings);
    }


    public function getTweets($query, $number, $cursor = '') {
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
 // }
