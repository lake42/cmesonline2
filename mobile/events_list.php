<?php

class Event
{
var $html;
function Event(){

	$this->html;
	}
	
function eventlist($hookup)
{

$query = "SELECT * FROM events WHERE display='y' order by id DESC";
$rs = mysql_query($query, $hookup);
$nrows	= mysql_num_rows($rs);
$html="";
$b = "<br>";
$p = "<p>";
for ($i=0; $i<$nrows; $i++){
$row = mysql_fetch_array($rs);
if (($row['title']!='')){
	$html.="<li><div class='eventItem'>";
	$html.="<h3>" . $row['title'] . "</h3><p>";
	if ($row['time_date']!='') {$html.=$row['time_date'] . $b;}
	if ($row['location']!='') {$html.=$row['location'] . $b;}
	if ($row['description']!='') {$html.=$row['description'] . $b;}
	if ($row['info']!='') {$html.=$row['info'] . $b;}
	$html.="</p></div></li>";
}
}
mysql_free_result($rs);
echo $html;
}

}
?>