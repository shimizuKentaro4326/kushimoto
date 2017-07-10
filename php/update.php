<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>更新完了</title>
</head>
<body>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/common.php");
?>
<?php
    $household_id= null;
    if($_POST['is_householder'] == "1"){
        $household_id=$_POST['household_id_orig'];
    }else{
        $household_id=$_POST['household_id_ref'];
    }
    try{
        $dbh->beginTransaction();
        /////////////////// household table
        if($_POST['is_householder'] == "1"){
            if($_POST['household_id_orig']== null){
                $sql='
                insert into household(id, postal_code_id, street, tel)
                values(:id, :postal_code_id,:street, :tel)';
                $stmt=$dbh->prepare($sql);
                $stmt->bindValue(':postal_code_id',$_POST['postal_code'], PDO::PARAM_STR);
                $stmt->bindValue(':street',$_POST['street'], PDO::PARAM_STR);
                $stmt->bindValue(':tel',$_POST['tel'], PDO::PARAM_STR);
                $stmt->bindValue(':id',$household_id, PDO::PARAM_INT);
                $stmt->execute();
                $household_id=$dbh->lastInsertId();
            }else{
                $sql = '
                update household set
                postal_code_id = :postal_code_id,
                street = :street,
                tel = :tel
                where
                id = :id';
                $stmt=$dbh->prepare($sql);
                $stmt->bindValue(':postal_code_id',$_POST['postal_code'], PDO::PARAM_STR);
                $stmt->bindValue(':street',$_POST['street'], PDO::PARAM_STR);
                $stmt->bindValue(':tel',$_POST['tel'], PDO::PARAM_STR);
                $stmt->bindValue(':id',$household_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }else if($_POST['is_householder'] == "0"){
            if($_POST['household_id_orig'] != null){
                $sql = '
                delete from household
                where id = :id';
                $stmt=$dbh->prepare($sql);
                $stmt->bindValue(':id',$_POST['household_id_orig'], PDO::PARAM_INT);
                $stmt->execute();
            }
            
        }
        /////////////////// people table

        $sql = '
        update people set
        last_name = :last_name,
        last_name_rubi = :last_name_rubi,
        first_name = :first_name,
        first_name_rubi = :first_name_rubi,
        sex = :sex,
        party_id = :party_id,
        celphone = :celphone,
        email = :email,
        birthday = :birthday,
        birthmonth = :birthmonth,
        birthyear = :birthyear,
        memo = :memo,
        deadday = :deadday,
        household_id = :household_id,
        is_householder = :is_householder
        where
        id = :people_id
        ';

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
        $stmt->bindValue(':people_id',$_POST['people_id'], PDO::PARAM_INT);
        $stmt->execute();
        $people_id=$dbh->lastInsertId();
        $dbh->commit(); 
    ?>
    更新完了
    <form action="/kushimoto/php/search.php">
    <input type="submit" name="buttom"  value="一覧へ"/>
    </form>
    <?php
    }catch(PDOexception $e){
        $dbh->rollback();
        echo $e->getMessage();
    } ?>
    </body>

    </html>
