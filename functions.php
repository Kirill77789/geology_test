<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 05.04.2020
 * Time: 15:03
 */
function formDataValidation($data){
    $data = stripcslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    $data = trim($data);
    //$data = strtolower($data);
    return $data;
}

function checkForm(){
    $errors = array();
    if(!empty($_POST)){
        $data = $_POST;
        $mustHave_testable = array(
            'FIO'=> 'Введите Ф.И.О.',
            'position' => 'укажите должность',
            'subDivision' => 'укажите подразделение',
            'branch' => 'укажите филилал',
            'password'=> 'укажите пароль');
        $mustHave_other = array(
            'FIO'=> 'Введите Ф.И.О.',
            'password'=> 'укажите пароль');

        if(empty($data['exampleRadios'])){
            $data['exampleRadios'] = 'testable';
        }
        if($data['exampleRadios'] == 'admin' || $data['exampleRadios'] == 'developer'){
            foreach($mustHave_testable as $key=>$value){
                if(!in_array($key, $data) && empty($data[$key])){

                    $errors[] = $mustHave_testable[$key];
                }
            }
        }else{
            foreach($mustHave_other as $key=>$value){
                if(!in_array($key, $data) && empty($data[$key])){
                    $errors[] = $mustHave_testable[$key];

                }
            }
        }
        if (empty($errors)){
            $start_error = startOfTest($data);
            if(!empty($start_error)){
                $errors[] = $start_error;
            }

        }
    }
    echo implode('; ', $errors);
    return $errors;
}
function errors_output(){
    $errors = checkForm();
    if(!empty($errors)){
        foreach($errors as $i=>$error){
            $errors[$i] = '<li>'.$errors[$i].'</li>';
        }
        $errors = '<ul>'.implode('', $errors).'</ul>';
        $error_output = '<div class="error_output">'.$errors.'</div>';
        echo $error_output;
    }
}
function startOfTest($data){
    date_default_timezone_set ( 'Europe/Moscow' );
    foreach($data as $key=>$value){
        $data[$key] = formDataValidation($data[$key]);
    }
    $checkPassword = false;
    $openPasssword = fopen('passwords.txt', 'r');
    while(!feof($openPasssword)){
        $line = fgets($openPasssword, 1024);
        $line = formDataValidation($line);
        if($line == $data['password']){
            $checkPassword = true;
            break;
        }
    }
    fclose($openPasssword);

    $checkUsedPassword = false;
    $openUsedPasssword = fopen('used_passwords.txt', 'r');
    if($checkPassword){
        while(!feof($openUsedPasssword)){
            $line = fgets($openUsedPasssword, 1024);
            //$line = formDataValidation($line);
            if(empty($line)){
                $input = array($data['password']=>date("Y:m:d H:i:s"));
                $openUsedPasssword = fopen('used_passwords.txt', 'a');
                fwrite($openUsedPasssword, json_encode($input)."\n");
                $openEmployes = fopen('users.txt', 'a');
                fwrite($openEmployes, json_encode($_POST, JSON_UNESCAPED_UNICODE)."\n");
                fclose($openUsedPasssword);
                fclose($openEmployes);
                $checkUsedPassword = true;
                break;
            }else{
                $line = json_decode($line, true);
                if($data['password'] == array_keys($line)[0]){
                    if((strtotime('+30 minutes', strtotime($line[$data['password']]))) > (strtotime(date("Y:m:d H:i:s")))){
                        $checkUsedPassword = true;
                        fclose($openUsedPasssword);
                        break;
                    }else{
                        fclose($openUsedPasssword);
                        return 'Ваше время истекло';
                    }
                }
            }
        }
    }
    if($checkUsedPassword){
        header('location:?page=test&user='.$data['password']);
    }
}

//07.04.2020
function init() {
    if(empty($_GET['page'])){
        $page = 'main';
    }else{
        $page = $_GET['page'];
    };
    include 'header.php';
    include $page.'.php';
    include 'footer.php';
}

