<?php
	session_start();
	$searchItems = array();

	$token = generateRandomString( 20 );
	$_SESSION['token'] = $token;

	if ( isset( $_GET['p1'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p1');
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p2'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p2');
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p3'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p3');
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p4'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p4');
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}

	if ( isset( $_GET['p5'] ) ){
		
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p5');
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !ctype_alnum ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
	}


	if ( sizeof ( $searchItems ) > 0 ){
		//Do something
	}

	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	};

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>TweetPoll</title>
		<meta name="viewport" content="width=100%, initial-scale=1.0, maximum-scale=1.0">
		<!-- Loading Bootstrap -->
		<!-- <link href="css/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
		<!-- Loading Flat UI -->
		<link href="css/flat-ui.css" rel="stylesheet">
		<link href="css/index.css" rel="stylesheet" type="text/css" >
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
		<!--[if lt IE 9]>
		<script src="dist/js/vendor/html5shiv.js"></script>
		<script src="dist/js/vendor/respond.min.js"></script>
		<![endif]-->

		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="images/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="images/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="images/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="images/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="images/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="images/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="images/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="images/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="images/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="images/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="&nbsp;"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />
    <link rel="stylesheet" href="https://code.cdn.mozilla.net/fonts/fira.css">
		<link href="https://fonts.googleapis.com/css?family=Ranga" rel="stylesheet">
		<script src="js/vendor/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
		<!-- <script type="text/javascript" src-"js/jquery.foggy.min.js"></script> -->
		<script type="text/javascript" src="js/velocity.min.js"></script>
	</head>
	<body>
		<div id="bg-img"></div>
		<div id="bg-overlay"></div>
		<h1 id="main-title">TweetPoll</h1>
		<input type="text" name="" id="main-search-box"
			maxlength="28" placeholder="Query the opinion harvester...">

		<div id="searches-container">
			<div class="search-element-group-template display-none">
				<h2 class="search-element-search">Jeremy Corbyn</h2>
				<h4 class="search-element-updated">Last Updated: 3 hours ago</h4>
				<div class="search-element-bars">
					<div class="search-element-loading">
						<div class="search-element-loading-overlay"></div>
					</div>
					<div class="search-element-percentage">
						<div class="search-element-green"></div>
						<div class="search-element-red"></div>
					</div>
				</div>
				<h3 class="search-percent-green">23%</h3>
				<h3 class="search-percent-red">77%</h3>
			</div>
		</div>

		<!-- <div id="page-container">
			<h1>TweetPoll</h1>
			<h2 id="tagline">The voice of the people</h2>
			<div id="search-container">
				<div class="search-element-group display-none" id="search-element-group">
					<h2 class="search-element-search">Jeremy Corbyn</h2>
					<h4 class="search-element-updated">Last Updated: 3 hours ago</h4>
					<div class="search-element-bars">
						<div class="search-element-loading">
							<div class="search-element-loading-overlay"></div>
						</div>
						<div class="search-element-percentage">
							<div class="search-element-green"></div>
							<div class="search-element-red"></div>
						</div>
					</div>
					<h3 class="search-percent-green">23%</h3>
					<h3 class="search-percent-red">77%</h3>
				</div>
			</div>


			<canvas id="page-graph">

			</canvas>
		</div> -->

		<script>
			var searches = [
				['Jeremy Corbyn', '3 hours ago', 23],
				['Theresa May', '1 hour ago', 12],
				['Donald Trump', '10 minutes ago', 32],
				['Harry Maughan', '5 hours ago', 44],
				['David Adeboye', '45 minutes ago', 23],
				['Devavrata Soni', '13 minutes ago', 4],
			];
			
			function getRandomInt(min, max) {
			  min = Math.ceil(min);
			  max = Math.floor(max);
			  return Math.floor(Math.random() * (max - min)) + min;
			};
			function createSearch(name, updateTime, greenPercent) {
				var $searchBlock = $(".search-element-group-template").clone();
				$searchBlock.removeClass("search-element-group-template");
				$searchBlock.addClass("search-element-group");
				$searchBlock.prependTo("#searches-container");
				$searchBlock.find(".search-percent-green").html(greenPercent.toString().concat("%"));
				$searchBlock.find(".search-percent-red").html((100-greenPercent).toString().concat("%"));
				$searchBlock.find(".search-element-search").html(name);
				$searchBlock.find(".search-element-updated").html("Last Updated: ".concat(updateTime));
				$searchBlock.removeClass("display-none");
				return $searchBlock;
			};
			function animateSearch($element) {
				var loadingTime = getRandomInt(1000, 5000);
				var greenNum = getRandomInt(5, 95);
				$($element).find(".search-element-loading-overlay").velocity({
					width: "100%",
				}, loadingTime).delay(loadingTime).fadeOut();
				$($element).find(".search-element-loading").delay(loadingTime).fadeOut();
				$($element).find(".search-element-red").delay(loadingTime).velocity({
					width: $($element).find(".search-percent-red").text(),
				}, 1000);
				$($element).find(".search-element-green").delay(loadingTime).velocity({
					width: $($element).find(".search-percent-green").text(),
				}, 1000);
				$($element).find(".search-percent-red").delay(loadingTime).fadeIn();
				$($element).find(".search-percent-green").delay(loadingTime).fadeIn();
			};
			$(document).ready(function() {
				for (search of searches) {
					createSearch(search[0], search[1], search[2]);
				};
				$(".search-element-group").each(function() {
					animateSearch(this);
				});
			});
		</script>
		<script type="text/javascript">
		$('#main-search-box').keypress(function (e) {
				if (e.which == 13) {
					var boxValue = $("#main-search-box").val();
					$("#main-search-box").val("");
					submitText( boxValue );
					return false;
				};
		});
			
			function submitText ( value ){
				var submitToken = '<?php echo $token ?>';
				var dataString = '&search_item=' + value + '&submit_token=' + submitToken;
				
				$.ajax({
					type: "POST",
					url: "includes/processInput.php",
					data: dataString,
					success: function(entity) {
						console.log(entity);
						if ( entity.status == true ){
							var query = entity.query;
							var positive = entity.positive;
							var timeAgo = entity.timeAgo;

							var searchBlock = createSearch(query , timeAgo , positive );
							animateSearch(searchBlock);
						}else {
							//Ben decide
						}
					},
					error: function(xhr, status, error) {
						console.log(xhr.responseText);
					  //var err = JSON.parse(xhr.responseText);
					  //alert(err.Message);
					}
				});
				
			}
			
		</script>
		<!-- Graph create script -->
		<div class="" style="min-height: 100px;">

		</div>
	</body>
</html>