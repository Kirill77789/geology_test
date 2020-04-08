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
    foreach($data as $key=>$value){
        $data[$key] = formDataValidation($data[$key]);
    }
    $openPasssword = fopen('passwords.txt', 'w');
    $openUsedPasssword = fopen('used_passwords.txt', 'a');
    $checkPassword = false;
    $checkUsedPassword = false;
    while(!feof($openPasssword)){
        $line = fgets($openPasssword, 1024);
        if($line == $data['password']){
            $checkPassword = true;
            fwrite($openUsedPasssword, $line);
            file_put_content();
            fclose($openUsedPasssword);
            fclose($openPasssword);
        }
    }
    while(!feof($openUsedPasssword)){
        $line = fgets($openUsedPasssword, 1024);

        fwrite($openUsedPasssword, $line);
    }

    $personal_file = translit_my($data['FIO'].'_'.$data['branch'].'_'.$data['subdivision'].'.txt');
    if(!file_exists($personal_file)){
        $data['startTime'] = date("Y:m:d H:i:s");
        file_get_content();
        file_put_contents(translit_my($personal_file), json_encode($data, JSON_UNESCAPED_UNICODE)."\n");
        header('location:?page=test');
    }else{
        $users = explode("\n", file_get_contents($personal_file));
        foreach($users as $key=>$value){
            $value = json_decode($value, true);
            if(strtotime('+1 minutes',strtotime($value['startTime'])) < strtotime(date("Y:m:d H:i:s"))){
                return 'Ваше время истекло';
            }
        }
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

function translit_my($data){
    $data = mb_strtolower($data);
    $data = strtr($data, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    return $data;
}

/*function record(){
    if(empty($_POST)){

    }else{
        $data = $_POST;
        foreach($data as $key=>$value){
            $data[$key] = formDataValidation($data[$key]);
        }
    }
}*/

//08.04.2020
function set_QA() {
    if(!empty($_POST)){
        $data = $_POST;
        $newQuestions = array(
            $numberOfQuestion = $data['newQuestions'],
            $question = $data['question'],
            $answer_1 = $data['answer_1'],
            $answer_2 = $data['answer_2'],
            $answer_3 = $data['answer_3'],
            $answer_4 = $data['answer_4'],
            $rightanswer = $data['rightanswer']
        );
        $q_a = fopen('q_a.txt', 'r');
        while(!feof($q_a)){
            $line = fgets($q_a);
        }
    }
}