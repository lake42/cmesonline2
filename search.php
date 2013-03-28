<!doctype html>
<html>
<head>
<title>The Holy Bible</title>
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
			<div class="cobHeader"><h2>The Holy Scriptures</h2></div>
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
$versehtml.="<div class=\"biblecontent\">";
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
			<!--footer -->
			<?php			
			require_once('footer.php');
			?>
			<!--footer end-->
<script src="j/cme_base.js" type="text/javascript"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
</body>
</html>