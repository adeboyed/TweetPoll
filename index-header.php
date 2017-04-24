<?php

	$ignore = true;
	session_start();
	require_once( 'functions.php' );
	
	$searchItems = array();

	if ( isset( $_GET['p1'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_POST, 'p1');
		$searchItem = strip_tags( trim( $searchItem1 ) );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p2'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_POST, 'p2');
		$searchItem = strip_tags( trim( $searchItem1 ) );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p3'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_POST, 'p3');
		$searchItem = strip_tags( trim( $searchItem1 ) );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p4'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_POST, 'p4');
		$searchItem = strip_tags( trim( $searchItem1 ) );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p5'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_POST, 'p5');
		$searchItem = strip_tags( trim( $searchItem1 ) );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( sizeof( $searchItems ) > 0 ){
		generateResults( $searchItems );
	}
