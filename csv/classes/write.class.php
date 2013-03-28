<?php require_once(__dir__.'/base.class.php');
class Write extends Base {
	public $date;
	public $path;
	public $filename;
	public $data_to_write;
	public $output;
	private $testing;

	public function __construct($filename = 'test.csv', $data_to_write = array(), $echo){
		//sets all pertinent data
		//Ynj_gis
		//Ynj_gis = M_D_Y_H
		//Y: numeric representation of yr
		//n: numeric representation of month with leading zeros
		//j: day of the month without leading zeros
		//g: 12 hour format of an hour without leading zeros
		//i: minutes with leading zeros
		//s : seconds
		$this->date 	  		= date('Ynj_gis');
		chdir('products');
		$this->path 	  		= getcwd();
		$this->filename   		= $this->path.'/METM_NPSRedAlert_'.$this->date.'.csv';
		$this->output 	  		= fopen($this->filename, 'w');
		$this->data_to_write    = $data_to_write;
		$this->testing 			= $echo;
		$this->write();
	}
	public function write(){
		if(is_array($this->data_to_write[0])){
			foreach($this->data_to_write as $array){
				fputcsv($this->output, $array);
			} 
				$this->debug('your file has been written.');
				return true;
		} else {
			if(is_array($this->data_to_write)){
				fputcsv($this->output, $this->data_to_write);
			} else {
				fputcsv($this->output, $this->data_to_write);	
				$this->debug('There was an error processing your data. Please try again.');
				return false;
			}
		}
	}
}