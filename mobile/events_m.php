<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('config.php');
require_once ('db.php');
require_once ('events_list.php');
        $list = new Event();
        global $hookup;
        $hookup = db_connect("cme");
        $list->eventlist($hookup);




