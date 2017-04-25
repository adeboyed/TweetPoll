<?php

	require_once( 'config.php' );
	require_once( 'exportClass.php' );

	if ( !isset( $ignore ) ){
		header("Location: https://tweetpoll.co/");
		exit();
	}
	
	function generateResults( $searchItems ){
		$mysqli_conn = new mysqli( $db_host, $db_username, $db_password,$db_name );
		if ( $mysqli_conn->connect_error ) {
			errorMessage(101);
		}

		$returnItems = array();

		foreach ($searchItems as &$value) {
			$stmtLog = $mysqli_conn->prepare("INSERT INTO search_log ( search_query, search_time ) VALUES ( ?, NOW() ) ");
			$stmtLog->bind_param('s', $value );
			$stmtLog->execute();
			$stmtLog->close();
			
			$stmt = $mysqli_conn->prepare("SELECT search_result, search_time FROM search_items WHERE search_query = ? AND search_time > DATEADD(HOUR, -1, GETDATE()) LIMIT 1");
			$stmt->bind_param('s', $value );
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result( $result, $time );
			$stmt->fetch();

			if ( $stmt->num_rows == 1 ){
				$json = json_decode ( $result );
				$json->timeAgo = time_elapsed_string( $time );
				array_push( $resultItems, $json );
				$stmt->close();
			}else {
				$stmt->close();
				$json = generateNewResult ( $value );
				array_push ( $returnItems, $json );

				$stmtAdd = $mysqli_conn->prepare("INSERT INTO search_items ( search_query, search_time, search_items ) VALUES ( ? , ? , NOW() ) ");
				$stmtAdd->bind_param('ss', $value, json_encode( $json ) );
				$stmtAdd->execute();
				$stmtAdd->close();
			}
		}
		
		
		echo json_encode( $returnItems );

		$mysqli_conn->close();
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
