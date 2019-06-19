<html>
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
		<title>Search iTunes Store</title>
		<style type="text/css">
			
		#search_form
		{
			text-align: center;
		}
		
		#searchterm
		{
			width: 80%;
			height: 50px;
			font-size: 22px;
			border: 2px solid #ccc;
			-moz-border-radius: 10px;
			-webkit-border-radius: 10px;
			border-radius: 10px;
			-moz-box-shadow: 2px 2px 3px #666;
			-webkit-box-shadow: 2px 2px 3px #666;
			box-shadow: 2px 2px 3px #666;
			padding: 4px 7px;
			outline: 0;
			-webkit-appearance: none;
		}
		
		.search-button {
			margin-top: 20px;
		    width: 150px;
			height: 60px;
			background-color: #29c700; 
			moz-border-radius: 15px;
			-webkit-border-radius: 15px;
			border: 2px solid #009900;
			padding: 5px;
			font-size: 25px;
			font-weight: bolder;
			font-family: helvetica;
		}
		
		#results_counter
		{
			font-family: helvetica;
			font-size: 16px;
			font-weight: bold;
			margin-bottom: 40px;
		}
		
		#appcontainer
		{
			margin-bottom: 80px;
			font-family: helvetica;
		}
		
		#appimage
		{
			float: left;
			margin-right: 20px;
		}
		
		#viewinitunes
		{
			margin-top: 5px;
		}
		
		#apptitle
		{
			font-size: 24px;
			font-weight: bold;
			
		}
		
		#appdeveloper
		{
			font-size: 16px;
			color: #ccc;
			font-weight: bold;
		}
		
		#appdescription
		{
			margin-top: 20px;
		}
		
		#single_screenshot
		{
			display: inline-block;
			list-style: none;
			list-style-position: outside;
		}
		#single_screenshot li
		{
			margin-right: 10px;
		}
		#single_screenshot img
		{
			width: 200px;
		}
			
		</style>
	</head>
	<body>
		<?php
			$searchterm = "";
			if( array_key_exists("searchterm", $_POST) ){
				$searchterm = $_POST['searchterm'];
			}
		?>
		<div id="search_form">
			<form action="index.php" method="post">
				<input type="text" name="searchterm" id="searchterm" size="100" value="<?php echo $searchterm; ?>" /><br />
				<input type="submit" value="Search" class="search-button"/>
			</form>
		</div>
		<?php
			
			if ( $searchterm != "" ) {

				$searchterm = urlencode($searchterm);
				
				$ch = curl_init("https://itunes.apple.com/search?term=" . $searchterm . "&country=IT&media=software&");
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				
				$content =  curl_exec($ch);
				curl_close($ch);
				
				$contentArray = json_decode($content);
				
				echo "<div id='results_counter'>Found " . $contentArray->resultCount . " results</div>";
				
				foreach ( $contentArray->results as $appDetails ) {

					?>
					<div id="appcontainer">
						<div id='appimage'><img src="<?php echo $appDetails->artworkUrl60; ?>" border="0"/></div>
						<div id="apptitle"><?php echo $appDetails->trackCensoredName; ?></div> 
						<div id="appdeveloper"><?php echo $appDetails->artistName; ?></div>
						<div id="viewinitunes">
							<a href="<?php echo  $appDetails->trackViewUrl; ?>" target="_blank">
								<img src="images/viewinitunes_it.png" border="0" />
							</a>
						</div>
						<div id="appdescription"><?php echo $appDetails->description; ?></div>
						<div id="appscreenshots">
							<?php
								foreach ($appDetails->screenshotUrls as $screenshot) {
									?>
									<ul id="single_screenshot">
										<li>
											<a href="<?php echo $screenshot; ?>" target="_blank">
												<img src="<?php echo $screenshot; ?>" border="0" />
											</a>
										</li>
									</ul>
									<?php
								}
								
							?>
						</div>
						<div id="supported_devices">
							Supported devices:
							<ul>
								<?php
								foreach ($appDetails->supportedDevices as $device) {
									?>
									<li><?php echo $device; ?></li>
									<?php
								}	
								?>
							</ul>
						</div>
						<div id="release_notes">
							Release notes:<br>
							<?php echo $appDetails->releaseNotes; ?>
						</div>
					</div>
					<?php
					
				}	
				
			} 
		?>
	</body>
</html>