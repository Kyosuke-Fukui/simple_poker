<?php

//デッキの作成
$suits = ['♠','♥','♦','♣'];

$faces = [];

for($i = 2; $i<11; $i++){
    $faces[]=$i;
}
$faces[]='J';
$faces[]='Q';
$faces[]='K';
$faces[]='A';

$deck = [];

foreach($suits as $suit){
    foreach($faces as $key => $face){
        $deck[] = array("key"=>$key,"face"=>$face,"suit"=>$suit);
    }
}
// var_dump($deck);

//手札の決定
shuffle($deck);
$cardPlayer = [];
$cardOpp = [];

for($i = 0; $i < 5; $i++) {
    $cardPlayer[] = array_shift($deck);
}

for($i = 0; $i < 5; $i++) {
    $cardOpp[] = array_shift($deck);
}

function sort_hands($array){
    $sort_keys = [];
    foreach($array as $key => $value){
        $sort_keys[$key] = $value['key'];
    }
    array_multisort($sort_keys,SORT_DESC,$array);
    return $array;
}

$sorted_cardPlayer = sort_hands($cardPlayer);
$sorted_cardOpp = sort_hands($cardOpp);

//役の判定
function show_hands($array){
    //スーツが同じ手札の枚数を数える
    $suit_array = [];
    foreach($array as $key => $value){
        $suit_array[$key] = $value['suit'];
    }
    $same_suits = array_count_values($suit_array);
    $count_same_suits = max($same_suits);
    // echo $count_same_suits.'<br>';
    //数字が同じ手札の枚数を数える
    $face_array = [];
    foreach($array as $key => $value){
        $face_array[$key] = $value['key'];
    }
    $same_faces = array_count_values($face_array);
    $count_same_faces = max($same_faces);
    // echo $count_same_faces.'<br>';
    // var_dump($same_faces);

    //数字に重複がある場合の重複枚数が最も多いペアの数字
    $pair;
    // echo $face_array[0];
    arsort($same_faces);
    // var_dump($same_faces);
    $pair =  array_keys($same_faces)[0];

    $hand = [[],[],[]]; //役の名前、役の強さ、同じ役の場合の高い数字
    //ストレート系判定
    if($count_same_faces==1 && ($face_array[0]-1 == $face_array[1] && $face_array[1]-1 == $face_array[2] && $face_array[2]-1 == $face_array[3] && $face_array[3]-1 == $face_array[4])){
        if($count_same_suits == 5){
            if($face_array[0] == 12){
                $hand = ["ロイヤルストレートフラッシュ",1,12];
            }else {
                $hand = ["ストレートフラッシュ",2,$face_array[0]];
            }
        }else {
            $hand = ["ストレート",6,$face_array[0]];
        }
    }else {
        if($count_same_suits == 5) {
            $hand = ["フラッシュ",5,$face_array[0]];
        }else{
            if($count_same_faces == 4) $hand = ["フォーカード",3,$pair];
            if ($count_same_faces == 3){
                if(count($same_faces) == 2) {
                $hand = ["フルハウス",4,$pair];
                }else {
                $hand = ["スリーカード",7,$pair];
                }
            } 
            if($count_same_faces == 2) {
                if(count($same_faces) == 3){
                $hand = ["ツーペア",8,$pair];
                }else{
                $hand = ["ワンペア",9,$pair];
                }
            }
            if($count_same_faces == 1) $hand = ["ハイカード",10,$face_array[0]];
        }
    }

    return $hand;
}

// $test = [['key' => 6, 'face' => 8, 'suit' => '♠'],
//         ['key' => 5, 'face' => 7, 'suit' => '♠'],
//         ['key' => 4, 'face' => 6, 'suit' => ''],
//         ['key' => 2, 'face' => 4, 'suit' => '♠'],
//         ['key' => 3, 'face' => 5, 'suit' => '♠']];

// $hand = show_hands(sort_hands($test));
// var_dump($hand);

$playerHand = show_hands($sorted_cardPlayer);
$oppHand = show_hands($sorted_cardOpp);

$messege;
//勝敗判定
if($playerHand[1] < $oppHand[1]){
    $messege = 'あなたの勝ちです！！';
}elseif($playerHand[1] > $oppHand[1]){
    $messege = 'あなたの負けです＞＜';
}else{
    if($playerHand[2] > $oppHand[2]){
    $messege = 'あなたの勝ちです！！';
    }elseif($playerHand[2] < $oppHand[2]){
    $messege = 'あなたの負けです＞＜';
    }else{
    $messege = '引き分けです';
    }
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    h1{
        text-align:center;
    }

    .main{
        width: 400px; margin: 100px auto; text-align:center;
    }
    .result{
        margin:50px 0 
    }
    </style>
</head>
<body>
    <h1>シンプルポーカー</h1>
    <div class="main">
        <div>
            <?php 
            echo <<<EOM
            相手の手札：
            {$sorted_cardOpp[0]['face']}<span>{$sorted_cardOpp[0]['suit']}</span>
            {$sorted_cardOpp[1]['face']}<span>{$sorted_cardOpp[1]['suit']}</span>
            {$sorted_cardOpp[2]['face']}<span>{$sorted_cardOpp[2]['suit']}</span>
            {$sorted_cardOpp[3]['face']}<span>{$sorted_cardOpp[3]['suit']}</span>
            {$sorted_cardOpp[4]['face']}<span>{$sorted_cardOpp[4]['suit']}</span><br><br>
            「{$oppHand[0]}」<br><br><br>
            自分の手札：
            {$sorted_cardPlayer[0]['face']}<span>{$sorted_cardPlayer[0]['suit']}</span>
            {$sorted_cardPlayer[1]['face']}<span>{$sorted_cardPlayer[1]['suit']}</span>
            {$sorted_cardPlayer[2]['face']}<span>{$sorted_cardPlayer[2]['suit']}</span>
            {$sorted_cardPlayer[3]['face']}<span>{$sorted_cardPlayer[3]['suit']}</span>
            {$sorted_cardPlayer[4]['face']}<span>{$sorted_cardPlayer[4]['suit']}</span><br><br>
            「{$playerHand[0]}」
            EOM;
            ?>
        </div>
        <div class="result">
            <?php echo $messege; ?>
        </div>

        <input type="button"  value="もう一度遊ぶ" onclick="koshin()">
    
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script>
    function koshin(){
        location.reload();
    }

    $("span:contains('♥')").css("color","red");
    $("span:contains('♦')").css("color","red");
    </script>
</body>
</html>