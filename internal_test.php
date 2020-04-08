Привет мир!
<?php
/*$openUsedPasssword = fopen('used_passwords.txt', 'r');
while(!feof($openUsedPasssword)){
    $line = fgets($openUsedPasssword, 1024);
    if(empty($line)){
        echo 'пуст';
    }else{
        echo 'не пуст';
    }
}*/

$openEmployes = fopen('used_passwords.txt', 'r');
while(!feof($openEmployes)){
    $line = fgets($openEmployes, 1024);
    //$line = json_decode($line);
    if(empty($line)){
        echo 'пусто';
        //fwrite($openEmployes, json_encode(['d'=>13, 'tr'=>67], JSON_UNESCAPED_UNICODE)."\n");
        //fclose($openEmployes);
    }else{
        echo 'не пусто';
    }
}
?>