<?php
function send($email,$name, $template){
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
	if (!empty($name))
		$message = strtr($template, array('{--name--}'=>', ' . $name));
	else
		$message = strtr($template, array('{--name--}'=>''));
	mail($email, 'pidpyshitsia na nas', $message, $headers);
}
?>
