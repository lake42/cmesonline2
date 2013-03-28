<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo "inside phph";
require_once ('config.php');
require_once ('db.php');
require_once ('LocatorPage.php');
?>
        <div id="clocator">
                 <div class="toolbar">
                    <h1>Church Locator</h1>
                    <a class="button swap" id="infoButton" href="#first">Main</a>
                </div>


<?php
$churches = new LocatorPage();
global $hookup;
$hookup = db_connect("cme");
$churches->ljson($hookup);
?>

       
</div>

