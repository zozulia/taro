<?php
//require_once('./sender/dbconfig.php');
require_once('./edit/lib_db.php');
require_once('./sender/lib_mail.php');

$db = new DBMySQLPDO();
$sql = 'SELECT text FROM polia WHERE block_name=\'vebinar\' AND block_order=1 AND field_name=\'date\';';
$db->execute($sql);
if (is_array($db->_rows)){
	$date = $db->_rows[0]['text'];
}
else die('Wrong vebinar date;');

$email = addslashes($_POST['email']);

$msg = '<table>' . "\n";
foreach($_POST as $key=>$value){
	$msg .= '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>' . "\n";
}
$msg .= '</table>';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
//send('korchenkosv@gmail.com', $name, $msg);
mail('korchenkosv@gmail.com, roman.zozulia@gmail.com', 'Novaya zayavka', $msg, $headers);

$sql = 'SELECT signed FROM adresat WHERE email=?;';
$db->execute($sql,true,array($email));
if (is_array($db->_rows) && array_key_exists(0, $db->_rows) && array_key_exists('signed', $db->_rows[0])){
	die('You are allready in our database');
}

$name = addslashes($_POST['name']);
$phone = (array_key_exists('phone',$_POST))?$_POST['phone']:'0';
$sql='INSERT INTO adresat(email, name, phone, signed, reg_date, first_date) VALUES(?,?,?,0,CURDATE(),CURDATE())';
$params = array($email,$name,$phone);
$db->execute($sql, false, $params);

$template = file_get_contents('sender/letter0.html');
$template = strtr($template,array('{--date--}'=>$date));
send($email, '', $template);

$template = file_get_contents('sender/letter1.html');
$template = strtr($template,array('{--date--}'=>$date));
send($email, $name, $template);

//header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
