<?php
require_once('./lib_db.php');
$fn_data = 'data.ini';
$data = array();
if (file_exists($fn_data)){
	$arr_data = file($fn_data);
	foreach($arr_data as $str){
		list($key, $value) = explode('}=',trim($str));
		$data[$key.'}'] = $value;
	}
}

$db = new DBMySQLPDO();
if (array_key_exists('edition',$_GET)){
	$lastedition = (int)$_GET['edition'];
}
else
{
	$sql = 'SELECT MAX(edition) as lastedition FROM polia;';
	$db->execute($sql);
	$lastedition = $db->_rows[0]['lastedition'];
}

$template = file_get_contents(fn_template);

$reg = '/{--(\w+)--(\d*)--(\w*)--}/';
preg_match_all( $reg, $template, $matches );
//print_r($matches);
$sql='INSERT INTO polia(block_name, block_order, field_name, edition, `text`) VALUES(?,?,?,?,?);';
foreach($matches[0] as $i=>$val)
{
	//print_r($val);
	$select = 'SELECT id FROM polia WHERE block_name =? AND block_order=? AND field_name=? AND edition=?;';
	$db->execute($select,true,array($matches[1][$i],$matches[2][$i],$matches[3][$i], $lastedition));
	if (array_key_exists(0,$db->_rows) && array_key_exists('id', $db->_rows[0]))
		continue;
	if (array_key_exists($val, $data)) $init_val = $data[$val];
	else $init_val = $val;
	$params = array($matches[1][$i],$matches[2][$i],$matches[3][$i], $lastedition, $init_val);
	$db->execute($sql,false,$params);
	print_r($params);
}
?>
OK
