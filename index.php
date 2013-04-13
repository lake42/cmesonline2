<!DOCTYPE html>
<html>
<head>
<title>Home - CMEsOnline.org</title>

<script src="j/m_redirect.js" type="text/javascript"></script>

<script type="text/javascript">
	M_redirect("http://m.cmesonline.org/","http://cmesonline.org/");
</script>

<?php
require_once('meta.php');
?>
<link rel="stylesheet" href="css/main.css" type="text/css" media="screen,projection" />


<!--
<link href="c/style2.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="c/cme_base2.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="c/newnav.css" type="text/css" media="screen,projection" />
-->
</head>

<body id="index_1">
<div id="page_effect" style="display:none;">
	<div>
		<div id="main">
			<!--header -->
			<div id="header">
				<div class="h_logo">
					<div class="left">
						<img alt="CMEsOnline.org" src="images/masthead_cmesonline.gif" />
					</div>
					<div class="clear"></div>
				</div>
				<br /><br/>
<?php
require_once('nav3.php');
?>
				<div class="content">
					<br />
					<br />
				</div>
			</div>
			<!--header end-->
			<div id="middle">
				<div class="indent">
					<div class="column1">
						<div class="body">
							<h2>The <b>Christian Methodist Episcopal Church</b></h2>
							<p>
							was described at its nascence in 1870 
							by <b>Bishop Randall Albert Carter</b>, one of its first bishops, as:</p>

							<div class="em">
							<span id="frontEm" style="display:none;">
							<em>"... this tender plant of God ... here to live or die."</em></span><span id="plant"  style="display:none;"><img src="images/tenderplant.png" width="40" id="plant" /></span></div>
							<p>This website was first developed in 1996 as a way of exemplifying the Christian Methodist Episcopal Church's 
							mission and digital relevance in the era of the World Wide Web. The site has evolved into a convenient 
							jump point for resources and connectional news. Please review the CME Forum's 
							<a href="javascript:loadwindow('cmeonline_privacy.html');" title="CME Forum Posting Guidelines">post and 
							registration policy</a>...
							</p>
						</div>
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
			<!--footer -->
<?php			
require_once('footer.php');
?>
			<!--footer end-->
		</div>
	</div>
<script src="j/cme_base.js" type="text/javascript"></script>
<script type="text/javascript" src="j/jquery-1.3.2.js"></script>
<script type="text/javascript"> 
$(document).ready(function(){	
	$('#page_effect').fadeIn(1200); 
	$('#frontEm').fadeIn(3000);
	$('#plant').fadeIn(7500);  
});
</script>
<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://69.56.173.240/piwik/" : "http://69.56.173.240/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 2);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://69.56.173.240/piwik/piwik.php?idsite=2" style="border:0" alt=""/></p></noscript>
<!-- End Piwik Tag -->
</body>
</html>
