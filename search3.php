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

function GetBetween($content,$start,$end){
    $r = explode($start, $content);
    if (isset($r[1])){
        $r = explode($end, $r[1]);
        return $start . " " . $r[0];
    }
    return '';
}

$bibledir="bible/";

$book = $_REQUEST['book'];
$chapter = $_REQUEST['chapter'];
$start_verse = $_REQUEST['startverse'];
$end_verse = (int)$_REQUEST['endverse']+1;
$xend = (int)$_REQUEST['endverse'];
$versehtml="";

$file = $bibledir . $book;
$verses = file_get_contents($file);
$matchstart = $chapter . ":" . $start_verse;
$matchend = $chapter . ":" . $end_verse;
$section = GetBetween($verses, $matchstart, $matchend);
$pattern = '/(\d+):(\d+)/i';
$replacement = '<p><strong>$1:$2</strong>';
$section = preg_replace($pattern,$replacement,$section);
$token = explode("\n", $verses);

$ln=sizeof($token);
$found_start = 0;
$finished = 0;
$verse = $start_verse;
$booktitle=$token[0];

$versehtml.="<div class=\"biblecontent\">";
$versehtml.= "<h2 class=\"byline2\">" . $booktitle . "</h2>";
$versehtml.="<p><b>Chapter " . $chapter . ": Verse(s) " . $start_verse . "-" . $xend . "</b>";
$versehtml.=$section;
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