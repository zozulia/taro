<?php
//require_once('./dbconfig.php');
require_once('../edit/lib_db.php');

$db = new DBMySQLPDO();

$sql = 'UPDATE adresat SET signed=? WHERE id=?';
$params = array($_POST['signed'],$_POST['id']);
$db->execute($sql, false, $params);
?>
