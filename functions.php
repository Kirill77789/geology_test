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
    $data = strtolower($data);
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
                    if((strtotime('+1 minutes', strtotime($line[$data['password']]))) > (strtotime(date("Y:m:d H:i:s")))){
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
        header('location:?page=test');
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

/*function translit_my($data){
    $data = mb_strtolower($data);
    $data = strtr($data, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    return $data;
}*/

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
            //header('location:?page=redact_test');
            /*        fclose($q_a);
                    $oldlines[] = $newline;
                    file_put_contents('q_a.txt', $oldlines);*/


            //$oldlines[] = $newline;


            /*        echo '<pre>';
                    print_r($oldlines);
                    echo '</pre>';*/


            /*        foreach($oldlines as $key=>$value){
                        echo $key;
                        echo $value;
                        $q_a = fopen('q_a.txt', 'a');
                        fwrite($q_a, $value."\n");
                    }*/
            //fclose($q_a);
        }
    }
}