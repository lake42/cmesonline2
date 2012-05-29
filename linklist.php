<!doctype html>
<html>
<head>
<title>Links - CMEsOnline.org</title>
<?php
require_once('meta.php');
?>

<link href="c/style2.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="c/cme_base2.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="c/newnav.css" type="text/css" media="screen,projection" />

</head>

<body id="index_1">
	<div id="header_tall">
		<div id="main">
			<!--header start-->
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
						<div class="eventHeader"><h2>Links &amp; Resources</h2></div>
						<?php
						require_once("links.php");
						?>
					</div>
					<div class="column2a">
						<!-- sidelinks here -->
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

</body>
</html>
