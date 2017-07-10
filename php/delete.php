<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>削除完了</title>
</head>
<body>
<?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/kushimoto/php/common.php");
?>
<?php
    try{
        $dbh->beginTransaction();

        $sql = '
        delete from household 
        using household 
        inner join people t2 on household.id = t2.household_id
        where
        t2.id = :people_id
        and t2.is_householder = "1";
        ';

        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(':people_id',$_POST['people_id'], PDO::PARAM_INT);
        $stmt->execute();
        /////////////////// people table

        $sql = '
        delete from people
        where
        id = :people_id
        ';

        $stmt=$dbh->prepare($sql);
        $stmt->bindValue(':people_id',$_POST['people_id'], PDO::PARAM_INT);
        $stmt->execute();


        $dbh->commit(); 
    ?>
    削除完了
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
