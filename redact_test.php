<?php
//require 'functions.php';
set_QA();
?>
<main class="baseWidth_2">
    <form class="protoWidth_2" method="post">
        <div class="mainFormLegend">Тестирование Управления геологии, испытания и КРС</div>
        <input type="text" name="numberOfQuestion" placeholder="Номер вопроса">
        <textarea type="text" name="question" class="question" placeholder="Вопрос"></textarea>
        <div class="form-check answer">
            <textarea class="form-check-label" name="answer_1" placeholder="Ответ №1"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label" name="answer_2" placeholder="Ответ №2"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label" name="answer_3" placeholder="Ответ №3"></textarea>
        </div>
        <div class="form-check answer">
            <textarea class="form-check-label" name="answer_4" placeholder="Ответ №4"></textarea>
        </div>
        <input type="text" name="rightanswer" placeholder="Номер правильного ответа">
        <button type="submit" class="btn btn-primary startButton" name="" value="">Записать</button>
    </form>

</main>