//08.04.2020
function set_QA()
{
    if (!empty($_POST)) {
        $data = $_POST;
        $newQuestion = array(
            'numberOfQuestion' => $data['numberOfQuestion'],
            'question' => $data['question'],
            'answer_1' => $data['answer_1'],
            'answer_2' => $data['answer_2'],
            'answer_3' => $data['answer_3'],
            'answer_4' => $data['answer_4'],
            'rightanswer' => $data['rightanswer']
        );
        $wasorno = false;
        $wasorno_2 = false;
        $oldlines = array();
        $newline = array();
        $q_a = fopen('q_a.txt', 'r');
        while (!feof($q_a)) {
            $line = fgets($q_a, 1024);
            if (!empty($line)) {
                echo 'было не пусто<br>';
                $wasorno = true;
                $oldline = $line;
                $newline = json_decode($line, true);
                if ($newline['numberOfQuestion'] == $data['numberOfQuestion']) {
                    $wasorno_2 = true;
                    echo 'этот вопрос был раньше<br>';
                    $newline = json_encode($newQuestion, JSON_UNESCAPED_UNICODE) . "\n";
                    $oldlines[] = $newline;
                } else {
                    echo 'этого вопроса раньше не было<br>';
                    $oldlines[] = $oldline;
                }
            } else {
                if (!$wasorno) {
                    echo 'было пусто<br>';
                    $q_a = fopen('q_a.txt', 'a');
                    fwrite($q_a, json_encode($newQuestion, JSON_UNESCAPED_UNICODE) . "\n");
                    fclose($q_a);
                    break;
                }
            }
        }
        if ($wasorno) {
            echo 'после вайла<br>';
            if (!$wasorno_2) {
                $verynew = json_encode($newQuestion, JSON_UNESCAPED_UNICODE) . "\n";
                $q_a = fopen('q_a.txt', 'w');
                fwrite($q_a, $verynew);
                foreach ($oldlines as $value) {
                    $q_a = fopen('q_a.txt', 'a');
                    fwrite($q_a, $value);
                }
            } else {
                $q_a = fopen('q_a.txt', 'w');
                fwrite($q_a, $oldlines[0]);
                $i = 0;
                foreach ($oldlines as $key => $value) {
                    if ($i++ == 0) {
                        continue;
                    }

                    $q_a = fopen('q_a.txt', 'a');
                    fwrite($q_a, $value);
                }
                fclose($q_a);
            }
        }
    }
}
//09.04.2020
function start_time($password){
    $time = '';
    $password_1 = formDataValidation($password);
    $f = fopen('used_passwords.txt' ,'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if(array_keys($line)[0] == $password_1){
                $time = $line[$password_1];
            }
        }else{
            $time =date("Y:m:d H:i:s");
            break;
        }
    }
    fclose($f);
    return $time;
}
function end_time($password) {
    return strtotime('+30 minutes', strtotime(start_time($password)));
}

function is_time_over($password){
    $end = end_time($password);
   if($end > (strtotime(date("Y:m:d H:i:s")))){
       return true;
   }else{
       return false;
    }
}

function translit_my($data){
    $data = mb_strtolower($data);
    $data = strtr($data, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    return $data;
}

function current_question($user){
    $current_question = array();
    $fio = '';
    $branch = '';
    $pos = '';
    $subdivision = '';
    $f1 = fopen('users.txt', 'r');
    while(!feof($f1)){
        $line = fgets($f1, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['password'] == $user){
                $fio = $line['FIO'];
                $branch = $line['branch'];
                $pos = $line['position'];
                $subdivision = $line['subdivision'];
            }
        }else{
            break;
        }
    }
    fclose($f1);
    $wasorno_3 = true;
    $f2 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'a');
    fclose($f2);
    $f2 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'r');
    while(!feof($f2)){
        $line = fgets($f2, 1024);
        if(!empty($line)){
            $wasorno_3 = false;
            $line = json_decode($line, true);
            $current_question[] = $line['current_question'];
        }else{
            if($wasorno_3){
                return 1;
            }
        }
    }
    fclose($f2);
    return (max($current_question) + 1);
}

function record($data, $user){
    $massive = array();
    if(!empty($data)){
        $massive = array(
            'current_question'=> current_question($user),
            'answer'=> $data['exampleRadios'],
        );
        $f = fopen('q_a.txt', 'r');
        while(!feof($f)){
            $line = fgets($f, 2014);
            if(!empty($line)){
                $line = json_decode($line, true);
                if($massive['current_question'] == $line['numberOfQuestion']){
                    $massive['rightanswer'] = $line['rightanswer'];
                    if($line['rightanswer'] == $massive['answer']){
                        $massive['correct'] = 1;
                    }else{
                        $massive['correct'] = 0;
                    }
                }
            }else{
                fclose($f);
                break;
            }
        }
        fclose($f);
        $u = fopen('users.txt', 'r');
        while(!feof($u)){
            $line = fgets($u, 1024);
            if(!empty($line)){
                $line = json_decode($line, true);
                if($line['password'] == $user){
                    $fio = $line['FIO'];
                    $branch = $line['branch'];
                    $pos = $line['position'];
                    $subdivision = $line['subdivision'];
                    $f3 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'a');
                    fwrite($f3, json_encode($massive, JSON_UNESCAPED_UNICODE)."\n");
                    fclose($f3);
                }
            }else{
                break;
            }
        }
        fclose($u);
        header('location:?page=test&user='.$data['password']);
    };

}

