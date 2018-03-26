<?php
require_once('../sender/dbconfig.php');
require_once('../edit/lib_db.php');
require_once('../sender/lib_mail.php');

$db = new DBMySQLPDO();
$sql = 'SELECT text FROM polia WHERE block_name=\'vebinar\' AND block_order=1 AND field_name=\'date\';';
$db->execute($sql);
if (is_array($db->_rows)){
	$date = $db->_rows[0]['text'];
}
else die('Wrong vebinar date;');

$sql = 'SELECT id, email, name FROM adresat WHERE signed=0 AND DATEDIFF(curdate(),second_date)>1000 AND DATEDIFF(curdate(),reg_date) = ?';
$db->execute($sql, true, array(second_delay));
if (is_array($db->_rows)){
	$template = file_get_contents('letter2.html');
	$template = strtr($template,array('{--date--}'=>$date));
	$rows = $db->_rows;	
	$sql = 'UPDATE adresat SET second_date=curdate() WHERE id=?';
	foreach( $rows as $row)
	{
		send($row['email'], $row['name'], $template);
		$db->execute($sql, false, array($row['id']));
		echo $row['email'] . '-' . $row['name'] . '<br />' . "\n";
	}
}

$sql = 'SELECT id, email, name FROM adresat WHERE signed=0 AND DATEDIFF(curdate(),third_date)>1000 AND DATEDIFF(curdate(),reg_date) = ?';
$db->execute($sql, true, array(third_delay));
if (is_array($db->_rows)){
	$template = file_get_contents('letter3.html');
	$template = strtr($template,array('{--date--}'=>$date));
	$rows = $db->_rows;	
	$sql = 'UPDATE adresat SET third_date=curdate() WHERE id=?';
	foreach( $rows as $row)
	{
		send($row['email'], $row['name'],$template);
		$db->execute($sql, false, array($row['id']));
		echo $row['email'] . '-' . $row['name'] . '<br />' . "\n";
	}
}
?>OK
