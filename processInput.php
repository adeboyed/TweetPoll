<?php
	$ignore = true;
	session_start();
	header('Content-Type: application/json');
	require_once( 'config.php' );

	//GETTING THE DATA AND 

	$searchItemsVal = filter_input(INPUT_POST, 'search_items');
	$searchItemsVal = strip_tags( trim( $searchItemVal ) );

	if ( $searchItemsVal == null ){
		errorMessage( 1 );
	}else if ( !searchItemsVal ){
		errorMessage( 1 );
	}else if ( !isNumeric( $searchItemsVal ) ){
		errorMessage( 1 );
	}
	
	$searchItemsNo = intval( $searchItemsVal );

	if ( $searchItemsNo > 5 || $searchItemsNo < 1 ){
		errorMessage( 2 );
	}

	$searchItems = array();

	for ( $i = 1; $i <= $searchItemsNo; $i++ ){
		$searchItem = filter_input(INPUT_POST, 'search_item' . $i );
		if ( $searchItem == null ){
			errorMessage( 3 );
		}else if ( !$searchItem ){
			errorMessage( 3 );
		}else if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 20 ){
			errorMessage( 3 );
		}else if ( !ctype_alnum ( $searchItem ) ){
			errorMessage( 3 );
		}
		
		$searchItem = strip_tags( trim( $searchItem ) );
		array_push( $searchItems, $searchItem );
	}


	//CHECKING THE DATABASE IF IT ALREADY EXISTS

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
			$json = json_decode ( $result )
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

	function errorMessage( $messageNo ){
		header("Location: https://tweetpoll.co/");
		exit();
	}

	function generateNewResult( $query ) {
		$tweets = getTweets( $query );
		
		
	}

	function getTweets( $query ){
		
	}

	function naiveBayes( $text ){
		
	}