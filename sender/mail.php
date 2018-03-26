<?php
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf8' . "\r\n";
$text = file_get_contents('letter1.html');
mail('roman@push-k.ua, dm@push-k.ua, roman.zozulia@gmail.com', 'Test 4', $text, $headers);
echo $text;
?>
