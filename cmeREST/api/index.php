<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/bishops', 'getBishops');
$app->get('/bishops/:id',	'getBishop');
$app->get('/churches',	'getChurches');
$app->get('/bishops/search/:query', 'findByName');
$app->post('/bishops', 'addBishop');
$app->put('/bishops/:id', 'updateBishop');
$app->delete('/bishops/:id',	'deleteBishop');

$app->run();

function getBishops() {
	$sql = "select * FROM cme_bishops ORDER BY rank_e ASC";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$cob = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"bishop": ' . json_encode($cob) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getChurches(){
    $sql = "SELECT church,address,city,state,zipcode,e_dist,phone FROM churches WHERE gmap='y'";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$churches = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '[{"churches": ' . json_encode($churches) . '}]';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getBishop($id) {
	$sql = "SELECT * FROM cme_bishops WHERE rank_e=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$bishop = $stmt->fetchObject();  
		$db = null;
		echo json_encode($bishop); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addBishop() {
	error_log('addBishop\n', 3, '/var/tmp/php.log');
	$request = Slim::getInstance()->request();
	$bishop = json_decode($request->getBody());
	$sql = "INSERT INTO cme_bishops (bishop_name, dstrct_title, email1, phone1, thumbnail, contact_info) VALUES (:name, :d_title, :email, :phone, :thumbnail, :contact_info)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $bishop->name);
		$stmt->bindParam("d_title", $bishop->grapes);
		$stmt->bindParam("email", $bishop->country);
		$stmt->bindParam("phone", $bishop->region);
		$stmt->bindParam("thumbnail", $bishop->year);
		$stmt->bindParam("contact_ifo", $bishop->description);
		$stmt->execute();
		$bishop->id = $db->lastInsertId();
		$db = null;
		echo json_encode($bishop); 
	} catch(PDOException $e) {
		error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateWine($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$bishop = json_decode($body);
	$sql = "UPDATE cme_bishops SET bishop_name=:name, distrct_title=:d_title, email1=:email, phone1=:phone, thumbnail=:thumbnail, contact_info=:contact_info WHERE rank_e=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("name", $bishop->name);
		$stmt->bindParam("grapes", $bishop->grapes);
		$stmt->bindParam("country", $bishop->country);
		$stmt->bindParam("region", $bishop->region);
		$stmt->bindParam("year", $bishop->year);
		$stmt->bindParam("description", $bishop->description);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($bishop); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteWine($id) {
	$sql = "DELETE FROM cme_bishops WHERE rank_e=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM cme_bishops WHERE UPPER(bishop_name) LIKE :query ORDER BY bishop_name";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$bishops = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"bishops": ' . json_encode($bishops) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
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

?>