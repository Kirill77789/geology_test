<?php
//require 'functions.php';
set_QA();
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" >

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" media="screen" href="style.css">
</head>
<body>
<main class="baseWidth_2">
    <form class="protoWidth_2" method="post">
        <div class="mainFormLegend">Тестирование Управления геологии, испытания и КРС</div>
        <input type="text" class="questionNum" name="numberOfQuestion" placeholder="Номер вопроса">
        <textarea type="text" name="question" class="questionDev" placeholder="Вопрос"></textarea>
        <div class="form-check answer">
            <textarea class="form-check-label_2" name="answer_1" placeholder="Ответ №1"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label_2" name="answer_2" placeholder="Ответ №2"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label_2" name="answer_3" placeholder="Ответ №3"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label_2" name="answer_4" placeholder="Ответ №4"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label_2" name="section" placeholder="Секция"></textarea>
        </div>
        <input type="text" class="rightAnswerForm" name="rightanswer" placeholder="Номер правильного ответа">
        <button type="submit" class="btn btn-primary startButton" name="" value="">Записать</button>
    </form>

</main>