function question($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['numberOfQuestion'] == $number){
                fclose($f);
                return $line['question'];
            }
        }else{
            fclose($f);
            break;
        }
    }
}

function user_ansewr_1($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['numberOfQuestion'] == $number){
                return $line['answer_1'];
            }
        }else{
            break;
        }
    }
}

function user_ansewr_2($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['numberOfQuestion'] == $number){
                return $line['answer_2'];
            }
        }else{
            break;
        }
    }
}

function user_ansewr_3($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['numberOfQuestion'] == $number){
                return $line['answer_3'];
            }
        }else{
            break;
        }
    }
}
function user_ansewr_4($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['numberOfQuestion'] == $number){
                return $line['answer_4'];
            }
        }else{
            break;
        }
    }
}

function question_count(){
    $count = 0;
    $f4 = fopen('q_a.txt', 'r');
    while(!feof($f4)){
        $line = fgets($f4, 1024);
        if(!empty($line)){
            $count++;
        }else{
            break;
        }
    }
    fclose($f4);
    return $count;
}

function init_done($user){
    $fio = '';
    $branch = '';
    $pos = '';
    $subdivision = '';
    $test_time = '';
    $user_errors = 0;
    //$flag = true;
    $u = fopen('users.txt', 'r');
    while(!feof($u)){
        $line = fgets($u, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['password'] == $user){
                $fio = $line['FIO'];
                $branch = $line['branch'];
                $pos = $line['position'];
                $subdivision = $line['subdivision'];
                $f3 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'r');
                while(!feof($f3)){
                    $line_2 = fgets($f3, 1024);
                    if(!empty($line_2)){
                        $line_2 = json_decode($line_2, true);
                        $user_errors += $line_2['correct'];
                    }else{
                        break;
                    }
                }
                fclose($f3);
            }
        }else{
            break;
        }
    }
    fclose($u);
    $f6 = fopen('used_passwords.txt', 'r');
    while(!feof($f6)){
        $line = fgets($f6, 1024);
        if(!empty($line)){
            $line = json_decode($line, true);
            if(array_keys($line)[0] == $user){
                $test_time = date("Y:m:d H:i:s", strtotime('+30 minutes', strtotime($line[$user])));
            }
        }else{
            break;
        }
    }
    fclose($f6);


    echo '<main class="baseWidth_3">

    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">
                    '.$fio.'<br>
                    '.$pos.'<br>
                    '.$subdivision.'<br>
                    '.$branch.'<br>
                    <hr>
                </a>
            </div>

        </div>
        <div class="col-9">
            <div class="result">
                <div class="date"> Время завершения теста: '.$test_time.'</div>
                <div class="rating">Допущено ошибок: '.(question_count() - $user_errors).'</div>
            </div>
        </div>
    </div>
</main>';

}

function qa_init($user){
    $question_count = question_count();
    $current_question = current_question($user);
    $question = question($current_question);
    $user_ansewr_1 = user_ansewr_1($current_question);
    $user_ansewr_2 = user_ansewr_2($current_question);
    $user_ansewr_3 = user_ansewr_3($current_question);
    $user_ansewr_4 = user_ansewr_4($current_question);
    record($_POST, $user);

    if($question_count >= $current_question){
        if(is_time_over($user)){
            echo '<main class="baseWidth_2">
    <form class="protoWidth_2" method="post">
        <div class="mainFormLegend">Тестирование Управления геологии, испытания и КРС</div>
        <div class="numberOfQuestion">Вопрос №'.$current_question.'</div>
        <div class="question">'.$question.'</div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios3" value="1" checked>
            <label class="form-check-label" for="exampleRadios3">
                '.$user_ansewr_1.'
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios4" value="2" checked>
            <label class="form-check-label" for="exampleRadios4">
                '.$user_ansewr_2.'
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios5" value="3" checked>
            <label class="form-check-label" for="exampleRadios5">
                '.$user_ansewr_3.'
            </label>
        </div>
        <div class="form-check answer">
            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios6" value="4" checked>
            <label class="form-check-label" for="exampleRadios6">
                '.$user_ansewr_4.'
            </label>
        </div>

        <button type="submit" class="btn btn-primary startButton" name="password" value='.$_GET['user'].'>Ответить</button>
    </form>

</main>';
        }else{
            echo 'Время прохождения теста истекло';
        }
    }else{
        header('location:?page=result&user='.$user);
    }
}