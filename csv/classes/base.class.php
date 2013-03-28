<?php 
require_once('vovici.api.class.php');
require_once(__DIR__.'/sanitize.class.php');
require_once(__DIR__.'/read.class.php');
require_once(__dir__.'/write.class.php');
require_once(__dir__.'/ftp.class.php');
require_once(__dir__.'/map.class.php');
require_once(__dir__.'/phpsec/Net/SFTP.php');

class Base {
	public function echo_file($file){
		var_dump($file);
	}
	public function debug_a(array $arr){
		echo "<pre>";
			print_r($arr);
		echo "</pre>";
	}
	public function debug($mixed){
		echo '<br>';
		switch(gettype($mixed)){
			case 'object':
				echo 'type: object<br>';
				echo "<pre>";
					var_dump($mixed);
				echo "</pre><br>";
			break;
			case 'array':
				echo 'type: array<br>';
				echo "<pre>";
					print_r($mixed);
				echo "</pre><br>";
			break;
			case 'boolean':
				echo 'type: bool<br>';
				echo "<pre>";
					$mixed = (int) $mixed;
					echo $mixed;
				echo "</pre><br>";
			break;
			case 'string':
				echo 'type: string';
				echo "<pre>";
					echo $mixed;
				echo "</pre><br>";
			break;
			default: 
				echo 'type: default<br>';
				var_dump($mixed);
				echo '<br>';
			break;
		}
	}
	public function vovici(array $config){
		return voviciAPI::getInstance($config);
	}
	public function newMap($data = null){
		return new Map($data);
	}
}