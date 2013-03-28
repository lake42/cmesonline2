<?php
function check($address){
	$needle = "undeliverable";
	$exec = exec("exim -bt $address");
	$pos = strpos($exec,$needle);
	
	if ($pos === false){
		$msg = 0; // good address
	} else {
		$msg = 1; // bad address
	}
	 return $msg;
	//echo $exec;
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="ronky";
	$dbname="cme";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function getTest() {
	$sql = "SELECT email, id FROM cme_emails ORDER BY id";
	$sql2 = "UPDATE cme_emails SET status=:m WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$cob = $stmt->fetchAll(PDO::FETCH_OBJ);
		for($i=399;$i<463;$i++){
			$m = check($cob[$i]->email);
			$id = $cob[$i]->id; 
			echo $cob[$i]->email . " | " . $m . "<br>";
			$stmt = $db->prepare($sql2);  
		//	$stmt->bindParam("email", $cob[$i]->email);
			$stmt->bindParam("m", check($cob[$i]->email));
			$stmt->bindParam('id',$cob[$i]->id);
			$stmt->execute();
		}
		$db = null;
		//print_r($cob);
		//echo $cob[1]['email'];
//		echo '{"bishop": ' . json_encode($cob) . '}';
//		echo json_encode($cob);	
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

getTest();