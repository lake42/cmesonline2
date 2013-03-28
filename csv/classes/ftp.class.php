<?php require_once(__DIR__.'/base.class.php');

class Ftp {
	private $host;
	private $username;
	private $password;
	private $port;
	private $initial_path;
	private $connection;
	private $secure;
	private $server;
	private $sftp;

	public function __construct(array $creds){
		$this->host = ($creds['server']) ? $creds['server'] : '';
		$this->username = ($creds['username']) ? $creds['username'] : '';
		$this->password = ($creds['password']) ? $creds['password'] : '';
		$this->port = ($creds['port']) ? $creds['port'] : 22;
		$this->initial_path = ($creds['initial_path']) ? $creds['initial_path'] : '/inbound/';
		$this->secure = ($creds['sftp']) ? $creds['sftp'] : true;
		$this->connect();
	}
	//Connect via SFTP if its secure otherwise use FTP to connect
	public function connect(){
			//Connect via FTP
			if(!$this->secure){
				if(!$_SESSION['connection']){
					echo 'Connecting via FTP <br>';
					if(!isset($this->connection)){
						$this->connection = ftp_connect($this->host, $this->port) or die('Unable to connect to '. $this->host);
						$_SESSION['connection'] = $this->connection;
					} else {
						if($this->connection){
							echo 'FTP Connection successful<br>';
							$server = ftp_login($this->connection, $this->username, $this->password);
							if($server){
								echo 'login successful<br>';
								$this->server = $server;
							} else {
								echo 'login unsuccessful<br>';
							}
						} else {
							echo '<br>could not connect to '. $this->host;
						}
				}
			}
		} else {
			$host = $this->host;
				echo 'Setting new sftp obj<br>';
				$sftp = new NET_SFTP($host);
				$this->sftp = $sftp;
				$username = $this->username;
				$password = $this->password;
				$sftp->login($username, $password);
				Base::debug($sftp->nlist());
		}
	}
	public function prepData($file){
		echo 'preppingData <br>';

		$tags = array('-',':', '"', "'");
		$tmp = array();

		$f = explode(',', $file);
		foreach($f as $item){
			//echo 'checking '.$item.'<br>';
			//on each item run the $tags filter
			foreach($tags as $tag){
				//echo 'for '.$tag.'<br>';
				switch($tag){
					case '"':
						$item = str_replace($tag,'', $item); 
					break;
					case "'":
						$item = str_replace($tag,'', $item); 
					break;
					case '-':
						$item = str_replace($tag,'', $item); 
					break;
					case ':':
						//$item = str_replace($tag,'\\'.$tag, $item); 
						$item = str_replace($tag,'', $item); 
					break;
					default: 
						//$item = str_replace($tag,'\\'.$tag, $item); 
						$item = str_replace($tag,'', $item); 
					break;
				}	
				
			}
			//push them onto the temp array
			$tmp[] = $item;
		}
		return implode(',',$tmp);
	}
	//Actually sends the data
	public function push($file){
		$file = substr_replace($file, '', 0, 60);
		//FTP in
		if(!$this->secure){
			if (ftp_put($this->connection, $this->initial_path.$file, $file, FTP_ASCII, 0)) {
				echo "successfully uploaded $file\n";
			} else {
				echo "There was a problem while uploading $file\n";
			}
		//SFTP in
		} else {
			echo 'putting with sftp <br>';
			//Base::debug($this->sftp);
			//$file = $this->prepData($file);
			// $file = "Call Center:,Policy Paid:,Email Address:,First Name:,Last Name:,State: ,Phone Number: ,Policy Amount: ,FACE_AMT:,PRODUCT PLAN:,PRODUCT_DESC:,Payment Type: ,Policy Number:,NPS Score: ,Open-End";
			//Base::debug($file);
			//the $mode is a contatnt that equates to an integer value in the library, like curl's constants.
			//put((string) $remote_file_path, (string) $data, (optional integer)$mode = NET_SFTP_STRING)
			if($this->sftp->put($this->initial_path.$file, $file, NET_SFTP_LOCAL_FILE)){
			//if(1 == 1){
				echo 'your file has been successfully written to the server.<br>';
			} else {
				echo 'There was an error trying to write the file to the server.<br>';
			}
			echo 'Disconnecting sftp obj<br>';
			$res = $this->sftp -> _disconnect(200);
			$res = (int) $res;
			echo '$sftp disconnect is: '.$res.'<br>';
		} //Close the SFTP Block
	}	//Closes the push method
}	//Closes the class