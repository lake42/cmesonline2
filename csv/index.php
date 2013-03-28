<?php require_once('/var/www/html/cmesonline/csv/classes/csv.class.php');
	  require_once('/var/www/html/cmesonline/csv/lib/config.php');
/**
*	Generate the file & a dynamic filename.
* 
* 	This file utilizes a csv container / wrapper class to implement public interfaces for specific 
* 	objects that will be reading, writing, building, and sending csv's based on dynamic data.
* 
* 	This class leverages on data that is piped in from vovici to the $array_to_write variable
* 	The process Goes: 
* 	-1 Create a new csv object and set it to false for production mode
* 	-2 Set a key map that will map all the variable fields to the headers for the first row of the file
* 	-3 Read the data source, return the results. 
* 	-4 Write the datasource to a filename
* 	-5 Sanitize the file that was written
* 	-6 Create a new FTP or SFTP server
* 	-7 Push the file up to the client server
* 
**/

//Init - ** Fix the "true" test mode
$csv = new Csv(false);
$csv->setKeyMap($keymap);
$result = $csv->read($data = 'getCompleteArray', $csv->_testing, $params); 
$csv->write(null, $result, $csv->_testing);
$csv->ftp($creds);
//$csv->_ftp->push($csv->getTarget());

