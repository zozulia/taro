<html>
<head>
<meta charset="utf-8">
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>
<script>
	$(document).ready(function(){
		$('.signed input').click(function(){
			var cur_id = $(this).val();
			var int_checked = 0;
			$('.signed input:checked').each(function(){
				//console.log('checked val=' + $(this).val());
				if ($(this).val()==cur_id){
					int_checked = 1;
					return false;
				}
			})
			$.post('/sender/get_signed.php',{id:$(this).val(), signed:int_checked});
		});
	});
</script>
<style>
	th, td{padding: 3px 5px;}
</style>
</head>
<body>
<?php
require_once('./dbconfig.php');
require_once('../edit/lib_db.php');

$db = new DBMySQLPDO();

$sql='SELECT id, email, name, phone, reg_date, signed, datediff(CURDATE(),reg_date) as last, datediff(first_date, CURDATE()) as first, datediff(second_date, CURDATE()) as second, datediff(third_date, CURDATE()) as third FROM adresat ORDER BY id DESC;';

$db->execute($sql);
if (is_array($db->_rows)){
	echo '<table>';
	echo '<tr>';
	echo '<th>E-mail</th>';
	echo '<th>Ім\'я</th>';
	echo '<th>Телефон</th>';
	echo '<th>Дата реєстрації</th>';
	echo '<th>Вже оплатив</th>';
	echo '<th>Перший лист</th>';
	echo '<th>Другий лист</th>';
	echo '<th>Третій лист</th>';
	echo '</tr>';
	foreach( $db->_rows as $row)
	{
		echo '<tr>';
		echo '<td>' . $row['email'] . '</td>';
		echo '<td>' . $row['name'] . '</td>';
		echo '<td>' . $row['phone'] . '</td>';
		echo '<td>' . $row['reg_date'] . '</td>';
		echo '<td class="signed">';
		if ($row['signed'] == 1) $html_checked = 'checked';
		else $html_checked = '';
		echo '<input type="checkbox" value="' . $row['id'] .  '" ' . $html_checked . '>';
		echo '</td>';
		echo '<td>';
		if (($row['first'] <= 0)&&($row['first']> -99999)) echo 'відправлено';
		else echo 'через ' .  (first_delay - $row['last']) . ' днів';
		echo '</td>';
		echo '<td>';
		if (($row['second'] <= 0)&&($row['second']> -99999)) echo 'відправлено';
		else echo 'через ' .  (second_delay - $row['last']) . ' днів';
		echo '</td>';
		echo '<td>';
		if (($row['third'] <= 0)&&($row['third']> -99999)) echo 'відправлено';
		else echo 'через ' .  (third_delay - $row['last']) . ' днів';
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}
?>
</body>
</html>
