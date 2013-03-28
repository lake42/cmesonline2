<?php
/**
 *  A class to sanitize data
 * 
 *  It will clean the data differently based on each type availible.
 *  If it is an file it will read the contents in, get the path, filter the string, 
 *  and put the contents back into the file it pulled them from.
 * 
 *  @param  array $blacklist list of items not allowed
 *  @return mixed returns whatever type it is passed
 * 
 * 
 */
class Sanitize extends Base {
	public $elem;
	public $type;
	public $_blacklist;

	public function __construct(array $blacklist){
		$this->_blacklist = $blacklist;
	}
	public function setBlacklist($arr){
		$this->_blacklist = $arr;
	}
	public function determineType($elem){	
		if(gettype($elem) == 'resource'){
			$this->type = 'resource';
		} else {
			$this->type = gettype($elem);
		}
	}
	public function clean($elem){
		$this->determineType($elem);

		switch($this->type){
			case 'string': 
				return $this->cleanString($elem);
			break;
			case 'array':
				return $this->cleanArray($elem);
			break;
		} //Ends switch
	}
	public function cleanString($elem){
		if((substr($elem, 0, 1)) == '/'){
			$this->cleanFile($elem);
			return;
		}
		foreach($this->_blacklist as $item){
			//echo 'The $item is: '.$item.' and the $elem is: '.$elem.'<br>';
			$elem = str_replace($item, '', $elem);
		}
		return trim($elem);
	}
	public function cleanArray($elem){
		//$this->debug('processing an array');
		$blacklist = $this->_blacklist;
		foreach($blacklist as $item){
			$elem = str_replace($item, '', $elem);
		}
		return $elem;
	}
	public function cleanFile($elem){
		$blacklist = $this->_blacklist;
		$contents = file_get_contents($elem);
		foreach($blacklist as $item){
		 	$contents = trim(str_replace($item, '', $contents));
		}
		file_put_contents($elem, $contents);
	}
}