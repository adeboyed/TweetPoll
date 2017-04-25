<?php

	$ignore = true;
	header('Content-Type: application/json');
	require_once('exportClass.php');
	
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
			}else if ( !ctype_alnum ( $searchItem ) ){
				errorMessage( 13 );
			}
			
			$searchItem = strip_tags( trim( $searchItem ) );
			$searchItem = strtolower( $searchItem );
			
			$returnItem = generateNewResult ( $searchItem );
			echo json_encode( $returnItem );
			
		}else {
			//Nothing
		}
	}else {
		//Nothing
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
		$tweets = getTweets( $query, 100 );
		
		$positive = 0;
		$negative = 0;
		
		$result = new exportClass;
		
		foreach ( $tweets as &$value ){
			$score = naiveBayes ( $value );
			
			if ( $score >= 0.5 ){
				$positive++;
			}else {
				$negative++;
			}
			
		}
		
		$result->query = $query;
		$result->positive = $positive;
		$result->negative = $negative;
		$result->timeAgo = "Just Now";
		
		return $result;
	}

	function getTweets( $query, $num ){
		$array = array();
		
		for ( $i = 0; $i < 100; $i++ ){
			$text = 'I am a tweet';
			array_push( $array, $text );
		}
		
		return $array;
	}

	function naiveBayes( $text ){
		return mt_rand() / mt_getrandmax();
	}

	function errorMessage( $messageNo ){
		$class = new exportClass;
		$class->status = false;
		$class->errorNo = $messageNo;
		echo json_encode( $class );
		exit();
	}