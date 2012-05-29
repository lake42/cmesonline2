<!doctype html>
<html>
<head>
<title>Photos - CMEsOnline.org</title>
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
						<!-- main column content here -->
					</div>
				</div>
			</div>
			<!--footer -->
			<?php			
			require_once('footer.php');
			?>
			<!--footer end-->
		</div>
	</div>
			<?php			
			require_once('gallerific_js_inc.php');
			?>
</body>
</html>
