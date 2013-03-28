<!doctype html>
<html>
<head>
<title>Events - CMEsOnline.org</title>
<?php
require_once('meta.php');
?>

<link href="c/style2.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="c/cme_base2.css" type="text/css" media="screen,projection" />
<link rel="stylesheet" href="c/newnav.css" type="text/css" media="screen,projection" />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.print.css' media='print' />
<style type="text/css">
	#loading {
		position: absolute;
		top: 5px;
		right: 5px;
		}

	#calendar {
		width: 730px;
		margin: 0 auto;
		}
	#cal {
		clear:both;
		margin-top: 0;
		margin-bottom:20px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
</style>


</head>

<body id="index_1">
	<div id="header_tall">
		<div id="main">
			<!--header -->
			<div id="header">
				<div class="h_logo">
					<div class="left">
						<img alt="" src="images/masthead_cmesonline.gif" />
					</div>
					<div class="clear"></div>
				</div>
				<br /><br/>
				<?php
				require_once('nav3.php');
				?>
			</div>
			<!--header end-->
			<div id="middle">
				<div class="indent">
					<div class="column1">
						<div id="cal">
							<div id='loading' style='display:none'>loading...</div>
							<div id='calendar'></div>
						</div>
							<div class="eventHeader"><h2>Connectional Events</h2></div>
								<?php
								require_once ('config.php');
								require_once ('db.php');
								require_once ('events_list.php');
								$list = new Event();
								global $hookup;
								$hookup = db_connect("cme");
								$list->eventlist($hookup);
								?>
							</div>
					<div class="column2a">
						<?php
				//		require_once('sidelinks.php');
						?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
                </div>
        </div>
			<!--footer -->
			<?php
			require_once('footer.php');
			?>
			<!--footer end-->
<script src="j/cme_base.js" type="text/javascript"></script>
<script type='text/javascript' src='jquery/jquery-1.5.2.min.js'></script>
<script type='text/javascript' src='jquery/jquery-ui-1.8.11.custom.min.js'></script>
<script type='text/javascript' src='fullcalendar/fullcalendar.min.js'></script>
<script type='text/javascript'>

	$(document).ready(function() {
	
		$('#calendar').fullCalendar({
		
			editable: false,
			
			events: "json-events.php",
			
			eventDrop: function(event, delta) {
				alert(event.title + ' was moved ' + delta + ' days\n' +
					'(should probably update your database)');
			},
			
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			}
			
		});
		
	});

</script>
			
</body>
</html>






