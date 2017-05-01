<?php
	session_start();
	$searchItems = array();

	$token = md5(uniqid(rand(), TRUE));
	$_SESSION['token'] = $token;
	$_SESSION['token_time'] = time();

	$i = 1;

	while ( isset( $_GET['p' . $i ] )  ){
		$ignore = false;
		$searchItem = filter_input(INPUT_GET, 'p' . $i );
		$searchItem = str_replace ( "_", " ", $searchItem );
		$searchItem = strip_tags( trim( $searchItem ) );
		$searchItem = strtolower( $searchItem );
		if ( !$searchItem ) $ignore = true;
		if ( strlen( $searchItem ) < 3 || strlen( $searchItem ) > 30 ) $ignore = true;
		if ( !coolCheck ( $searchItem ) ) $ignore = true;
		
		if ( !$ignore ){
			array_push( $searchItems, $searchItem );
		}
		$i++;
	}

	$defaultSearches = "";
	
	$ip = getUserIP();
	if (strpos($ip, ',') !== false) {
		$ips = explode ( ',', $ip );
		$ip = $ips[0];
	}

	if ( strcmp ( $ip, 'null' ) != 0 ){
		$country = file_get_contents("http://ipinfo.io/$ip/country");
		$country = trim ( $country );
		
		if ( strcmp ( $country, 'GB' ) == 0 ){
			$defaultSearches = "var defaultSearches = ['Jeremy Corbyn', 'Theresa May'];";
		}else {
			$defaultSearches = "var defaultSearches = ['Twitter', 'Facebook'];";
		}
	}else {
		$defaultSearches = "var defaultSearches = ['Twitter', 'Facebook'];";
	}
	
	function coolCheck($string) {
		return preg_match("/^[a-zA-Z0-9\s]*$/", $string);
	}
	
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

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
		<meta name="description" content="TweetPoll gathers the public's opinion on a particular topic using tweets."/>
		<meta name="author" content="TweetPoll">
		<link rel="canonical" href="http://www.tweetpoll.co/" />

		<meta property="og:title" content="TweetPoll"/>
		<meta property="og:type" content="website"/>
		<meta property="og:url" content="http://www.tweetpoll.co/"/>
		<meta property="og:image" content="http://www.tweetpoll.co/images/apple-touch-icon-152x152.png"/>
		<meta property="og:description" content="TweetPoll gathers the public's opinion on a particular topic using tweets."/>

		<meta name="twitter:card" content="summary">
		<meta name="twitter:site" content="@_TweetPoll"/>
		<meta name="twitter:url" content="http://www.tweetpoll.co/"/>
		<meta name="twitter:title" content="TweetPoll"/>
		<meta name="twitter:image" content="http://www.tweetpoll.co/images/apple-touch-icon-152x152.png"/>
		<meta name="twitter:description" content="TweetPoll gathers the public's opinion on a particular topic using tweets."/>

		<title>TweetPoll</title>

		<link href="/css/flat-ui.css" rel="stylesheet" type="text/css">
		<link href="/css/index.css" rel="stylesheet" type="text/css">
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
		<!--[if lt IE 9]>
		<script src="/js/vendor/html5shiv.js"></script>
		<script src="/js/vendor/respond.min.js"></script>
		<![endif]-->

		<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/images/apple-touch-icon-57x57.png" />
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/images/apple-touch-icon-114x114.png" />
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/images/apple-touch-icon-72x72.png" />
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/images/apple-touch-icon-144x144.png" />
		<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/images/apple-touch-icon-60x60.png" />
		<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/images/apple-touch-icon-120x120.png" />
		<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/images/apple-touch-icon-76x76.png" />
		<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/apple-touch-icon-152x152.png" />
		<link rel="icon" type="image/png" href="/images/favicon-196x196.png" sizes="196x196" />
		<link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96" />
		<link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16" />
		<link rel="icon" type="image/png" href="/images/favicon-128.png" sizes="128x128" />
		<meta name="application-name" content="TweetPoll"/>
		<meta name="msapplication-TileColor" content="#FFFFFF" />
		<meta name="msapplication-TileImage" content="mstile-144x144.png" />
		<meta name="msapplication-square70x70logo" content="mstile-70x70.png" />
		<meta name="msapplication-square150x150logo" content="mstile-150x150.png" />
		<meta name="msapplication-wide310x150logo" content="mstile-310x150.png" />
		<meta name="msapplication-square310x310logo" content="mstile-310x310.png" />

    	<link rel="stylesheet" href="https://code.cdn.mozilla.net/fonts/fira.css">
		
		<!-- <script>
		  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		  ga('create', 'UA-98198042-1', 'auto');
		  ga('send', 'pageview');

		</script> -->

	</head>
	<body>
		<div id="body-wrapper">
			<div id="bg-img"></div>
			<div id="bg-overlay"></div>
			<div id="main-toast">Sorry we don't harvest strings fewer than 4 characters!</div>
			<div id="main-credits-button">
				<h3 id="main-button-text">About</h3>
			</div>
			<div id="main-title"><img src="/images/header_logo.png" width="100%" ></div>
			<input type="text" name="" id="main-search-box"
				maxlength="24" placeholder="Query the opinion harvester...">
			<div id="searches-container">
				<div class="search-element-group-template display-none">
					<h2 class="search-element-search"></h2>
					
					<h4 class="search-element-updated"></h4>
					<div class="search-element-bars">
						<div class="search-element-loading">
							<div class="search-element-loading-overlay"></div>
						</div>
						<div class="search-element-percentage">
							<div class="search-element-green"></div>
							<div class="search-element-red"></div>
						</div>
					</div>
					<div class="search-element-cross">
						<div class="search-element-cross-1"></div>
						<div class="search-element-cross-2"></div>
					</div>
					<h3 class="search-percent-green"></h3>
					<h3 class="search-percent-green"></h3>
					<h3 class="search-percent-red"></h3>
					<h4 class="search-element-change"></h4>
					<h3 class="search-element-error">The query got stuck in the straw chopper.. please try again...</h3>
				</div>

			</div>
		</div>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.5.0/velocity.min.js"></script>
		
		<script type="text/javascript">
			var loadingMessages = [
				"Locating renderable gigapixels",
				"Spinning up the hamster",
				"Shovelling coal to the server",
				"Programming the flux capacitor",
				"We dream about faster computers",
				"Would you like fries with that?",
				"Calculating gravitational constant",
				"Forming the black hole",
				"At least you’re not on hold",
				"This server is powered by oranges",
				"We’re testing your patience",
				"Waiting for the satellite",
				"Launching into near Earth orbit",
				"This is faster than you could do it",
				"Warming up Large Hadron Collider",
				"Waiting for the abacus",
				"The elves are having lunch",
				"Time is an illusion",
				"Hang on, it’s here somewhere",
				"Measuring cable length",
				"Re-calibrating the internet",
				"Reconfiguring coffee machine",
				"Paging the administrator",
				"Counting backwards from infinity",
				"Notifying field agents",
				"Negotiating WiFi password",
				"Scanning for credit cards",
				"Adjusting for your IQ",
				"Dividing by zero",
				"Adding random fluctuations",
				"Recalculating Pi",
				"Creating universe",
				"Caching internet locally",
				"Time-loop inversion failed",
				"Commencing infinite loop",
				"Spinning wheel of fortune",
				"Starting missile launch",
				"Protecting the innocent",
				"Rewinding DVDs",
				"The internet is full.. please wait",
				"Loading loading message",
				"Computing chance of success",
				"Randomising memory access",
				"I think, therefore I am",
				"Preparing for hyperspace jump",
				"Adjusting bell curves",
				"Adding hidden agendas",
				"Aligning covariance matrices",
				"Building data structures",
				"Calibrating blue skies",
				"Collecting meteor particles",
				"Decomposing singular values",
				"Destabilising economic indicators",
				"Downloading terrain data",
				"Generating jobs",
				"Flushing pipe network",
				"Integrating curves",
				"Normalising power",
				"Mixing genetic pool",
				"Perturbing matrices",
				"Synthesising wavelets",
				"Time-compressing simulator clock"
			];
			var currentSearches = [];
			var submitToken = '<?php echo $token ?>';

			String.prototype.replaceAll = function(search, replacement) {
				var target = this;
				return target.replace(new RegExp(search, 'g'), replacement);
			};

			function getRandomInt(min, max) {
				min = Math.ceil(min);
				max = Math.floor(max);
				return Math.floor(Math.random() * (max - min)) + min;
			};

			function createSearch(name) {
				$('html, body').animate({
					scrollTop: $("#main-title").offset().top
				}, 1000);
				var loadingMessage = loadingMessages[Math.floor(Math.random() * loadingMessages.length)];
				var $searchBlock = $(".search-element-group-template").clone();
				$searchBlock.removeClass("search-element-group-template");
				$searchBlock.addClass("search-element-group");
				$searchBlock.prependTo("#searches-container");
				$searchBlock.find(".search-element-search").html(name);
				$searchBlock.find(".search-element-updated").html(loadingMessage.concat("..."));
				$searchBlock.find(".search-element-cross").click(function() {
					removeSearch($searchBlock);
				});
				$searchBlock.removeClass("display-none");
				animateLoad($searchBlock);
				return $searchBlock;
			};

			function animateLoad($element) {
				var setupTime = 400;
				var loadingTime = getRandomInt(5000, 9000);
				$($element).find(".search-element-search").velocity({
					opacity: "1",
				}, setupTime);
				$($element).find(".search-element-loading").velocity({
					width: "100%",
				}, setupTime);
				$($element).find(".search-element-loading-overlay").delay(setupTime).velocity({
					width: "80%",
				}, {
					duration: loadingTime,
				});
			};

			function animateFinish($element, greenPercent, updateTime, change) {
				currentSearches.push($element.find(".search-element-search").html());
				updateURL();
				$($element).find(".search-percent-green").html(greenPercent.toString().concat("%"));
				$($element).find(".search-percent-red").html((100 - greenPercent).toString().concat("%"));
				$($element).find(".search-element-loading-overlay").velocity({
					width: "100%",
				}, {
					duration: 400,
				}).delay(400).fadeOut();
				$($element).find(".search-element-loading").delay(400).fadeOut();
				window.setTimeout(function() {
					$($element).find(".search-element-updated").html(updateTime);
				}, 400);
				if (change != null) {
					//$($element).find(".search-element-change").html(change);
				}

				$($element).find(".search-element-red").delay(400).velocity({
					width: $($element).find(".search-percent-red").text(),
				}, 1000);
				$($element).find(".search-element-green").delay(400).velocity({
					width: $($element).find(".search-percent-green").text(),
				}, 1000);
				$($element).find(".search-percent-red").delay(400).fadeIn();
				$($element).find(".search-percent-green").delay(400).fadeIn();
				//$($element).find(".search-element-change").delay(400).fadeIn();
			};

			function searchError($element) {
				$($element).find(".search-element-search").fadeOut();
				$($element).find(".search-element-updated").fadeOut();
				$($element).find(".search-element-error").delay(500).velocity({
					opacity: "1",
				}, 500);
				window.setTimeout(function() {
					$($element).fadeOut();
				}, 3000);
				window.setTimeout(function() {
					$($element).remove();
				}, 4000);
			};

			function updateURL() {
				var stringURL = "/";
				for (search of currentSearches) {
					var searchL = search.toLowerCase();
					searchL = searchL.replaceAll(' ', '_');
					stringURL = stringURL + searchL + "/";
				};
				stringURL = stringURL.substring(0, stringURL.length - 1);
				history.pushState(null, null, stringURL);
			}

			function removeSearch($element) {
				var index = currentSearches.indexOf($element.find(".search-element-search").html());
				if (index > -1) {
					currentSearches.splice(index, 1);
				}
				updateURL();
				$($element).velocity({
					opacity: "0",
				}, 500);
				$($element).velocity({
					height: "0px",
				}, 500);
				window.setTimeout(function() {
					$($element).remove();
				}, 1000);
			};

			function submitText(value) {
				var dataString = '&search_item=' + value + '&submit_token=' + submitToken;
				var $searchBlock = createSearch(value);
				$.ajax({
					type: "POST",
					url: "/includes/processInput.php",
					data: dataString,
					success: function(entity) {
						console.log(entity);
						if (entity.status == true) {
							var query = entity.query;
							var positive = entity.positive;
							var timeAgo = entity.timeAgo;
							var change = entity.change;
							animateFinish($searchBlock, positive, timeAgo, change);

						} else {
							searchError($searchBlock);
						}
					},
					error: function(xhr, status, error) {
						console.log(xhr.responseText);
					}
				});
			};
		</script>
		<script type="text/javascript">
		$('#main-search-box').keypress(function (e) {
				if (e.which == 13) {
					var boxValue = $("#main-search-box").val();
					if (boxValue.length > 3) {
						$("#main-search-box").val("");
						submitText( boxValue );
						return false;
					} else {
						$("#main-toast").finish().animate({
							opacity: "1",
						}, 500);
						$("#main-toast").delay(5000).animate({
							opacity: "0",
						}, 500);
					};
				};
		});
		$("#main-credits-button").click(function() {
			window.location.href = "/credits.html";
		});
		$(window).on("popstate", function(e) {
			location.reload();
		});
		</script>
		<script type="text/javascript">
			<?php
				if ( sizeof ( $searchItems) > 0 ){
					echo 'var defaultSearches = [';
					for ( $i = 0; $i < sizeof ( $searchItems); $i++ ){
						if ( $i == ( sizeof( $searchItems ) - 1 ) ){
							echo ' \'' . $searchItems[$i] . '\'';
						}else {
							echo ' \'' . $searchItems[$i] . '\', ';
						}
					}
					echo '];';
				}else {
					echo $defaultSearches;
				}
			?>
			$(document).ready(function() {
				for (search of defaultSearches) {
					submitText(search);
				};
			});
		</script>
	</body>
</html>