<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/common.php");
?>
<?php
header('Context-Type:application/json');
$postal_code = $_POST['postal_code'];

$db_host='localhost';
$db_user='root';
$db_pwd='';

$database='kushimoto';
try{
		$dbh = new PDO('mysql:host=localhost; dbname=kushimoto;charset=utf8', $db_user, $db_pwd, array(PDO::ATTR_PERSISTENT => true));
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt = $dbh->query("
select 
		t1.id as people_id,
    t2.id as household_id,
		concat(t1.last_name, t1.first_name) as name,
		t2.street as street,
		t2.tel as tel
from
		people t1
inner join household t2 on (t1.household_id = t2.id)
inner join postal_code t3 on (t2.postal_code_id = t3.id)
where
		t1.is_householder is true
and
				t3.id = '" . $postal_code . "'"
);
				$householder_list = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$householder_list[$row['household_id']] = array('name'=> $row['name'], 'street'=> $row['street'], 'tel'=> $row['tel'], 'people_id'=>$row['people_id']);
}
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		echo json_encode(compact('householder_list'));
}catch(PDOexception $e){
            echo $e->getMessage();
}
?>
