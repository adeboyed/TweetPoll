<?php

/*
 * TweetPoll Twitter Interface
 */

require_once('Exchange.php');
class TwitterInterface {

    // App Keys
    private $keysets = 3;

    // 1
    private $consumerKey_1 = 'xWiTUMZfrqFlakjlQpKHXWMka';
    private $consumerSecret_1 = 'z5OsT09Qv7B6jpLlHtx5Hh3nwIzgaSjDP8oI1V6fdBybv08tsX';
    private $oAuthToken_1 = '2688125125-VfKk6GU0NRIuWfFkbr46x17cUHMleS1r2hlZEjM';
    private $oAuthSecret_1 = 'XrOHlFHAYYOZVXgVjnij1p7nERCTKga4IyAwW8lFdSZcw';

    // 2
    private $consumerKey_2 = '60pRYoyAAm6c9mFWfq6YTGoXB';
    private $consumerSecret_2 = 'Fpb4W59W3wvCO6FNORbWE0HAnssyEAk4x2TquEMuTdyXcEXI1A';
    private $oAuthToken_2 = '2688125125-44ouwSxoDvOaGloKNf5st1JP7ko0u8wCRa9eSpT';
    private $oAuthSecret_2 = 'XbbKWEhjQA0hMIM8RdWHME1r7QKYa7ZIrsomXy5GSQy3m';

    // 3
    private $consumerKey_3 = '3gZvt1xFHEjmHgVFj66DfNa7K';
    private $consumerSecret_3 = 'BnXybsfkQNl5AlVfr8KuoQsRWjENoHi6pvkZfnOThYQzUkCJG4';
    private $oAuthToken_3 = '533523417-7CTtxa8S3oPL2NTd9rhRpBFZuTiZsSfvnbJISas7';
    private $oAuthSecret_3 = 'ndYbg6gq4lEJlcHI3GW2q1pfH0uqGrNelcaxZ5eF3mNwf';

    // 4
    //private $consumerKey_4 = '1gjylV87gbqFOBUSD6SqerRLJ';
    //private $consumerSecret_4 = 'jhsG7nGmqqgXaFxuCbqxxuYe5CorQLPwY2ocZCHRdj8bwTLmPl';
    //private $oAuthToken_4 = '856985557385269248-4EJ35JJOjGfgKT59QTszpqocvNNwV3a';
    //private $oAuthSecret_4 = 'NOl5G00nOqUCKmRQWvADopbrQ8rrIYmkAjk7mnWjbObrC';

    // 5
    //private $consumerKey_5 = '2hCSpVVcNEyjqmyqPUrCXbjyC';
    //private $consumerSecret_5 = 'tYwTA98FuyjUQCvRRDCw9LyKWJNVzKptrVkxAxnEeiuWVuO5hY';
    //private $oAuthToken_5 = '853915504687407104-hHHIXXlGPG39SftkLfKQpLmRlu885m1';
    //private $oAuthSecret_5 = 'lg96plhXZvzjQMlSzB3rm3DiOfxSZNdxHz5GUxQ7hwWTu';

    // 6
    //private $consumerKey_6 = 'OPdLQky2F4Y94UeBIEfgzinnh';
    //private $consumerSecret_6 = 'MdUqiEeJo20AnPi1Gs8UsfsCEO2Jil0cSs48xwWG9G65B4Ujl8';
    //private $oAuthToken_6 = '2786239599-OGBWo17EHgWBExwzo37tFWK2xDLSZWeHaK2b7xl';
    //private $oAuthSecret_6 = 'ZGz2aim9PaMJFgZbHRGmrLneuIhNhp8scvdtWzYosZAV0';

    private $settings;
    private $exchange;
    private $target = '_TweetPoll';
    public $data;
    private $debug = false;


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

    public function getTweets($query, $number, $depth, $cursor = '') {
		
        $this->useSettings((date('i') % $this->keysets) + 1);

        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $cursor_param = '';
        $next = $number;

        if( $cursor !== '' ) $cursor_param = '&max_id='.$cursor;
        else $this->data = [];
		
		if ( $depth == 0 ){
			return $this->data;
		}

        $getfield = '?q=' . urlencode('"'.$query.'"') . "&lang=en&count=100" . $cursor_param;

        $twitter = new TwitterAPIExchange($this->settings);
        $results = $twitter->setGetfield($getfield)->buildOauth($url, 'GET')->performRequest();
        $results = json_decode($results, true);

        if ( !isset( $results['errors'] ) ) {
            $cursor = 0;
            foreach ( $results['statuses'] as $tweet ) {
                if( $tweet['user']['followers_count'] > 30 ) {
                    if( $next > 0 ) {
                        array_push( $this->data, $tweet['text'] );
                        $next--;
                    }
                }
                $cursor = $tweet['id'];
            }

            if( $cursor > 0 && $next > 0 ) {
                $this->getTweets( $query, $next, $depth - 1, $cursor );
            } else {
                return $this->data;
            }
        } else {
            if ( $this->debug ){
				print( $results['errors'][0]['message'] );
			} else {
				error_log( $results['errors'][0]['message'], 3, 'error.log' );
			}
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
