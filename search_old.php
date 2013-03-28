<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en' xml:lang='en'>
<head>
<title>{title}</title>
<link rel="stylesheet" href="jlpds_base.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="jQ/plugins/jplayer.blue.monday.css" title="style"  media="screen" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<body bgcolor="#efefef">

	<div id="header_tall">
		<div id="main">
			<!--header -->
			<div id="header">
				<div class="h_logo">
						<div class="left">
						<img alt="" src="images/plate7a.gif" />
					</div>
					<div class="right">
						<a href="http://www.cmesonline.org/cme.xml">RSS</a></div>
					<div class="clear"></div>
				</div>
				<br /><br/>
				<div id="menu">
					<div class="rightbg">
						<div class="leftbg">
							<div class="padding">
								<ul>
									<li><a href="index.html">Home</a></li>
									<li><a href="scriptures.php">Bible</a></li>
									<li><a href="events.php">Events</a></li>
									<li><a href="index-2.html">Forum</a></li>
									<li><a href="linklist.php">Links</a></li>
									<li class="last"><a href="index-4.html">Photos</a></li>
								</ul>
								<br class="clear" />
							</div>
						</div>
					</div>
				</div>
				<div class="content">
									

					<br />
					<br /><!--
					<div class="text2">
						Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Praesent vestibulum molestie lacus. Aenean nonummy hendrerit mauris.<br />
					</div>
					<a href="#"><img alt="" src="images/header_click_here.jpg" /></a><div class="clear"></div>-->
				</div>
			</div>
			<!--header end-->
			<div id="middle">
				<div class="indent">
					<div class="column1">
						<div class="padding1">
							<div class="bot_line">
								<div class="content">
									<strong class="b_text">The Holy Scriptures</strong><br />
									<p class="p1">

<?php

$bibledir="bible/";

$book = $_REQUEST['book'];
$chapter = $_REQUEST['chapter'];
$start_verse = $_REQUEST['startverse'];
$end_verse = $_REQUEST['endverse'];

/*
$book = "1philemon.txt";
$chapter = "1";
$start_verse = "2";
$end_verse = "7";
*/
$versehtml="";

$file = $bibledir . $book;
//echo $file;
//$fh = fopen($file, 'r');
$verses = file_get_contents($file);
//fclose($fh);
//echo $verses . "<br><br>";
$token = explode("\n", $verses);

$ln=sizeof($token);
//echo $ln . "<br><br>";
$found_start = 0;
$finished = 0;
$verse = $start_verse;
$booktitle=$token[0];
$matchstart = $chapter . ":" . $start_verse;
$matchend = $chapter . ":" . $end_verse;

// echo $booktitle . "<br><br>";
//echo $matchstart . "<br><br>";
$versehtml.="<div class=\"content\">";
$versehtml.= "<h2 class=\"byline2\">" . $booktitle . "</h2>";
$versehtml.="<p><b>Chapter " . $chapter . ":" . $start_verse . "-" . $end_verse . "</b>";

$match=$chapter . ":";

//foreach($token as $j){
$vstack=array();
for ($i=$start_verse;$i<=$end_verse; $i++){
array_push($vstack, $match . $i);
}

for ($j=0;$j<=$ln; $j++){
/*
	if ($verse > $end_verse) {
		break;
	}
*/

//$snatch=$match . $j;
//echo $snatch;

foreach ($vstack as $r){
	if (preg_match("/\b$r\b/i", $token[$j])) {
	//echo $token[$j] . "<br><br>";
	$versehtml.="<p>" . $token[$j] ;
	}


}



//echo $token[0] . "<br><br>";

}

$versehtml.="<p><br /><a href='scriptures.php'>Back to Bible Verse Search</a>";
$versehtml.="</div>";

echo $versehtml;
?>

</div>
							</div>
						</div>
						<div class="padding2">
							<!--<img alt="" src="images/2-t2.gif" />-->
							<div class="content">
								
						<!--
								<div class="cols">
									<div class="col1">
										<strong class="b_text">Integrations.</strong><br />
										Montes, nascetur ridiculus muulla dui. Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus us lorem.<br />
										<div class="more"><a href="#">learn more</a> &raquo;</div>
										<p class="p1">
											<strong class="b_text">Publishing.</strong>										</p>
										Montes, nascetur ridiculus muulla dui. Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus necem.<br />
										<div class="more"><a href="#">learn more</a></div>
									</div>
									<div class="ind_col">&nbsp;</div>
									<div class="col2">
										<strong class="b_text">Documentation.</strong><br />
										Montes, nascetur ridiculus muulla dui. Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus nec, luctus.<br />
										<div class="more"><a href="#">learn more</a></div>
										<p class="p1">
											<strong class="b_text">Our Portal.</strong>										</p>
										Montes, nascetur ridiculus muulla dui. Fusce feugiat malesuada odio. Morbi nunc odio, gravida at, cursus nec, orem.<br />
										<div class="more"><a href="#">learn more</a></div>
									</div>
									<div class="clear"></div>
								</div>-->
							</div>
						</div>
					</div>
					<div class="column2">
						<div class="border">
							<div class="btall">
								<div class="ltall">
									<div class="rtall">
										<div class="tleft">
											<div class="tright">
												<div class="bleft">
													<div class="bright">
														<div class="ind">
															<div class="h_text">
																<img alt="" src="images/2-t3a.jpg" /><br />
															</div>
															<div class="padding">
																<img alt="" src="images/2-p1a.gif" />
																<!--<img alt="" src="images/lakey_n.jpg" />-->
																<br /><br />
<?php
require_once('sidelinks.php');
?>
																
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<!--footer -->
			<div id="footer">
				<div class="indent">
					&copy; 2009 - 20010 CMEsOnline.org &bull; <br /><a href="index-6.html">Privacy Policy</a></div>
			</div>
			<!--footer end-->
		</div>
	</div>
</body>
</html>


