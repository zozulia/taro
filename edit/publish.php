<?php
	session_start();
	require_once('./lib_db.php');
	$db = new DBMySQLPDO();
	$sql = 'SELECT MAX(edition) as lastedition FROM polia;';
	$db->execute($sql);
	$newedition = 1 + $db->_rows[0]['lastedition'];
	if (array_key_exists('edition',$_GET)){
		$lastedition = (int)$_GET['edition'];
	}	
	else
	{
		$lastedition = $newedition - 1;
	}
	$_SESSION['edition']=$lastedition;
	$sql = 'SELECT * FROM polia WHERE edition = ?';
	$db->execute($sql, true, array($lastedition));
	$arr_subst = array();
	if (is_array($db->_rows)) foreach($db->_rows as $row){

		if(strpos($row['field_name'],'href') !== false)
		{
			$arr_subst['{--' . $row['block_name'] . '--' . $row['block_order'] . '--' . $row['field_name'] . '--}'] = $row['text'] . '" data-block="' . $row['block_name'] . '" data-order="' . $row['block_order'] . '" data-field="' . $row['field_name'];
		}
		else
		{
			
			$arr_subst['{--' . $row['block_name'] . '--' . $row['block_order'] . '--' . $row['field_name'] . '--}'] = '<span class="edited" data-block="' . $row['block_name'] . '" data-order="' . $row['block_order'] . '" data-field="' . $row['field_name'] . '">' . $row['text'] . '</span>';
		}
	}
	$sql = 'SELECT p1.id FROM polia p1 JOIN polia p2 ON p1.block_name=p2.block_name AND p1.block_order=p2.block_order AND p1.field_name=p2.field_name AND p1.edition=p2.edition AND p1.id>p2.id';
	$db->execute($sql);
	$ids = array();
	if (is_array($db->_rows)) foreach ($db->_rows as $row)
	{
		$ids[] = $row['id'];
	}
	if (array_key_exists(0,$ids)){
		$sql = 'DELETE FROM polia WHERE id IN(' . implode(',',$ids) . ')';
		$db->execute($sql, false);
	}
	$img_edition_dir = '../edit/img/' . $_SESSION['edition'];
	//echo $img_edition_dir;
	if (!file_exists($img_edition_dir)) mkdir($img_edition_dir);
	if ( array_key_exists('full',$_GET) )
	{
		$arr_subst['<!--edition-->'] = title;
		if ($handle = opendir($img_edition_dir)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry{0} == '.') continue;
				//echo  '..' . img_destination . $entry;
				copy($img_edition_dir . '/' . $entry, '..' . img_destination . $entry);
			}
			closedir($handle);
		}
	}
	else{
		$arr_subst['<!--lib-edited-->']='<script src="/edit/edit.js"></script><script>$(document).ready(prepare2edit);</script>';
		$arr_subst['<!--edition-->']='Версия №' . $_GET['edition'];
		if ($handle = opendir($img_edition_dir))
		{
			while (false !== ($entry = readdir($handle))) {
				if ($entry{0} == '.') continue;
				$key = img_destination . $entry;
				$arr_subst[$key] = $img_edition_dir . '/' . $entry;
				//echo '<!--' . $key . '-->';
			}
			closedir($handle);
			//print_r($arr_subst);
		}
		//print_r($arr_subst);
	}
	$template = file_get_contents(fn_template);
	//print_r($template);
	if (array_key_exists('full',$_GET))
	{
		$landing_fn = __DIR__ . '/' . fn_production;
		//echo $landing_fn;
		file_put_contents($landing_fn, strtr($template,$arr_subst));
		$sql_lastedition = 'REPLACE lasteditions(id,dt) VALUES(?,current_timestamp);';
		$db->execute($sql_lastedition, false, array($newedition));
		header('Location: ' . fn_production);
		exit();
	}
	else echo strtr($template,$arr_subst);
?>
