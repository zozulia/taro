<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js?ver=3.9.2"></script>
    <meta charset="utf-8">

<script>
	function publish(){
		var edition = $('input[name="edition"]').val();
		var href= '/edit/publish.php?edition=' + edition + '&full=1';
		if (confirm('Вы действительно хотите опубликовать редакцию №' + edition + '?'))
			window.location = href;
		return false;
	}
	
	function get_version()
	{
		if (!confirm("Вьі действительно хотите єтого?")) return 0;
		var inputs = document.getElementsByTagName("input");
		for(ii in inputs)
		{
			if (inputs[ii].checked) return inputs[ii].value;
		}
		return 0; 
	}
	
</script>
<style>
	html,body{
		padding: 0px;
		margin: 0px;
		font-family: Arial;
	}
	
	.for-form{
		float: left;
		margin: 5px;
		margin-right: 20px;
	}
	
	h1{
		font-size: 14pt;
		text-align: left;
	}
	
	form table{
		border-collapse: collapse;
		border-right: solid 5px #81828a;
	}
	
	form td{
		padding: 5px 10px;
	}
	
	.gray, .radio{
		background-color: #81828a;
	}
	
	.caption{
		font-weight: normal;
		color:black;
		padding: 5px 10px;
	}
	
	.total{
		text-align: right;
		padding-right: 18px;
	}
	
	.link a{
		color: #81828a;
	}
	
	.pencil{
		padding: 5px 3px;
	}
	
	input[type="radio"],input[type="checkbox"]{
		transform: scale(1.333);
		border-color: #f00;
	}
	
	form table td a{
		text-decoration: none;
		color:#81828a;
		font-size: 110%;
	}
	
	form button, form input[type="submit"]{
		border: 0px;
		padding: 8px;
		margin: 5px;
		color: white;
		font-size: 110%;
	}
	
	input[type="submit"]{
		background-color: #e4730c;
	}
	
	button#btn-clone{
		background-color: #b4b5bd;
	}
	
	button#btn-delete{
		background-color: #81828a;
	}
	
	.signature{
		text-align: right;
		padding-right: 10px;
	}
</style>
</head>
<body>
<?php
	session_start();
	require_once('./lib_db.php');
	$db = new DBMySQLPDO();
	$sql = 'SELECT DISTINCT edition, dt FROM polia p JOIN lasteditions l ON p.edition=l.id ORDER BY l.dt ASC;';
	$db->execute($sql);
	$f = __DIR__ . '/../';
    $io = popen ( '/usr/bin/du -sk ' . $f, 'r' );
    $size = fgets ( $io, 4096);
    $size = substr ( $size, 0, strpos ( $size, "\t" ) );
	$size = 0.5 + round($size/1024, 2);
    pclose ( $io );
?>
<!--If You know, what You do, <a href="init.php">Init</a>.-->
<div class="for-form">
<form action="publish.php" method="get">
<table>
<tbody>
<tr>
	<th class="gray"></th>
	<th class="caption"><h1>Страница выбора<br />версий Landing Page</h1></th>
	<th class="total">Total<br /><?= $size; ?><br />MB</th>
</tr>
<?php 
	if(is_array($db->_rows))
		foreach($db->_rows as $row)
		{

?>
<tr>
	<td class="radio">
		<?= '<input type="radio" name="edition" value="' . $row['edition'] . '" />'; ?>
	</td>
	<td class="link">
		<?= '<a target="_blank" href="/edit/publish.php?edition=' . $row['edition'] . '">' . $row['edition'] . ' - ' . $row['dt'] . ' </a>'; ?>
	</td>
	<td class="pencil">
		<?= '<a target="_blank" href="/edit/publish.php?edition=' . $row['edition'] . '"><img src="./img/pencil.png" align="right" /></a>'; ?>
	</td>
</tr>
		<?php } ?>
<tr>
	<td class="radio">
	</td>
	<td colspan="2">
		<input type="checkbox" name="full" value="1">
		<input type="submit" value='Publish'/>
		<button id="btn-clone" href="#" onclick="document.location='clone.php?edition='+get_version(); return false;">Clone</button>
		<button id="btn-delete" href="#" onclick="document.location='delete.php?edition='+get_version(); return false;">Delete</button>
	</td>
</tr>
</tbody>
</table>
</form>
</div>
<h2>ИНСТРУКЦИЯ</h2>
<p>
Принцип системы изменения контента Landing Page от веб-студии PUSH-K Solutions основан на генерации Версий Landing Page. В каждой Версии хранятся тексты и изображения, которые были загружены именно в нее. Для удобства навигации каждой Версии присваивается дата её последнего изменения. Список Версий выведен на Странице выбора Версий и упорядочен по возрастанию даты последнего внесения изменений. Каждой новой версии всегда присваивается номер, который на 1 больше, чем наибольший номер среди уже существующих Версий. Так же каждой Версии присваивается уникальный url для предварительного просмотра и редактирования.
</p><p>
Чтобы перейти к редактированию любой из Версий, кликните мышью на значок карандаша. Таким образом Вы загрузите выбранную Версию в режиме предварительного просмотра. Что бы вызвать Редактор контента используйте комбинацию клавиш Alt+W в Chrome или Shift+Alt+W в Firefox. После вызова Редактора контент все области Landing Page, где предусмотрено редактирование выделятся красной рамкой или красной линией, или синей рамкой – теперь Вы можете вносить изменения. Нажатие на изменяемую надпись, картинку или ссылку открывает форму для её изменения. При изменении картинки, следите, чтобы ширина и высота новой картинки были такими, как указано в форме изменения картинки (над фотоаппаратом). Если не соблюдать следовать этому правилу, то нарушится верстка сайта. Что бы вернуться в режим предварительного просмотра и увидеть внесенные изменения обновите страницу (F5 или Ctrl+F5). Все вносимые Вами изменения будут сохранятся в Версии, которую Вы редактируете, автоматически.
</p><p>
Чтобы та или иная Версия была опубликована на основном адресе Landing Page, выберите мышью радиокнопку слева от номера версии, поставьте галочку справа от кнопки “Publish” и нажмите эту кнопку. Далее браузер отобразит Ваш Landing Page на основном адресе с внесенными изменениями в тексты, ссылки и изображения в соответствии с выбранной для публикации Версией.
Вы можете удалять Версии если Вам недостаточно дискового пространства на хостинге. Для удобства на Странице выбора Версий реализован счетчик суммарного размера всех версий в Мб. 
Обратите внимание, что после публикации Версии, Вы можете и дальше вносить в нее изменения. Таким образом, может возникнуть ситуация, когда ни одна из Версий уже не содержит исходные картинки и тексты. Чтобы этого избежать, рекомендуем создавать дубликаты важных для вас Версий. 
Комбинация клавиш Alt-H для браузера Chrome или Shift+Alt+H для Firefox открывает эту Инструкцию.
</p>
<p class="signature">
<a href="https://push-k.ua/">Web studio PUSH-K Solutions</a>
</p>
</body>
</html>