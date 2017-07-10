<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
        <title>登録完了</title>
</head>
<body>
<?php
$db_host='localhost';
$db_user='root';
$db_pwd='';

$database='kushimoto';
try{
		$dbh = new PDO('mysql:host=localhost; dbname=kushimoto', $db_user, $db_pwd);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
/////////////////// household table
		if($_POST['is_householder']){
		$sql = '
insert into household(
postal_code_id,
street,
tel
)
values(
:postal_code_id,
:street,
:tel
)';
$stmt=$dbh->prepare($sql);
$stmt->bindValue(':postal_code_id',$_POST['postal_code'], PDO::PARAM_STR);
$stmt->bindValue(':street',$_POST['street'], PDO::PARAM_STR);
$stmt->bindValue(':tel',$_POST['tel'], PDO::PARAM_STR);
$stmt->execute();
$household_id=$dbh->lastInsertId();

		}else{
						$household_id=$_POST['household_id_ref'];
		}
/////////////////// people table
		
		$sql = '
insert into people(
last_name,
last_name_rubi,
first_name,
first_name_rubi,
sex,
party_id,
celphone,
email,
birthday,
birthmonth,
birthyear,
memo,
deadday,
household_id,
is_householder)
values(
:last_name,
:last_name_rubi,
:first_name,
:first_name_rubi,
:sex,
:party_id,
:celphone,
:email,
:birthday,
:birthmonth,
:birthyear,
:memo,
:deadday,
:household_id,
:is_householder
)';

$stmt=$dbh->prepare($sql);
$stmt->bindValue(':last_name',$_POST['last_name'], PDO::PARAM_STR);
$stmt->bindValue(':last_name_rubi',$_POST['last_name_rubi'], PDO::PARAM_STR);
$stmt->bindValue(':first_name',$_POST['first_name'], PDO::PARAM_STR);
$stmt->bindValue(':first_name_rubi',$_POST['first_name_rubi'], PDO::PARAM_STR);
$stmt->bindValue(':sex',$_POST['sex'], PDO::PARAM_STR);
$stmt->bindValue(':party_id',$_POST['party_id'], PDO::PARAM_INT);
$stmt->bindValue(':celphone',$_POST['celphone'], PDO::PARAM_STR);
$stmt->bindValue(':email',$_POST['email'], PDO::PARAM_STR);
$stmt->bindValue(':birthday',$_POST['birthday'], PDO::PARAM_STR);
$stmt->bindValue(':birthmonth',$_POST['birthmonth'], PDO::PARAM_STR);
$stmt->bindValue(':birthyear',$_POST['birthyear'], PDO::PARAM_STR);
$stmt->bindValue(':memo',$_POST['memo'], PDO::PARAM_STR);
$stmt->bindValue(':deadday',$_POST['deadday'], PDO::PARAM_STR);
$stmt->bindValue(':household_id',$household_id, PDO::PARAM_INT);
$stmt->bindValue(':is_householder',$_POST['is_householder'], PDO::PARAM_BOOL);
$stmt->execute();
$people_id=$dbh->lastInsertId();
$dbh->commit();
?>
登録完了
    <form action="/kushimoto/php/search.php">
    <input type="submit" name="buttom"  value="一覧へ"/>
    </form>
<?php
}catch(PDOexception $e){
		$dbh->rollback();
		echo $e->getMessage();
}
?>
</body>

</html>
