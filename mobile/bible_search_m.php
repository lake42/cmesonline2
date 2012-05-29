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
$versehtml.= "<h3 class=\"byline2\">" . $booktitle . "</h3>";
$versehtml.="<p><b>Chapter " . $chapter . ": Verse(s) " . $start_verse . "-" . $xend . "</b>";
$versehtml.=$section;
$versehtml.="<p><br />";
$versehtml.="</div>";
echo $versehtml;
//echo $file;

?>

