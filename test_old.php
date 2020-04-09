<?php
qa_init($_GET['user']);
$current_question = $current_question($_GET['user']);
$user_ansewr_1 = $user_ansewr_1($_GET['user']);
$user_ansewr_2 = $user_ansewr_2($_GET['user']);
$user_ansewr_3 = $user_ansewr_3($_GET['user']);
$user_ansewr_4 = $user_ansewr_4($_GET['user']);

?>
<main class="baseWidth_2">
    <form class="protoWidth_2">
        <div class="mainFormLegend">Тестирование Управления геологии, испытания и КРС</div>
        <div class="numberOfQuestion">Вопрос №<?php num_q($_GET['user']); ?></div>
        <div class="question"><?php $current_question; ?></div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="1" checked>
            <label class="form-check-label" for="exampleRadios3">
                <?php $user_ansewr_1; ?>
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios4" value="2" checked>
            <label class="form-check-label" for="exampleRadios4">
                <?php $user_ansewr_2; ?>
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios5" value="3" checked>
            <label class="form-check-label" for="exampleRadios5">
                <?php $user_ansewr_3; ?>
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios6" value="4" checked>
            <label class="form-check-label" for="exampleRadios6">
                <?php $user_ansewr_4; ?>
            </label>
        </div>

        <button type="submit" class="btn btn-primary startButton" name="" value="">Ответить</button>
    </form>

</main>
