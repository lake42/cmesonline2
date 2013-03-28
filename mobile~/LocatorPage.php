<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LocatorPage
 *
 * @author james
 */
class LocatorPage {
    //put your code here
    
var $html;
function LocationPage(){

	$this->html;
	}
        
        
        function lpage($hookup)
{
	$query 	= "SELECT church,address,city,state,zipcode FROM churches WHERE gmap='y'";
	$rs 	= @mysql_query($query, $hookup);
	$nrows 	= @mysql_num_rows($rs);
//	$row 	= @mysql_fetch_array($rs);
        
        $html="";
	$b="<br />";
        $s = " ";
        $c = ", ";
        $html.='<ul class="rounded">';
    //    $html.='<li class="arrow"><a href="#map" class="testevent" vlu="500 Wofford Street, Spartanburg SC">Bunton Institutional C.M.E. Church</a></li>';
        for ($i=0; $i<$nrows; $i++){
            $row = mysql_fetch_array($rs);
            $html.="<li class=\"arrow\"><a href=\"#map\" class=\"testevent\" rel=\"" . $row['address'] . $c . $row['city'] . $s . $row['state'] . "\">" . $row['church'] . "</a></li>";
        }   
   //     $html.="<a href=\"map\" class=\"testevent\">" . row['address']. "</a>";
        $html.="</ul>";
        mysql_free_result($rs);
echo $html;
}

function ljson($hookup){
    	$query 	= "SELECT church,address,city,state,zipcode,e_dist,phone FROM churches WHERE gmap='y'";
	$rs 	= @mysql_query($query, $hookup);
        $data = array();
        
        while($row = mysql_fetch_row($rs) )
        {
         $data[] = $row;    
            
        }
        mysql_free_result($rs);
        echo json_encode( $data );
    
}


}