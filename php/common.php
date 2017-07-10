<?php
    $dsn='mysql:host=localhost; dbname=kushimoto';
    $db_user='root';
    $db_pwd='';
    $dbh = new PDO($dsn, $db_user, $db_pwd,array(PDO::ATTR_PERSISTENT => true));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function getOneRecord($sql){
        try{
            $stmt = $GLOBALS['dbh']->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        }catch(PDOexception $e){
           # $GLOBALS['dbh']->rollback();
            echo $e->getMessage();
        }
    }

    function getMultiRecords($key_name, $sql){
        try{
            $stmt = $GLOBALS['dbh']->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array($key_name=>$result);
        }catch(PDOexception $e){
#            $GLOBALS['dbh']->rollback();
            echo $e->getMessage();
        }
    }
?>
