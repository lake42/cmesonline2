<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//echo "inside phph";
require_once ('config.php');
require_once ('db.php');
require_once ('bishops.php');

$bishopp = new BishopPage();
global $hookup;
$hookup = db_connect("cme");
$bishopp->b_json($hookup,$_REQUEST['i']);

