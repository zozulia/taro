<?php
	session_start();
	require_once('./lib_db.php');
	if(!array_key_exists('edition', $_SESSION))
	{
		if(array_key_exists('edition', $_GET))
			$_SESSION['edition'] = (int)$_GET['edition'];
		else
			$_SESSION['edition'] = -1;
	}
	$params = array($_POST['val'], $_POST['block_name'], $_POST['block_order'], $_POST['field_name'], $_SESSION['edition']);
	$sqlWhere =  'where block_name=? AND block_order=? AND field_name=? AND edition=?';
	$sql='update polia set `text`=? ' . $sqlWhere;
	$db = new DBMySQLPDO();
	$db->execute($sql, false, $params);
	$sql = 'SELECT `text`, ? as etalon FROM polia ' . $sqlWhere;
	$db->execute($sql, true, $params);
	echo $db->_rows[0]['text'];
	//print_r($db->_rows);
	if ($db->_rows[0]['text'] != $db->_rows[0]['etalon']){
		mail('roman.zozulia@gmail.com', 'Bad update', implode(',', $params));
		echo $sql; 
	}
	else{
		$sql_lastedition = 'REPLACE lasteditions(id,dt) VALUES(?,current_timestamp);';
		$db->execute($sql_lastedition, false, array((int)$_SESSION['edition']));
	}
?>