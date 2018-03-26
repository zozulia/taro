<?php
if (array_key_exists('dbname',$_GET)){
	include_once('lib_db.php');
	$db = new DBMySQLPDO($_GET['host'], $_GET['user'], $_GET['password'], $_GET['dbname'], 'utf8');
	$db->execute('DROP TABLE IF EXISTS `polia`;
CREATE TABLE `polia` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_name` tinytext NOT NULL,
  `block_order` int(11) NOT NULL,
  `field_name` tinytext NOT NULL,
  `edition` int(11) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE lasteditions (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  dt timestamp,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;', false);
	$str = array();
	$str[] = 'define(\'DBHost2\',\'' . $_GET['host'] . '\');'.
	$str[] = 'define(\'DBUser2\',\'' . $_GET['user'] . '\');';
	$str[] = 'define(\'DBPassword2\',\'' . $_GET['password'] . '\');';
	$str[] = 'define(\'DBName2\',\'' . $_GET['dbname'] . '\');';
	$str[] = 'define(\'DBPort2\',3306);';
	$str[] = 'define(\'DBCharSet2\', \'utf8\');';
	$str[] = 'define(\'fn_template\',\'' . $_GET['template'] . '\');';
	$str[] = 'define(\'fn_production\', \'' . $_GET['production'] . '\');';
	$str[] = 'define(\'img_destination\', \'' . $_GET['img_destination'] . '\');';
	$str[] = 'define(\'title\',\'' . $_GET['site_title'] . '\');';
	$text = '<?php ' . implode("\n", $str) . ' ?>';
	file_put_contents('dbconfig.php', $text);
	echo '<pre>' . $text . '</pre>';
	die('install finished. Check, whether it was successfull');
}
else{?>
<form action="install.php" method="get">
	<label>Host MySQL <input type="text" name="host" placeholder="Host MySQL"></label><br />
	<label>MySQL user <input type="text" name="user" placeholder="MySQL user" ></label><br />
	<label>MySQL password <input type="text" name="password" placeholder="MySQL password"></label><br />
	<label>database name<input type="text" name="dbname" placeholder="database name"></label><br />
	<label>template file name from here<input type="text" name="template" placeholder="template file name from here"></label><br />
	<label>front-end file name from here<input type="text" name="production" placeholder="front-end file name from here"></label><br />
	<label>uploaded image directory<input type="text" name="img_destination" placeholder="uploaded image directory"></label><br />
	<label>Title of your landing<input type="text" name="site_title" placeholder="Title of your landing"></label><br />
	<input type="submit" value="OK">
</form>
<?php
}
?>
