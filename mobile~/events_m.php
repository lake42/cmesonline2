<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once ('config.php');
require_once ('db.php');
require_once ('events_list.php');
?>
<div id="events">
                 <div class="toolbar">
                    <h1>Events</h1>
                    <a class="button flipleft" id="infoButton" href="#first">Main</a>
                 </div>
<ul class="rounded">
    <li>
<?php
        $list = new Event();
        global $hookup;
        $hookup = db_connect("cme");
        $list->eventlist($hookup);
?>
    </li>
</ul>
</div>


