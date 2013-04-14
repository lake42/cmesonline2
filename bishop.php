<?php
require_once ('config.php');
require_once ('db.php');
require_once ('bishops.php');
?>
<!doctype html>
<html>
<head>
<title>College of Bishops - CMEsOnline.org</title>
<?php
require_once('meta.php');
?>

<link rel="stylesheet" href="css/main.css" type="text/css" media="screen,projection" />

</head>

<body id="index_1" class="bishops">
	<div id="header_tall">
		<div id="main">
			<!--header start-->
			<div id="header">
				<div class="h_logo">
					<div class="left">
						<a href="http://www.cmesonline.org"><img alt="CMEsOnline.org" src="images/masthead_cmesonline.gif" /></a>
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
						<?php
						$bishopp = new BishopPage();
						global $hookup;
						$hookup = db_connect("cme");
						$bishopp->bpage($hookup,$_REQUEST['i']);
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
