<?php
	$ignore = true;
	session_start();
	header('Content-Type: application/json');
	require_once 'Requests.php';
	require_once 'exportClass.php' ;
	require_once 'config.php';

	define( 'SERVER_2_ADDRESS', 'http://localhost/server2/index.php' );

	//CHECKING THE TOKEN
	
	if ( !isset( $_SESSION['s_token'] ) || !isset( $_POST['submit_token'] )  ){
		errorMessage(0);
	}else {
		$tokenFromSession = $_SESSION['s_token'];
		$tokenFromPost = filter_input(INPUT_POST, 'submit_token');
		
		if ( strcmp ( $tokenFromSession, $tokenFromPost ) !== 0 ){
			//errorMessage( $tokenFromSession . ' ' . $tokenFromPost );
		}
	}

	Requests::register_autoloader();

	$headers = array(
		'TweetPoll-Header' => '59822d551b8b49abaabce3855e8e5964',
	);


	//GETTING THE DATA AND 

	$searchItem = filter_input(INPUT_POST, 'search_item');
	$searchItem = trim( $searchItem );

	if ( $searchItem == null ){
		errorMessage( 3 );
	}else if ( !$searchItem ){
		errorMessage( 3 );
	}else if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ){
		errorMessage( 3 );
	}else if ( !coolCheck ( $searchItem ) ){
		errorMessage( 3 );
	}

	$searchItem = strip_tags( $searchItem );
	$searchItem = strtolower( $searchItem );

	$returnItem = new exportClass;

	$mysqli_conn = new mysqli( $db_host, $db_username, $db_password,$db_name );
	if ( $mysqli_conn->connect_error ) {
		errorMessage(101);
	}

	$stmtLog = $mysqli_conn->prepare("INSERT INTO search_log ( search_query ) VALUES ( ? ) ");
	$stmtLog->bind_param('s', $searchItem );
	$stmtLog->execute();
	
	$stmtLog->close();

	$stmt = $mysqli_conn->prepare("SELECT search_result, search_time FROM search_items WHERE search_query = ? AND search_time >= now() - interval 60 minute LIMIT 1");
	$stmt->bind_param('s', $searchItem );
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result( $result, $time );
	$stmt->fetch(); 

	if ( $stmt->num_rows == 1 ){
		$returnItem = json_decode ( $result );
		$returnItem->timeAgo = time_elapsed_string( $time );
		
		$stmt->close();
	}else {
		$stmt->close();  
		
		$options = array(
			'item' => $searchItem
		);

		$response = Requests::post( SERVER_2_ADDRESS , $headers, $options );
		$body = $response->body;
		$returnItem = json_decode( $body );
		
		if ( $returnItem != null ){
			$stmtAdd = $mysqli_conn->prepare("INSERT INTO search_items ( search_query, search_result, search_time ) VALUES ( ? , ? , NOW() )");
			$stmtAdd->bind_param('ss', $searchItem, $body );
			$stmtAdd->execute();
			$stmtAdd->close();
		}else {
			$mysqli_conn->close(); 
			errorMessage(9);
		}

		
	} 
	
	$mysqli_conn->close(); 

	echo json_encode( $returnItem );

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

	function coolCheck($string) {
		return preg_match("/^[a-zA-Z0-9\s]*$/", $string);
	}

	function errorMessage( $messageNo ){
		$class = new exportClass;
		$class->status = false;
		$class->errorNo = $messageNo;
		echo json_encode( $class );
		exit();
	}