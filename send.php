<!DOCTYPE html>

<html lang="en" xmlns="http://www.HTMLPage3.html">

<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>混雑状況の送信</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;500&family=Noto+Serif+JP:wght@300;500&display=swap"
        rel="stylesheet">

</head>
<h1>
    <div class=title>混雑状況送信フォーム</div>
</h1>
    <div class=moji>ここから混雑状況の送信が出来ます。</div>
<h1>
    <div class=title2>～混雑状況を判断する際の基準～</div>
</h1>
<div id="kijyunarea">
<div class="kijyun">
<img src="空いてる.jpg" alt=" " class="image1" />
    <div class=status><span class="statustitle">空いている</span><br>店内にほとんど人がいないか、数えるほどしかいない</div>
    </div><div class="kijyun">
<img src="まぁまぁ空いてる.jpg" alt=" " class="image1" />
    <div class=status><span class="statustitle">まぁ空いている</span><br>通路を見渡すことが出来る。自分の周りに数人いる程度</div>
</div><div class="kijyun"><img src="混んでる.jpg" alt=" " class="image1" />
    <div class=status><span class="statustitle">混んでいる</span><br>通路が少し通りづらい、売り場の商品を取るのに時間がかかる。レジに人が並び始める</div>
</div><div class="kijyun"><img src="激混んでる.jpg" alt=" " class="image1" />
    <div class=status><span class="statustitle">とても混んでいる</span><br>店の外に人が並んでいる、店の中も人が多くいわゆる3密の状態。レジ待ちで多くの人が並ぶ</div>
</div>
</div>
<div id="statusarea">
<div class="title2">送信内容</div>
<div class="storeinfo">
    <div class="name2">
店舗
<br>
<?php
echo $_POST['name'];
?>
</div>
<div class="vinicity">
<?php
echo $_POST['vicinty'];
?>
</div>

<form class="sendstatus" method="post" action="sending.php">
    <?php
    echo '<input type="hidden" value="'.$_POST['placeid'].'" name="placeid">';
    ?>
    
<div class="name">状態を選択</div>
<label><input type="radio" name="status" value="1" class="radio" onclick="funcstatus('空いている')"><img src="sui.jpg" class="radio_image"></label>
<label><input type="radio" name="status" value="2" class="radio" onclick="funcstatus('まぁ空いている')"><img src="malasui.jpg" class="radio_image"></label>
<label><input type="radio" name="status" value="3" class="radio" onclick="funcstatus('混んでいる')"><img src="komi.jpg" class="radio_image"></label>
<label><input type="radio" name="status" value="4" class="radio" onclick="funcstatus('とても混んでいる')"><img src="gekikomi.jpg" class="radio_image"></label>

状態
<div id="output">
    状態を選択して下さい
</div>
<div id="submit">

</div>
</form>
</div>
<script>
    function funcstatus(string){
        var outarea = document.getElementById("output");
        var submit = document.getElementById("submit");
        outarea.innerHTML=string;
        submit.innerHTML="<button type = 'submit' class='button3'>送信</button>";
    }
</script>
</body>

</html>