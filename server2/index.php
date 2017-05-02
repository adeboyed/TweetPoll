
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
		return preg_match("/^['.a-zA-Z0-9\s]*$/", $string);
	}

	function generateNewResult( $query ) {
		$tweets = getTweets( $query, 250 );
		
		if ( is_array( $tweets ) && sizeof( $tweets ) > 0 ){
			$result = naiveBayes( $tweets );
			if ( $result->negative > 0 && $result->positive > 0 ){
				$result->totalClassified = $result->positive + $result->negative;
				$result = normalise( $result );
				$result->status = true;
				$result->query = ucwords ( $query );
				$result->timeAgo = "Just Now";
				$result->totalOut = sizeof( $tweets );
				unset( $result->errorNo );
			}else {
				errorMessage(15);
			}
		}else {
			errorMessage(14);
		}
		
		return $result;
	}

	function getTweets( $query, $num ){
		$interface = new TwitterInterface();
		$interface->getTweets( $query, $num, 7 );
		return $interface->data;;
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
		unset( $result->errorNo );
		
		return $result;
	}

	function naiveBayes( $tweets ){
		
		$sentiment = new \PHPInsight\Sentiment();
		$result = new exportClass;
		$result->positive = 0;
		$result->negative = 0;
		
		$mostPositiveTweet = null;
		$mostPositiveScore = 0;
		
		$mostNegativeTweet = null;
		$mostNegativeScore = 0;
		
		foreach ( $tweets as &$value ){
			$class = $sentiment->categorise( $value );
			$scores = $sentiment->score($value);
			if ( $scores['neg'] > 0.40 ){
				$result->negative++;
				
				if ( $scores['neg'] > $mostNegativeScore ){
					$mostNegativeTweet = $value;
					$mostNegativeScore = $scores['neg'];
				}
				
			} 
			if ( $scores['pos'] > 0.40  ){
				$result->positive++;
				
				if ( $scores['pos'] > $mostPositiveScore ){
					$mostPositiveTweet = $value;
					$mostPositiveScore = $scores['pos'];
				}
			}
		}
		
		$result->mostPositive = $mostPositiveTweet;
		$result->mostNegative = $mostNegativeTweet;
		
		return $result;
	}

	function normalise ( $result ){
		$total = $result->positive + $result->negative;
		
		if ( $total == 100 ) return $result;
		
		$posPercent = $result->positive / $total;
		$negPercent = $result->negative / $total;
		
		$result->positive = (int) round( $posPercent * 100 );
		$result->negative = (int) round( $negPercent * 100 );
		
		return $result;
	}


	function errorMessage( $messageNo ){
		$class = new exportClass;
		$class->status = false;
		$class->errorNo = $messageNo;
		echo json_encode( $class );
		exit();
	}