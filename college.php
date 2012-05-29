<!doctype html>
<html>
<head>
<title>College of Bishops - CMEsOnline.org</title>
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
			<div class="cobHeader"><h2>The College of Bishops of the Christian Methodist Episcopal Church</h2></div>
			<?php
			require_once('cob.php');
			?>
		</div>
	</div>
			<!--footer -->
			<?php			
			require_once('footer.php');
			?>
			<!--footer end-->
<script src="j/cme_base.js" type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
</body>
</html>