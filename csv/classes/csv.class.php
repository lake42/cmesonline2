<?php require_once('/var/www/html/cmesonline/csv/classes/base.class.php');
ini_set("auto_detect_line_endings", true);
class Csv extends Base {
	public $_write;
	private $_read;
	private $_map;
	public 	$_ftp;
	public  $_testing;
	private $keyMap;
	private $_sanitize;
	public  $list;

	public function __construct($echo){
		$this->_testing = $echo;
		$this->list = array("'", '"', ":", "\\015", "\\012", ",");
	}
	//Make an API call and read the result or read a file or read an array
	public function read($filename = './assets/test.csv', $_testing = true, $params = null){
		//will setup a new read obj that reads a file or an array
		$this->_read = new Read($filename, $_testing, $params);
		return $this->buildRows($this->_read->getResults());
	}
	public function buildRows($results){
		//how many results to expect from each array - compare all the arrays and make sure there are no blanks.
		return ($this->equalCounts($results)) ? $this->processRows($results) : false;
	}
	public function equalCounts($results){
		$counts = array();
		foreach($results as $array){
			$counts[] = count($array); 
		}
		return (count(array_unique($counts)) == 1) ? true : false;
	}
	public function sanitize($elem = null, $list) {
		//Create a sanitize obj if one doesnt exist. If it does then just clean the params.
		if(gettype($this->_sanitize) !== 'object'){
			//$list is the blacklist to clean against
			$this->_sanitize = new Sanitize($list);
			return $this->_sanitize->clean($elem);
		} else {
			//check it _blacklist is already set and list is empty
			if(!isset($this->_sanitize->_blacklist) || empty($this->_sanitize->_blacklist)){
				$this->_sanitize->setBlacklist($blacklist = array("'", '"', ":", "\\015", "\\012", ","));
			}
			return $this->_sanitize->clean($elem);
		}
	}
	public function processRows($results){
		$rows = array();
		$keys = array_keys($results);
		$count = 0;

		$tmp = array();
		foreach($this->keyMap as $key){
			$tmp[] = trim($key);
		}
		//These are the keys for the first header row
		$rows[] = $tmp;
		
		//Grabs all the rows - change the Q17 part to be dynamic for future as it has to be manually set for each survey
		//Q17 is just one of the question names. This is used to get a ceiling for the for loop. Nothing question specific.
		
		//Assembles a row
		for($i = 0; $i < count($results['Q4']); $i++){
			$temp = array();
			foreach($keys as $key){
				//each cell
				$temp[] = trim($this->sanitize($results[$key][$count], $this->list));
				//$temp[] = $results[$key][$count];
			}
			//Run a switch to map the correct names onto the call center question
			if($temp[0]){
				switch($temp[0]){
					case 1:
						$temp[0] = trim('SBIA');
					break;
					case 2:
						$temp[0] = trim('CSSG Sale');
					break;
					case 3:
						$temp[0] = trim('TZ');
					break;
					case 4:
						$temp[0] = trim('LO');
					break;
					case 5:
						$temp[0] = trim('Pinney');
					break;
					case 6:
						$temp[0] = trim('Barnum');
					break;
					case 7:
						$temp[0] = trim('Convergys');
					break;
					case 8:
						$temp[0] = trim('myinsuranceExpert');
					break;
					case 9:
						$temp[0] = trim('Cypress');
					break;
					case 10:
						$temp[0] = trim('SSP');
					break;
					case 11:
						$temp[0] = trim('OEA');
					break;
					case 12:
						$temp[0] = trim('Placeholder');
					break;

				} //close the switch
			}
			
			//Convert Unix Epoch Timestamp to a php datetime format
			//echo '$temp[16] is: '.$temp[16].'<br>';
			if($temp[16]){
				$stamp = explode('T', $temp[16]);
				$temp[16] = trim($stamp[0]);
			}
			//=< 6 is the red alert threshold for Epsilon in this survey
			if($temp[13] <= 6){
				$rows[] = $temp;
			}
			//Base::debug($temp);
			$count++;  
		}
		return $rows;
	}
	public function write($filename = './assets/test.csv', $data = array(), $_testing){
		//will setup a new write obj that writes to a file
		return $this->_write = new Write($filename, $data, $_testing);
	}
	public function setKeyMap(array $keymap){
		$this->keyMap = $keymap;
	}
	public function map(array $resp = array()){
		$this->_map = new Map();
	}
	//FTP: accepts an array of creds
	public function ftp(array $creds){
		//Make a new ftp client
		$this->_ftp = new Ftp($creds);
	}
	public function send(string $data = null){
		//Exposes the push method of the ftp class to the Csv object so it can push directly
		$this->_ftp->push($data = '');
	}
	public function getTarget(){
		$basepath = 'localhost/infosurv/csv';
		$target = trim($this->_write->filename); 
		return $target;
	}
}