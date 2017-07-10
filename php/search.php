<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<link rel="stylesheet" type="text/css" href="/kushimoto/css/main.css">
<title></title>
</head>
<body>
<form action="/kushimoto/php/regist_form.php">
<input type="submit" value="登録">
</form>
<table>
<thead><tr>
<th>世帯主</th>
<th>名前</th>
<th>住所</th>
<th>役職</th>
<th>メモ</th>
<th>イベント</th>
<th>贈り主</th>
<th>贈り物</th>
<th>要望</th>
<th>状態</th>
<th>政党</th>
</tr></thead>
<tbody><?php
$db_host='localhost';
$db_user='root';
$db_pwd='';

$database='kushimoto';
try{
    $dbh = new PDO('mysql:host=localhost; dbname=kushimoto', $db_user, $db_pwd);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    foreach($dbh->query("
    SELECT 		t1.id,
    case
    when t1.is_householder = 1 then '0'
    else null
    end as '世帯主',
    concat(t1.last_name,t1.first_name) as '名前',
    t6.name as '役職',
    concat(t4.address,t3.street) as '住所',
    t1.memo as 'メモ',
    t7.name as 'イベント',
    concat(t9.last_name,t9.first_name) as '贈り主',
    t8.contents as '贈り物',
    t10.contents as '要望',
    t12.value as '状態',
    t13.name as '政党'

    FROM 		people as t1
    left outer join 	household as t3 on (t1.household_id = t3.id)
    left outer join 	postal_code as t4 on (t3.postal_code_id = t4.id)
    left outer join 	role_apply as t5 on (t1.id = t5.people_id)  
    left outer join		role as t6 on (t5.role_id = t6.id)
    left outer join		event as t7 on (t7.people_id = t1.id)
    left outer join		gift as t8 on (t8.event_people_id = t7.people_id 
    and t8.event_branch = t7.branch)
    left outer join		people as t9 on (t9.id = t8.people_id)
    left outer join		demand as t10 on (t10.people_id = t1.id)
    left outer join		demand_type as t11 on (t11.id = t10.demand_type_id)
    left outer join		state as t12 on (t12.id = t10.state_id)
    left outer join		party as t13 on (t13.id = t1.party_id)
    order by
    t4.id asc,
    t3.street asc,
    t1.is_householder desc
    ") as $row){
echo <<< EOM
<tr>
    <td>{$row['世帯主']}</td>
    <td><a href="/kushimoto/php/update_form.php?people_id={$row['id']}">{$row['名前']}</a></td>
    <td>{$row['住所']}</td>
    <td>{$row['役職']}</td>
    <td>{$row['メモ']}</td>
    <td>{$row['イベント']}</td>
    <td>{$row['贈り主']}</td>
    <td>{$row['贈り物']}</td>
    <td>{$row['要望']}</td>
    <td>{$row['状態']}</td>
    <td>{$row['政党']}</td>
        </tr>
EOM;
    }
}catch(PDOexception $e){
    print $e;
}
?></tbody>
</table>
</body>
</html>
