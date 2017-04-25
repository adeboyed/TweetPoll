<?php

	require_once( 'config.php' );
	require_once( 'exportClass.php' );

	if ( !isset( $ignore ) ){
		header("Location: https://tweetpoll.co/");
		exit();
	}
	
	function generateResults( $searchItem ){
		
		$returnItem = new exportClass;
		
		/*$mysqli_conn = new mysqli( $db_host, $db_username, $db_password,$db_name );
		if ( $mysqli_conn->connect_error ) {
			errorMessage(101);
		}
		
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
			$returnItem = json_decode ( $result );
			$returnItem->timeAgo = time_elapsed_string( $time );
			
			$stmt->close();
		}else {
			$stmt->close(); */
			$returnItem = generateNewResult ( $searchItem );
		/*
			$stmtAdd = $mysqli_conn->prepare("INSERT INTO search_items ( search_query, search_time, search_items ) VALUES ( ? , ? , NOW() ) ");
			$stmtAdd->bind_param('ss', $value, json_encode( $json ) );
			$stmtAdd->execute();
			$stmtAdd->close();
		} */
		//$mysqli_conn->close();
		
		
		return $returnItem;
	}

	
