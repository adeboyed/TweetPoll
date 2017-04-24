<?php
	$ignore = true;
	session_start();
	header('Content-Type: application/json');
	require_once( 'functions.php' );

	//GETTING THE DATA AND 

	$searchItemsVal = filter_input(INPUT_POST, 'search_items');
	$searchItemsVal = strip_tags( trim( $searchItemsVal ) );

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
		}else if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ){
			errorMessage( 3 );
		}else if ( !ctype_alnum ( $searchItem ) ){
			errorMessage( 3 );
		}
		
		$searchItem = strip_tags( trim( $searchItem ) );
		array_push( $searchItems, $searchItem );
	}

	generateResults( $searchItems );

	function errorMessage( $messageNo ){
		//header("Location: https://tweetpoll.co/");
		//exit();
	}