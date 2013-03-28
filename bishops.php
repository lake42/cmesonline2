<?php

class BishopPage
{
var $html;
function BishopPage(){

	$this->html;
	}
	
function bpage($hookup,$id)
{
	$query 	= "SELECT * FROM cme_bishops WHERE rank_e='$id'";
	$rs 	= @mysql_query($query, $hookup);
	$nrows 	= @mysql_num_rows($rs);
	$row 	= @mysql_fetch_array($rs);

	$html="";
	$info="";
	$b="<br />";
	$seniors=array("48","42","41","39");

	$info.="<div class='top'><p><img src='i/" . $row['thumbnail'] . "' width='120' class='thumb' />";

	switch($row['b_id']){
		case 'd':$info.="Deceased" . $b; break;
		case 'r':$info.="Retired Bishop" . $b; break;
		case 'rs':$info.="Retired Senior Bishop" . $b; break;
		default:$info.="The " . $row['dstrct_title'] . " Episcopal District" . $b;
	} 	

	if (in_array($row['rank_e'],$seniors)){
		$info.="<h2>Senior Bishop " . $row['bishop_name'] . "</h2>";
	}else{
		$info.="<h2>Bishop " . $row['bishop_name'] . "</h2>";
	}
	
	$info.=$row['contact_info'];

	if ($row['website']){
		$info.="<br><a href='" . $row['website'] . "' target='_blank'>" . $row['website'] . "</a></p></div>";
	}
	else {
		$info.="</p></div>";
	}

$html.="<div id='bishWrap'>";
$html.=$info;

$html.="<div class='body'>" . $row['vitae'] . "</div></div>";

mysql_free_result($rs);
echo $html;

} // linklist

function b_json($hookup,$id)
{
 	$query 	= "SELECT * FROM cme_bishops WHERE rank_e='$id'";
	$rs 	= @mysql_query($query, $hookup);
	$nrows 	= @mysql_num_rows($rs);
	$row 	= @mysql_fetch_array($rs);
    //    }
 //       print $row['vitae'];
        print json_encode($row);
}

} // Links
