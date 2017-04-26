<?php

	$ignore = true;
	header('Content-Type: application/json');
	require_once('exportClass.php');
	require_once 'insight/autoload.php';
	require_once('twitterInterface.php');

	
	if ( isset( getallheaders() ['TweetPoll-Header'] ) ){
		$header = getallheaders() ['TweetPoll-Header'];
		
		if ( strcmp( $header, '59822d551b8b49abaabce3855e8e5964' ) == 0 ){
			
			$searchItem = filter_input(INPUT_POST, 'item');

			if ( $searchItem == null ){
				errorMessage( 13 );
			}else if ( !$searchItem ){
				errorMessage( 13 );
			}else if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ){
				errorMessage( 13 );
			}else if ( !coolCheck( $searchItem) ){
				errorMessage( 13 );
			}
			
			$searchItem = strip_tags( trim( $searchItem ) );
			$searchItem = strtolower( $searchItem );
			
			$returnItem = generateNewResult ( $searchItem );
			echo json_encode( $returnItem );
			
		}else {
			errorMessage(15);
		}
	}else {
		errorMessage(16);
	}

	function coolCheck($string) {
		return preg_match("/^[a-zA-Z0-9\s]*$/", $string);
	}

	function time_elapsed_string($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function generateNewResult( $query ) {
		$tweets = getTweets( $query, 50 );
		
		if ( is_array( $tweets ) && sizeof( $tweets ) > 0 ){
			$result = naiveBayes( $tweets );
			$result = normalise( $result );
			//sleep(3);
			$result->status = true;
			$result->query = ucwords ( $query );
			$result->timeAgo = "Just Now";
		}else {
			errorMessage(14);
		}
		
		return $result;
	}

	function getTweets( $query, $num ){
		$interface = new TwitterInterface();
		return $interface->getTweets( $query, $num );
	}

	function azureML( $tweets ){
		$toSend = array();
		$i = 1;
		foreach ( $tweets as &$value ){
			$tweet = new azureDocuments;
			$tweet->language = 'en';
			$tweet->id = $i;
			$tweet->text = $value;
			
			array_push( $toSend, $tweet );
				
			$i++;
		}
		
		$headers = array(
			'Ocp-Apim-Subscription-Key' => '0adc559e358f438aa531b30b6ac806ee',
			'Content-Type' => 'application/json',
			'Accept' => 'application/json'
		);
		
		$options = array(
			'documents' => $toSend,
		);
		
		$response = Requests::post( 'https://westus.api.cognitive.microsoft.com/text/analytics/v2.0/sentiment' , $headers, json_encode( $options ) );
		$body = $response->body;
		
		$returnItem = json_decode( $body );
		
		$documents = $returnItem->documents;
		
		$positive = 0;
		$negative = 0;
		
		foreach ( $documents as &$value ){
			$score = $value->score;
			
			if ( $score >= 0.5 ){
				$positive++;
			}else {
				$negative++;
			}
		}
		
		$result = new exportClass;
		$result->positive = $positive;
		$result->negative = $negative;
		
		//var_dump ( $result );
		
		return $result;
	}

	function naiveBayes( $tweets ){
		
		$sentiment = new \PHPInsight\Sentiment();
		$result = new exportClass;
		$result->positive = 0;
		$result->negative = 0;
		
		foreach ( $tweets as &$value ){
			$class = $sentiment->categorise( $value );
			if ( strcmp( $class, 'neg' ) == 0 ) $result->negative++;
			if ( strcmp( $class, 'pos' ) == 0 ) $result->positive++;
		}
		return $result;
	}

	function normalise ( $result ){
		$total = $result->positive + $result->negative;
		
		if ( $total == 100 ) return $result;
		
		$posPercent = $result->positive / $total;
		$negPercent = $result->negative / $total;
		
		$result->positive = (int) ( $posPercent * 100 );
		$result->negative = (int) ( $negPercent * 100 );
		
		return $result;
	}


	function errorMessage( $messageNo ){
		$class = new exportClass;
		$class->status = false;
		$class->errorNo = $messageNo;
		echo json_encode( $class );
		exit();
	}