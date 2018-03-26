<?php
	session_start();
	if(!array_key_exists('edition', $_SESSION))
	{
		if(array_key_exists('edition', $_POST))
			$_SESSION['edition'] = (int)$_POST['edition'];
		else
			$_SESSION['edition'] = -1;
	}

$target_dir = "img/" . $_SESSION['edition'];
if (!file_exists('img')) mkdir('img');
if (!file_exists($target_dir)) mkdir($target_dir);
$target_file = $target_dir . '/' . basename($_POST['fn']);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_FILES[0]["tmp_name"])) {
    $check = getimagesize($_FILES[0]["tmp_name"]);
    if($check !== false) {
		if (move_uploaded_file($_FILES[0]["tmp_name"], $target_file)) {
			//echo "The file ". basename( $_FILES[0]["name"]). " has been uploaded.";
			require_once('./lib_db.php');
			$db = new DBMySQLPDO();
			$sql_lastedition = 'REPLACE lasteditions(id,dt) VALUES(?,current_timestamp);';
			$db->execute($sql_lastedition, false, array((int)$_SESSION['edition']));
		} else {
			print_r($_FILES);
			echo "Sorry, there was an error uploading your file.";
		}
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
	echo '/edit/' . $target_file;
}
else print_r($_FILES);
?>