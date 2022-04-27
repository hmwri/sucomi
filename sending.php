<?php
require_once("private/important.php");
$DSN = sprintf('mysql:host=%s;dbname=%s;charest=utf8,unix_socket=/tmp/mysql.sock;',$db['host'],$db['dbname']);
try{
    date_default_timezone_set('Asia/Tokyo');
    $now= date("Y-m-d H:i:s");
    $message = "";
    $dbh = new PDO(
      $DSN,
      $db['user'],
      $db['pass'],
      array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
      )
    );
    $limits=30;
    $base = 0;
    $placeid=$_POST['placeid'];
    $status=$_POST['status'];
    $prepare = $dbh->prepare('insert into post (placeID,date,status)values(:placeid,:date,:status)');
    $prepare->bindValue(':placeid',$placeid);
    $prepare->bindValue(':status',$status);
    $prepare->bindValue(':date',$now);
    $prepare->execute();
    header( "Location:complete.html") ;
  }catch(PDOException $e){
    //例外処理
    $error = $e->getMessage();
  var_dump($error);
}
?>