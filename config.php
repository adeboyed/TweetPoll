<?php

	if ( !isset( $ignore ) ){
		header("Location: https://tweetpoll.co/");
		exit();
	}

	$db_host = 'localhost';
	$db_username = 'root';
	$db_password = 'root';
	$db_name = 'tweetpoll';