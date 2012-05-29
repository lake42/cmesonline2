<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo "inside phph";
require_once ('config.php');
require_once ('db.php');
require_once ('bishops.php');
?>
<div data-role="page">                 
<div data-role="header"  data-iconpos="left">
                    <h1>The Bishops</h1>
                    <a class="button back" href="#bishops">Bishops</a>
                 </div>


<ul class="rounded">
    <li>
<?php
$bishopp = new BishopPage();
global $hookup;
$hookup = db_connect("cme");
$bishopp->bpage($hookup,$_REQUEST['i']);
?>
    </li>
</ul>
</div>

