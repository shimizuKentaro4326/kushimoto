<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="/kushimoto/css/main.css">
<title>データ更新</title>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/person_detail.php");
?>
</head>
<body>
<form action="/kushimoto/php/update.php" method="post">
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/html/person_detail.html");
?>
<input type="submit" name="buttom"  value="更新"/>
</form>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/delete_form.php");
?>
</body>
</html>
