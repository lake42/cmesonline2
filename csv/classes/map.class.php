<?php
/**
 *  Translates the results of a webservice request into an object that actually has
 *  a useable structure.
 * 
 * 
 * @ param array $data
 * @ return $map
 * 
 * */
class Map extends Base {
	private $data;				//Starting data
	private $temp = array();	//used as a workspace
	private $keys;				//list of all keys avalible
	public  $targets;			//the questions we want to target
	public  $map = array();		//Final object to return

	public function __construct($data = null){
		$this->data = $data;
		$this->getKeys($this->data);
	}
	//Get the master list of keys
	private function getKeys($data){
		$this->keys = array_keys($data[0]);
	}
	//Set the questions we want to extract
	public function setTargets(array $targets){
		$this->targets = $targets;
	}
	//Set the map to its initial state with the targeted keys and blank values
	public function initTargetArray(){
		foreach($this->targets as $key => $value){
			$this->map[$value] = '';
		}
	}
	//Handles adding the results of the mapping onto the map
	// private function pushMap($key, $value){
	// 	$this->map[$key][$value] = $this->map[$key][$value];
	// }
	private function pushMap($key, $value){
        $this->map[$key][] = $value;
    }
	//Build the map object by filtering through the initial data array filtering by targeted keys
	public function processData($data){
		foreach($data as $key => $value){
				//if the key is in the targets array and the value is not an array push it to the map
				if((in_array($key, $this->targets)) && !is_array($value)) { 
					$this->pushMap($key, $value);
				} else {
					//Otherwise recursively call the processData function and loop the inner values of the array
					if(is_array($value)) { 
						$this->processData($value);
					} 
				}
		} //closes foreach
	} //closes process data function
	public function getMapResults(){
		return $this->map;
	}

} //Ends datamap


/* -- Notes --------------------------------------------------------------------------------------------------
	#1	Need to refactor getKeys to loop through all of the reponses. Get the keys for the first item. 
		Then get the keys for each subsequent item. If there is a key in the subsequent item that is 
		not in the first list of keys then add it to the list of keys. That way we can account for the 
		fact that some questions may have a different key structure than others.

	#2	
*/