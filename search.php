<?php
require_once("private/important.php");
$DSN = sprintf('mysql:host=%s;dbname=%s;charest=utf8,unix_socket=/tmp/mysql.sock;',$db['host'],$db['dbname']);

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>検索結果</title>
    <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon-180x180.png">

    <meta name="robots" content="noindex,nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;500&family=Noto+Serif+JP:wght@300;500&display=swap" rel="stylesheet">
  </head>
  <body>
    <div id="loader-bg">
  <div id="loader">
  　<img src="Preloader_4.gif" alt="Loading..." />
  　<p>Loading...(しばらく時間がかかる場合があります)</p>
  </div>
</div>
<div id="contens">

        <div id="header">
          <a href="index.html">
        <img src="sucomipng.png" class="headerimg" alt="">
</a>
    </div>
    <div id='searchresult'>
      <div class="title">
         お近くのスーパー
      </div>
      <div class="title2">
         混雑状況の登録をお願いします
      </div>
      <div class="info">
        とても空いている、空いている、混んでいる、とても混んでいるのいずれかが表示されます<br>
        より詳しい状態を見たい場合は詳細ボタンをクリックしてください。<br>
        混雑状況を投稿する場合は混雑状況を投稿ボタンを押してください。<br>
      </div>
      <br>
      <table>
    <?php
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

  
    $x=$_POST['x'];
    $y=$_POST['y'];
    $url = "https://maps.googleapis.com/maps/api/place/nearbysearch/json?rankby=distance&location=".$y.",%20".$x."&types=supermarket&language=ja&key=AIzaSyDlrZJfOn1y9u0pZBk0txctk9-m13XDZdI";
    $json = file_get_contents($url);
    $json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
    $arr = json_decode($json,true);
    for ($i=0; $i < 20 ; $i++) {
      echo "<tr><td class='td1'><span class='name'>";
      $name = $arr['results'][$i]["name"];
      echo $name;
      echo"</span><br>";
      echo"<span class='vicinity'>";
      $vicinity =  $arr['results'][$i]["vicinity"];
      echo $vicinity;
      echo"</span><span class='opening_hours'>";
      $openflag = $arr['results'][$i]["opening_hours"]["open_now"];
      $placeid = $arr['results'][$i]["place_id"];
      if ($openflag == 1) {
        // code...
        echo "<span class='red'>営業中</span></span><br>";
      }else{
        echo "<br></span>";
      }
    $prepare = $dbh->prepare("SELECT avg(status) FROM post  WHERE date >= (NOW() - INTERVAL 1 hour) and placeID = :placeid");
    $prepare->bindValue(':placeid',$placeid);
    $prepare->execute();
    $result = $prepare->fetch();
    $statusavg = $result[0];
    if(!empty($statusavg)){
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
        echo '</div><a href="details.php?id='.$placeid.'"><button type="button" class="button4">詳細</button></a>';

      echo '<form action="send.php" method="post"><input type="hidden" name="placeid" value="';
      echo $placeid.'">';
      echo '<input type="hidden" name="name" value="';
      echo $name.'">';
      echo '<input type="hidden" name="vicinty" value="';
      echo $vicinity.'">';
      echo '<button type="submit" class="button3">混雑状況を投稿</button>';
      echo '</form></td><td>';
      echo '<iframe class="mapframe" src="https://maps.google.co.jp/maps?output=embed&q='.$arr['results'][$i]["name"].'"></iframe>';
      echo"</td></tr>";
      
    }
      }catch(PDOException $e){
    //例外処理
    $error = $e->getMessage();
  var_dump($error);
}  
    ?>
    
    </form>
  </table>
  <form action="showin.html" method="post">

  </form>
  </div>
</div>
<script>
  $(function() {
    var h = $(window).height(); // ブラウザウィンドウの高さを取得する
    $('#contents').css('display','none'); // コンテンツを非表示にする
    $('#loader-bg ,#loader').height(h).css('display','block');//ローディング画像を表示
});
$(window).on('load', function () { // 読み込み完了したら実行する
    $('#loader-bg').delay(900).fadeOut(800);// ローディングを隠す
    $('#loader').delay(600).fadeOut(300);
    $('#contens').css('display', 'block');// コンテンツを表示する
});
</script>
  </body>
</html>
