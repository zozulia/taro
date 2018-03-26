<?php
	session_start();
	require_once('./lib_db.php');
	$db = new DBMySQLPDO();
	if (array_key_exists('edition',$_GET)){
		$edition = (int)$_GET['edition'];
	}	
	else{
		die();
	}

	$sql = 'DELETE FROM polia WHERE edition = ?;';
	$db->execute($sql, FALSE, array($edition));
	$sql = 'DELETE FROM lasteditions WHERE id = ?;';
	$db->execute($sql, FALSE, array($edition));
	header('Location: ./');
?>