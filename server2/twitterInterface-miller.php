<?php

/*
 * TweetPoll Twitter Interface
 */

require_once('Exchange.php');
class TwitterInterface {

    // App Keys
    private $keysets = 5;

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
    private $consumerKey_3 = 'iwzLqe3XbL76G4UbrLu52ssOI';
    private $consumerSecret_3 = 'XbYClXeU20HtRqmjQwRi4m5kOu9CDVpapCXABUZAc6GYe11vLf';
    private $oAuthToken_3 = '853915504687407104-Subi3KJyt7mxalmGiZZuX8T4EwV2j7R';
    private $oAuthSecret_3 = '0wEf4iDrY4xKt3XvIC4HY9gPD76UPooZU1wEUhGwSQ5l3';

    // 4
    private $consumerKey_4 = '7zh6MKjlE3j1gSA4VTAlqSNMr';
    private $consumerSecret_4 = 'f1T1CmezuK8X71TZTp55S8t9by84Fgl4q2jli0PBbGd2R6gzxn';
    private $oAuthToken_4 = '853915504687407104-KphXdgWVnF0mBJulLeR2l3RHm93R5U7';
    private $oAuthSecret_4 = 'DRk4G3cSaKhil3ZYAa9fRNCvbSoZ52cyQpmmsH0GSu2Hl';

    // 5
    private $consumerKey_5 = '4xUf7NO40e12kPngZVAImqw84';
    private $consumerSecret_5 = 'l9ARfWvBzH67wilMnFTBwbAy3k5pMExKn0QhFDUkQ4cyQzYbD3';
    private $oAuthToken_5 = '853915504687407104-RGGKy8PljRJvdzgRxBO2eBt80XL4L1G';
    private $oAuthSecret_5 = 'ZpTRags4GsKPjk8VPaYRTsUyar8B2nBBrl2uXyePE3UIm';

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
