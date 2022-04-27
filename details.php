<?php
$status=["","とても空いている","まぁ空いている","混んでいる","とても混んでいる"];
$HTMLarray=["",'<span class="sui">','<span class="malasui">','<span class="komi">','<span class="gekikomi">',];
$placeid=$_GET['id'];
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
    $prepare = $dbh->prepare("SELECT avg(status) FROM post  WHERE date >= (NOW() - INTERVAL 1 hour) and placeID = :placeid ");
    $prepare->bindValue(':placeid',$placeid);
    $prepare->execute();
    $result = $prepare->fetch();
    $statusavg = $result[0];
    $prepare = $dbh->prepare("SELECT * FROM post  WHERE placeID = :placeid order by date DESC limit 0,:limits;");
    $prepare->bindValue(':placeid',$placeid);
    $prepare->bindValue(':limits',$limits);
    $prepare->execute();
    $result = $prepare->fetchAll();

      }catch(PDOException $e){
    //例外処理
    $error = $e->getMessage();
  var_dump($error);
}  
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>詳細</title>
        <link rel="stylesheet" href="style.css">
    <style>
        a{
            text-decoration:none;
        }
    </style>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon-180x180.png">

    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;500&family=Noto+Serif+JP:wght@300;500&display=swap" rel="stylesheet">

</head>
<body>
        <div id="header">
            <a href="index.html">
        <img src="sucomipng.png" class="headerimg" alt=""></a>
    </div>
    <div class="title">詳細情報</div>
    <div class="hour">
        直近１時間の混雑状況<br>
    <?php
    echo $statusavg;
    if (!empty($statusavg)) {
            if($statusavg>3.5&&$statusavg<=4){
      echo '<div class="gekikomi">';
      echo "とても混んでいます";
    }else if($statusavg>2.5&&$statusavg<=3.5){
      echo '<div class="komi">';
      echo "混んでいます";
    }else if($statusavg>1.5&&$statusavg<=2.5){
      echo '<div class="malasui">';
      echo "空いています";
    }else{
      echo '<div class="sui">';
      echo "とても空いています";
    }
  }else{
    echo '<div class="notinfo">';
    echo "直近１時間の情報はありません";
  }
  echo"</div><br>LOG(最近の30件のみ表示)<br><br>";
        # code...
    
        for ($i=0; $i <count($result) ; $i++) { 
        # code...
        echo $HTMLarray[$result[$i]['status']];
        echo $status[$result[$i]['status']];
        echo "</span><br>";
        echo $result[$i]['date'];
        
        echo "<br><br>";
    }
    if (!empty($_GET['keyword'])) {
      # code...
      echo '<a href="searchname.php?search='.$_GET["keyword"].'" class="button5">戻る</a><br><br>';
    }
    ?>
    
    <a href="searching.html" class="button5">戻る</a><br><br>
</div>
    
</body>
</html>