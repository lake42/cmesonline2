<!DOCTYPE html>
<html>
<head>
<title>CMEsOnline.org Photo Gallery</title>
<?php
require_once('meta.php');
?>
<script src="http://cdn.jquerytools.org/1.1.2/full/jquery.tools.min.js"></script>

<link rel="stylesheet" type="text/css" href="http://static.flowplayer.org/tools/css/standalone.css"/>

<link rel="stylesheet" href="css/main.css" type="text/css" media="screen,projection" />

<style type="text/css">
body {padding-top: 10px}

.thmb{
	width:75px;
}


/* the thumbnails */
#triggers {
	text-align:center;

        width:550px;
}

#triggers img {
    margin:7px 2px;
    /*
	background-color:#fff;
	padding:2px;
	border:1px solid #ccc;
	
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
    */
}

/* the active thumbnail */
#triggers a.active img {
    /*
	outline:1px solid #000;
*/
	/* show on top of the expose mask */
	z-index:9999;
	position:relative;
}

</style>
</head>

<body id="index_1">
<div id="page_effect">
	<div>
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
                                <!--
				<div class="content">
					<br />
					<br />
				</div>
                                -->
			</div>
			<!--header end-->
			<div id="middle">
				<div class="indent">
					<div class="column1">

                                        <!-- list of thumbnails --> 
                                        <div id="triggers">
                                            <a href="images/port.jpg" title="The CME Online Photo Gallery"> 
                                            <img src="images/port.jpg" class="thumb3" /></a>

                                            <br/>

                                            <a href="i/collegeofbishops_2011.jpg" title="The CME College of Bishops 2011"> 
                                            <img src="i/collegeofbishops_2011.jpg" class="thumb3" /></a>

                                            <a href="images/collegeofbishops2006.jpg" title="The CME College of Bishops"> 
                                            <img src="images/collegeofbishops2006.jpg" class="thumb3" /></a>

                                            <a href="i/generalofficers_2011.jpg" title="CME / 3"> 
                                            <img src="i/generalofficers_2011.jpg" class="thumb3" /></a>

                                            <a href="images/generalofficers_2007.jpg" title="The CME General Officers"> 
                                            <img src="images/generalofficers_2007.jpg" class="thumb3" /></a>

                                            <a href="images/Bishops_and_Plaque2.jpg" title="CME / 1"> 
                                            <img src="images/Bishops_and_Plaque2.jpg" class="thumb3" /></a>

                                            <br />

                                            <a href="images/gconf/gconf_018.jpg" title="CME General Conference / 1"> 
                                            <img src="images/gconf/gconf_018.jpg" class="thumb3" /></a>

                                            <a href="images/gconf/gconf_017.jpg" title="CME General Conference / 2"> 
                                            <img src="images/gconf/gconf_017.jpg" class="thumb3" /></a>

                                            <a href="images/gconf/gconf_015.jpg" title="CME General Conference / 3"> 
                                            <img src="images/gconf/gconf_015.jpg" class="thumb3" /></a>

                                            <a href="i/Congressman Lewis with COB.jpg" title="Image description #1" > 
                                            <img src="i/Congressman Lewis with COB.jpg"  class="thumb3" /></a>

                                            <a href="images/cit3.jpg" title="Image description #1" > 
                                            <img src="images/cit3.jpg"  class="thumb3" /></a>

                                            <br />

                                            <a href="i/CME_Convocation_2011_001.jpg" title="CME General Conference / 1">
                                            <img src="i/CME_Convocation_2011_001.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_002.jpg" title="CME General Conference / 2">
                                            <img src="i/CME_Convocation_2011_002.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_003.jpg" title="CME General Conference / 3">
                                            <img src="i/CME_Convocation_2011_003.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_006.jpg" title="Image description #1" >
                                            <img src="i/CME_Convocation_2011_006.jpg"  class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_007.jpg" title="Image description #1" >
                                            <img src="i/CME_Convocation_2011_007.jpg"  class="thumb3" /></a>

                                            <br />

                                            <a href="i/CME_Convocation_2011_004.jpg" title="CME General Conference / 1">
                                            <img src="i/CME_Convocation_2011_004.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_005.jpg" title="CME General Conference / 2">
                                            <img src="i/CME_Convocation_2011_005.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_010.jpg" title="CME General Conference / 3">
                                            <img src="i/CME_Convocation_2011_010.jpg" class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_009.jpg" title="Image description #1" >
                                            <img src="i/CME_Convocation_2011_009.jpg"  class="thumb3" /></a>

                                            <a href="i/CME_Convocation_2011_008.jpg" title="Image description #1" >
                                            <img src="i/CME_Convocation_2011_008.jpg"  class="thumb3" /></a>


                                            <br />

                                            <a href="images/ohl/ohl2.jpg" title="Othal H. Lakey Circle / 2"> 
                                            <img src="images/ohl/ohl2.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl3.jpg" title="Othal H. Lakey Circle / 3"> 
                                            <img src="images/ohl/ohl3.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl4.jpg" title="Othal H. Lakey Circle / 4"> 
                                            <img src="images/ohl/ohl4.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl5.jpg" title="Othal H. Lakey Circle / 5"> 
                                            <img src="images/ohl/ohl5.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl6.jpg" title="Othal H. Lakey Circle / 6"> 
                                            <img src="images/ohl/ohl6.jpg" class="thumb3" /></a>

                                            <br />

                                            <a href="images/ohl/ohl9.jpg" title="Othal H. Lakey Circle / 9"> 
                                            <img src="images/ohl/ohl9.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl7.jpg" title="Othal H. Lakey Circle / 7"> 
                                            <img src="images/ohl/ohl7.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl8.jpg" title="Othal H. Lakey Circle / 8"> 
                                            <img src="images/ohl/ohl8.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl10.jpg" title="Othal H. Lakey Circle / 10"> 
                                            <img src="images/ohl/ohl10.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl11.jpg" title="Othal H. Lakey Circle / 11"> 
                                            <img src="images/ohl/ohl11.jpg" class="thumb3" /></a>

                                            <br />

                                            <a href="images/ohl/ohl1.jpg" title="Othal H. Lakey Circle / 1"> 
                                            <img src="images/ohl/ohl1.jpg" class="thumb3" /></a>

                                            <a href="images/ohl/ohl12.jpg" title="Othal H. Lakey Circle / 12"> 
                                            <img src="images/ohl/ohl12.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl13.jpg" title="Othal H. Lakey Circle / 13"> 
                                            <img src="images/ohl/ohl13.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl14.jpg" title="Othal H. Lakey Circle / 14"> 
                                            <img src="images/ohl/ohl14.jpg"  class="thumb3" /></a>

                                            <a href="images/ohl/ohl15.jpg" title="Othal H. Lakey Circle / 15"> 
                                            <img src="images/ohl/ohl15.jpg"  class="thumb3" /></a>

                                            <br />

                                            <a href="images/collegeofbishopsAIDStest.jpg" title="CME / 2"> 
                                            <img src="images/collegeofbishopsAIDStest.jpg" class="thumb3" /></a>

                                            <a href="images/015_15_0001.jpg" title="CME / 3"> 
                                            <img src="images/015_15_0001.jpg" class="thumb3" /></a>

                                            <a href="images/cit1.jpg" title="Othal H. Lakey Circle / 16"> 
                                            <img src="images/cit1.jpg"  class="thumb3" /></a>
                                            
                                            <a href="images/cit2.jpg" title="Image description #1" > 
                                            <img src="images/cit2.jpg"  class="thumb3" /></a>

                                        </div><!--end of triggers -->

                                        </div><!-- end of column 1 -->

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

<link rel="stylesheet" type="text/css" href="http://static.flowplayer.org/tools/css/overlay-basic.css"/>

<!-- gallery specific styling -->
<link rel="stylesheet" type="text/css" href="http://static.flowplayer.org/tools/css/overlay-gallery.css"/>


<div class="simple_overlay" id="gallery">

	<!-- "previous image" action -->

	<a class="prev">prev</a>

	<!-- "next image" action -->
	<a class="next">next</a>

	<!-- image information -->
	<div class="info"></div>

	<!-- load indicator (animated gif) -->

	<img class="progress" src="http://static.flowplayer.org/tools/img/overlay/loading.gif" />
</div>
<script src="j/cme_base.js" type="text/javascript"></script>

<script>
$(function() {

// select the thumbnails and make them trigger our overlay
$("#triggers a").overlay({

	// each trigger uses the same overlay with the id "gallery"
	target: '#gallery',

	// optional exposing effect
	expose: '#f1f1f1'

// let the gallery plugin do its magic!
}).gallery({

	// the plugin accepts its own set of configuration options
	speed: 800
});
});
</script>




</body>
</html>