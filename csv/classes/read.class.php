<?php require_once(__dir__.'/base.class.php');
/**
*	Generate the file & a dynamic filename.
* 
* 	Relies on data that is piped in from vovici to the $array_to_write variable
* 
* 
**/
//getting the csv file
//$file = 
//var_dump($file);
class Read extends Base {
	private $testing;
	private $data;
	private $vovici_calls; //possible vocici methods to call
	private $params; //vovici params if needed
	private $result;

	public function __construct($data = './assets/test.csv', $echo = false, $params = null){
		$this->testing = $echo;
		$this->data = $data;
		$this->params = $params;
		$this->vovici_calls = array('getProjectInformation','getCompleteArray');
		$this->read();
		return $this->result;
	}
	//Make an API call and read the result or read a file or read an array
	public function read($result = null){
		if($this->testing) {
			echo 'Reading File: <br>';
				self::echo_file($this->data);
			echo '<br><br>';
		} else {
			switch(gettype($this->data)){
				case 'string':
					//Is it a vovici method? If so call the method, then pass the response back to itself ( on second pass it will be an array )
					//$this->data represents the function name to call.
					//$result is the response of that
					if(in_array($this->data, $this->vovici_calls)){
						$vovici = Base::vovici($this->params);
						$result = $vovici->call($this->data, $this->params); //fix the return type here
						if(!empty($result)){
							$this->data = $result;
							$this->read($this->data);
						}
					} else {
							//if not its a vovici method. Process the file
							$this->data = file_get_contents($this->data, true);
						}
				break;

				case 'array':
					//its a vovici response: make a new map object and pass it in
					$dataMap  = Base::newMap($this->data);
					/*$targets = array(
									'Q17',		//Call center
									'Q18_7',	//Policy paid date
									'Q18_8',	//Email
									'Q18_9',	//First name
									'Q18_10',	//Last name
									'Q18_13', 	//State
									'Q18_15',	//Phone number
									'Q18_19',	//Annual-Prem-Amount
									'Q18_20',	//Face amount
									'Q18_21', 	//Product plan
									'Q18_22',	//Product Desc
									'Q18_23',	//payment type
									'Q18_34',	//Policy Number
									'Q10',		//NPS
									'Q11'		//open ended
								);
					*/
					$targets = array(
									'Q10_6',	//Call center
									'Q10_7',	//Policy paid date
									'Q10_8',	//Email
									'Q10_9',	//First name
									'Q10_10',	//Last name
									'Q10_13', 	//State
									'Q10_15',	//Phone number
									'Q10_19',	//Annual-Prem-Amount
									'Q10_20',	//Face amount
									'Q10_21', 	//Product plan
									'Q10_22',	//Product Desc
									'Q10_23',	//payment type
									'Q10_34',	//Policy Number
									'Q2',		//NPS
									'Q4',		//open ended
								 	'Q10_5',	//CAMPAIGN_ID
								 	'completed',	//Completed
								 	'Q10_3'		//RESERV_ID
								);
					$dataMap -> setTargets($targets);
					$dataMap -> initTargetArray();
					$dataMap -> processData($this->data);
					$this->result = $dataMap->map;
					//Base::debug($this->result);
				break;
			} //ends switch
		}

	}
	public function getResults(){
		return $this->result;
	}
}