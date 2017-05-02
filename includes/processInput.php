<?php
	$ignore = true;
	session_start();
	header('Content-Type: application/json');

	require_once 'Requests.php';
	require_once 'exportClass.php' ;
	require_once 'config.php';

	function getUserIP() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'null';
		return $ipaddress;
	}

	$serverNo = 2;

	$backendServers = array (
		'http://api.tweetpoll.co/',
		'http://api2.tweetpoll.co/'
	);

	//CHECKING THE TOKEN
	
	if ( !isset( $_SESSION['token'] ) || !isset( $_POST['submit_token'] )  ){
		errorMessage(0, null);
	}else {
		$tokenFromSession = $_SESSION['token'];
		$tokenFromPost = $_POST['submit_token'];
		
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
		errorMessage( 3 , null);
	}else if ( !$searchItem ){
		errorMessage( 3 , null);
	}else if ( strlen( $searchItem ) < 4 || strlen( $searchItem ) > 30 ){
		errorMessage( 3 , null);
	}else if ( !coolCheck ( $searchItem ) ){
		errorMessage( 3 , null);
	}

	$searchItem = strip_tags( $searchItem );
	$searchItem = strtolower( $searchItem );

	$returnItem = new exportClass;

	$mysqli_conn = new mysqli( $db_host, $db_username, $db_password,$db_name );
	if ( $mysqli_conn->connect_error ) {
		errorMessage( 101 , null);
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
		var_dump ( $result );
		$returnItem->timeAgo = time_elapsed_string( $time );
		
		$stmt->close();
	}else {
		$stmt->close();  
		
		$options = array(
			'item' => $searchItem
		);
		
		$serverNum = (date('s') % $serverNo);
		$address = $backendServers[$serverNum];
		
		$response = Requests::post( $address , $headers, $options );
		$body = $response->body;
		$returnItem = json_decode( $body );
		
		if ( $returnItem != null ){
			$stmtAdd = $mysqli_conn->prepare("INSERT INTO search_items ( search_query, search_result, search_time ) VALUES ( ? , ? , NOW() )");
			$stmtAdd->bind_param('ss', $searchItem, $body );
			$stmtAdd->execute();
			$stmtAdd->close();
		}else {
			$mysqli_conn->close(); 
			errorMessage(9, $body);
		}
	} 
	
	$returnItem = getDayAgo( $mysqli_conn, $returnItem );

	$mysqli_conn->close();
	
	unset( $returnItem->errorNo );

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

	function getDayAgo( $mysqli_conn,  $result ){
		$stmt = $mysqli_conn->prepare("SELECT search_result, search_time FROM search_items WHERE search_query = ? AND search_time <= now() - interval 1 day AND search_time > now() - interval 2 day ORDER BY search_time DESC LIMIT 1");
		$stmt->bind_param('s', $result->query );
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result( $resultV, $time );
		$stmt->fetch(); 

		if ( $stmt->num_rows == 1 ){
			$returnItem = json_decode ( $resultV );
			$positive = $returnItem->positive;
			
			$change = $positive - $result->positive;
			
			if ( $change > 0 ){
				$result->change = '+ ' . $change;
			}else if ( $change == 0 ) {
				$result->change = 'No Change';
			}else {
				$result->change = '- ' . ( -1 * $change );
			}
			$stmt->close();
			return $result;
		}else {
			$result->change = '';
			$stmt->close();
			return $result;
		}
	}

	function coolCheck($string) {
		return preg_match("/^['.a-zA-Z0-9\s]*$/", $string);
	}

	function errorMessage( $messageNo, $body ){
		$class = new exportClass;
		$class->status = false;
		
		if ( $body != null ){
			$class->body = $body;	
		}
		
		$searchItem = filter_input(INPUT_POST, 'search_item');
		
		error_log( 'NO: ' . $messageNo . ' TIMESTAMP: ' . date ( 'Y-m-d H:i:s' , $_SERVER['REQUEST_TIME'] ) . ' QUERY: ' . $searchItem . ' BODY: ' . $body , 3, 'error.log' );
		
		$class->errorNo = $messageNo;
		echo json_encode( $class );
		exit();
	}