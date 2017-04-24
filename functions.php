<?php

	require_once( 'config.php' );

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
			$stmt = $mysqli_conn->prepare("SELECT search_result FROM search_items WHERE search_query = ? AND search_time > DATEADD(HOUR, -1, GETDATE()) LIMIT 1");
			$stmt->bind_param('s', $value );
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result( $result );
			$stmt->fetch();

			if ( $stmt->num_rows == 1 ){
				$json = json_decode ( $result );
				array_push( $resultItems, $json );
				$stmt->close();
			}else {
				$stmt->close();
				$json = generateNewResult ( $value );
				array_push ( $resultItems, $json );

				$stmtAdd = $mysqli_conn->prepare("INSERT INTO search_result ( search_query, search_time, search_items ) VALUES ( ? , ? , NOW() ) ");
				$stmtAdd->bind_param('ss', $value, json_encode( $json ) );
				$stmtAdd->execute();
				$stmtAdd->close();
			}
		}
		
		
		echo json_encode( $resultItems );

		$mysqli_conn->close();
	}

	function generateNewResult( $query ) {
		$tweets = getTweets( $query );
		
	}

	function getTweets( $query ){
		
	}

	function naiveBayes( $text ){
		
	}
