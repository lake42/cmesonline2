<!doctype html>
<html>
<head>
<title>The Bible - CMEsOnline.org</title>
<?php
require_once('meta.php');
?>

<link href="c/style2.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="c/slick.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="c/cme_base2.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="c/newnav.css" type="text/css" media="screen,projection" />
</head>

<body id="index_1">
	<div id="header_tall">
		<div id="main">
			<!--header -->
			<div id="header">
				<div class="h_logo">
					<div class="left">
						<img alt="" src="images/masthead_cmesonline.gif" />
					</div>
					<div class="clear"></div>
				</div>
				<br /><br/>
				<?php
				require_once('nav3.php');
				?>
			</div>
			<!--header end-->
			<div id="middle">
				<div class="indent">
					<div class="column1">
							<div class="eventHeader"><h2>The Holy Bible</h2></div>
								<?php
								require_once("bible.php");
								?>
					</div>
					<div class="column2a">
						<?php
						require_once('sidelinks.php');
						?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
                </div>
        </div>
			<!--footer -->
			<?php
			require_once('footer.php');
			?>
			<!--footer end-->
<script src="j/cme_base.js" type="text/javascript"></script>
<script type="text/javascript" src="j/jquery-1.3.2.js"></script>			
</body>
</html>






