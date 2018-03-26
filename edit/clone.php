<?php
	session_start();
	require_once('./lib_db.php');
	$db = new DBMySQLPDO();
	$sql = 'SELECT MAX(edition) as lastedition FROM polia;';
	$db->execute($sql);
	$newedition = 1 + $db->_rows[0]['lastedition'];
	if (array_key_exists('edition',$_GET)){
		$edition = (int)$_GET['edition'];
	}	
	else
	{
		$edition = $newedition - 1;
	}

	$sql = 'SELECT * FROM polia WHERE edition = ?';
	$db->execute($sql, true, array($edition));
	$fields = $db->_rows;
	//print_r($fields);
	$sql = 'INSERT INTO polia(block_name,block_order,field_name,edition, text) VALUES(?,?,?,?,?);';
	if (is_array($fields)) foreach($fields as $row){
		$row['edition'] = $newedition;
		$params = array();
		$i=0;
		foreach($row as $field_name => $param)
		{
			if ($i++>0)
				$params[] = $param;
		}
		//print_r($params);
		$db->execute($sql,false,$params);
	}
	$sql_lastedition = 'REPLACE lasteditions(id,dt) VALUES(?,current_timestamp);';
	$db->execute($sql_lastedition, false, array($newedition));
	header('Location: ./');
?>