<?php
/**
 * Created by PhpStorm.
 * User: Kirill
 * Date: 05.04.2020
 * Time: 15:03
 */

date_default_timezone_set ( 'Europe/Moscow' );

function formDataValidation($data){
    $data = stripcslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    $data = trim($data);
    //$data = strtolower($data);
    return $data;
}

function checkForm(){
    if(empty($_GET['user'])){
        $errors = array();
        if(!empty($_POST)){
            $data = $_POST;
            $mustHave_testable = array(
                'FIO'=> 'Введите Ф.И.О.',
                'position' => 'укажите должность',
                'subdivision' => 'укажите подразделение',
                'branch' => 'укажите филилал',
                'password'=> 'укажите пароль');
            $mustHave_other = array(
                'FIO'=> 'Введите Ф.И.О.',
                'password'=> 'укажите пароль');

            if(empty($data['exampleRadios'])){
                $data['exampleRadios'] = 'testable';
            }
            if($data['exampleRadios'] == 'admin' || $data['exampleRadios'] == 'developer'){
                foreach($mustHave_other as $key=>$value){
                    if(!in_array($key, $data) && empty($data[$key])){

                        $errors[] = $mustHave_other[$key];
                    }
                }
            }else{
                foreach($mustHave_testable as $key=>$value){
                    if(!in_array($key, $data) && empty($data[$key])){
                        $errors[] = $mustHave_testable[$key];

                    }
                }
            }
            if (empty($errors)&& $data['exampleRadios'] == 'testable'){
                $start_error = startOfTest($data);
                if(!empty($start_error)){
                    $errors[] = $start_error;
                }
            }elseif($data['exampleRadios'] == 'admin' && check_admin_password($data['password'])){
                header('location:?page=admin&user='.$data['password']);
            }elseif($data['exampleRadios'] == 'developer' && devPass($data['password'])){
                header('location:?page=redact_test&user='.$data['password']);
            }
        }
        echo implode('; ', $errors);
        return $errors;
    }
}

function devPass($password){
    $f9 = fopen('developer_passwords.txt', 'r');
    while(!feof($f9)){
        $line = fgets($f9, 1600);
        if(!empty($line)){
            if(formDataValidation($line) == formDataValidation($password)){
                return true;
            }
        }else{
            return false;
        }
    }
    fclose($f9);
    return false;
}

function check_admin_password($admin_password){
    $f8 = fopen('admin_passwords.txt', 'r');
    while(!feof($f8)){
        $line = fgets($f8, 1600);
        if(!empty($line)){
            if(formDataValidation($admin_password) == formDataValidation($line)){
                return true;
            }
        }else{
            break;
        }
    }
    fclose($f8);
    return false;
}

function init_admin(){
    $markup = '<!doctype html>
<html lang="en">
<head>
    <title>Тест по Геологии</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="icon" href="icon/geol.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" media="screen" href="style.css">
</head>
<main class="baseWidth_3">
    <div class="branchs_buttons">
        <button class="branch_btn all">Все</button>
        <button class="branch_btn cau">ЦАУ</button>
        <button class="branch_btn krb">ООО "Краснодар бурение"</button>
        <button class="branch_btn orb">ООО "Оренбург бурение"</button>
        <button class="branch_btn arb">ООО "Астрахань бурение</button>
        <button class="branch_btn urb">ООО "Уренгой бурение"</button>
    </div>
</main>';
    $subMarkup = '';
    $u = fopen('users.txt', 'r');
    while(!feof($u)){
        $questionCount_2 = 0;
        $line = fgets($u, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            $fio = $line['FIO'];
            $branch = $line['branch'];
            $pos = $line['position'];
            $subdivision = $line['subdivision'];
            $user_errors = 0;
            $fileName = 'users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt';
            $fileName = formDataValidation(translit_my($fileName));
            $test_time = date('d.m.Y H:i:s', filemtime($fileName));
            $f3 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'r');
            $subMarkup = '';
            while(!feof($f3)){
                $line_2 = fgets($f3, 1600);
                if(!empty($line_2)){
                    $questionCount_2++;
                    $line_2 = json_decode($line_2, true);
                    $user_errors += $line_2['correct'];
                    $prenumOfquestion =  $line_2['current_question'];
                    $pretextOfquestion = $line_2['numberOfQuestion'];
                    $preusersAnswer =    $line_2['answer'];
                    $numOfquestion =  '';
                    $textOfquestion = '';
                    $usersAnswer =    '';
                    $textOfAnswer_1 = '';
                    $textOfAnswer_2 = '';
                    $textOfAnswer_3 = '';
                    $textOfAnswer_4 = '';
                    $classWrite = 'classWrite';
                    $classWrong = 'classWrong';
                    $class_1 = '';
                    $class_2 = '';
                    $class_3 = '';
                    $class_4 = '';

                    if($line_2['correct'] == 0){
                        switch($line_2['rightanswer']){
                            case '1': $class_1 =  $classWrite;
                                        break;
                            case '2': $class_2 =  $classWrite;
                                        break;
                            case '3': $class_3 =  $classWrite;
                                         break;
                            case '4': $class_4 =  $classWrite;
                                        break;
                        }
                          switch($line_2['answer']){
                              case '1': $class_1 =  $classWrong;
                                          break;
                              case '2': $class_2 =  $classWrong;
                                          break;
                              case '3': $class_3 =  $classWrong;
                                           break;
                              case '4': $class_4 =  $classWrong;
                                          break;
                          }
                    }else{
                          switch($line_2['answer']){
                              case '1': $class_1 =  'onlyWrite';
                                          break;
                              case '2': $class_2 = 'onlyWrite';
                                          break;
                              case '3': $class_3 =  'onlyWrite';
                                           break;
                              case '4': $class_4 =  'onlyWrite';
                                          break;
                          }
                    };
                    $f22 = fopen('q_a.txt', 'r');
                    while(!feof($f22)){
                         $line_22 =  fgets($f22, 1600);
                         if(!empty($line_22)){
                             $line_22 =    json_decode($line_22, true);
                             if($pretextOfquestion == $line_22['numberOfQuestion']){
                                $numOfquestion = $line_22['numberOfQuestion'];
                                $textOfquestion = $line_22['question'];
                                $textOfAnswer_1 = $line_22['answer_1'];
                                $textOfAnswer_2 = $line_22['answer_2'];
                                $textOfAnswer_3 = $line_22['answer_3'];
                                $textOfAnswer_4 = $line_22['answer_4'];
                                //break;
                                $subMarkup .= '<main class="baseWidth_2">             
                                     <div class="protoWidth_2" method="post">
                                     <div class="justEmpty"></div>                              
                                         <div class="numberOfQuestion tetxtCentr">Вопрос №'.$numOfquestion.'</div>    
                                         <div class="question">'.$textOfquestion.'</div>                                                   
                                             <div class="answerInAdm '.$class_1.'">                   
                                                 '.$textOfAnswer_1.'                                       
                                             </div>                                                                    
                                             <div class="answerInAdm '.$class_2.'">                   
                                                 '.$textOfAnswer_2.'                                       
                                             </div>                              
                                             <div class="answerInAdm '.$class_3.'">                   
                                                 '.$textOfAnswer_3.'                                       
                                             </div>                            
                                             <div class="answerInAdm '.$class_4.'">                   
                                                 '.$textOfAnswer_4.'                                       
                                             </div>                                                     
                                     </div>                                                                
                                 </main>';
                             };
                         }else{
                             break;
                         }

                    }   fclose($f22);
                }else{
                    break;
                }
            }
            fclose($f3);
        }else{
            break;
        }
        $markup .= '<body><main class="baseWidth_3 forHide">
        <div class="adm_row">
            <div class="col-adm1">
                '.$fio.'<br>
                '.$pos.'<br>
                '.$subdivision.'<br>
                '.$branch.'<br>
            </div>
            <div class="col_adm2">
                <div class="result">
                    <div class="date"> Время завершения теста: '.$test_time.'</div>
                    <div class="rating">Количество ответов: '.($questionCount_2).'</div>
                    <div class="rating">Допущено ошибок: '.($questionCount_2 - $user_errors).'</div>
                    <button class="admbtn">ответы</button>
                </div>
            </div>
        </div>
        <div class="thisResult hide">
            '.$subMarkup.'
        </div>
    </main>';

    }
    fclose($u);
    echo $markup;
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
        $line = fgets($openPasssword, 1600);
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
            $line = fgets($openUsedPasssword, 1600);
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
                    if((strtotime('+60 minutes', strtotime($line[$data['password']]))) > (strtotime(date("Y:m:d H:i:s")))){
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
            'rightanswer' => $data['rightanswer'],
            'section' => $data['section']
        );
        $wasorno = false;
        $wasorno_2 = false;
        $oldlines = array();
        $newline = array();
        $q_a = fopen('q_a.txt', 'r');
        while (!feof($q_a)) {
            $line = fgets($q_a, 1600);
            if (!empty($line)) {
                $wasorno = true;
                $oldline = $line;
                $newline = json_decode($line, true);
                if ($newline['numberOfQuestion'] == $data['numberOfQuestion']) {
                    $wasorno_2 = true;
                    $newline = json_encode($newQuestion, JSON_UNESCAPED_UNICODE) . "\n";
                    $oldlines[] = $newline;
                } else {
                    $oldlines[] = $oldline;
                }
            } else {
                if (!$wasorno) {
                    $q_a = fopen('q_a.txt', 'a');
                    fwrite($q_a, json_encode($newQuestion, JSON_UNESCAPED_UNICODE) . "\n");
                    fclose($q_a);
                    break;
                }
            }
        }
        if ($wasorno) {
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
        $line = fgets($f, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            if(array_keys($line)[0] == $password_1){
                $time = $line[$password_1];
                fclose($f);
                return $time;
            }
        }else{
            $time = date("Y:m:d H:i:s");
            break;
        }
    }
    fclose($f);
    return $time;
}

function end_time($password) {
    return strtotime('+60 minutes', strtotime(start_time($password)));
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

function usersFileName($user){
    $fio = '';
    $branch = '';
    $pos = '';
    $subdivision = '';
    $f1 = fopen('users.txt', 'r');
    while(!feof($f1)){
        $line = fgets($f1, 1600);
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
    $usersFileName = formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt'));
    return $usersFileName;
};

function current_question($user){
    $current_question = array();
    $fio = '';
    $branch = '';
    $pos = '';
    $subdivision = '';
    $f1 = fopen('users.txt', 'r');
    while(!feof($f1)){
        $line = fgets($f1, 1600);
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
        $line = fgets($f2, 1600);
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
            'numberOfQuestion' => $data['numberOfQuestion'],
            'answer'=> $data['exampleRadios'],
            'password'=> $user
        );
        $f = fopen('q_a.txt', 'r');
        while(!feof($f)){
            $line = fgets($f, 2014);
            if(!empty($line)){
                $line = json_decode($line, true);
                if($massive['numberOfQuestion'] == $line['numberOfQuestion']){
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
        $massive['section'] = $data['section'];
        //fclose($f);
        $u = fopen('users.txt', 'r');
        while(!feof($u)){
            $line = fgets($u, 1600);
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
        $hir = 'location:?page=test&user='.formDataValidation($data['password']);
        header($hir);
    };

}

function question($number){
    $f = fopen('q_a.txt', 'r');
    while(!feof($f)){
        $line = fgets($f, 1600);
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
        $line = fgets($f, 1600);
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
        $line = fgets($f, 1600);
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
        $line = fgets($f, 1600);
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
        $line = fgets($f, 1600);
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

function questionSection($number){
    $file_12 = fopen('q_a.txt', 'r');
    while(!feof($file_12)){
        $line = fgets($file_12, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($number == $line['numberOfQuestion']){
                return $line['section'];
            }
        }else{
            fclose($file_12);
            break;
        }
    }
}

function question_count(){
    $count = 0;
    $f4 = fopen('q_a.txt', 'r');
    while(!feof($f4)){
        $line = fgets($f4, 1900);
        if(!empty($line)){
            $count++;
        }else{
            break;
        }
    }
    fclose($f4);
    return $count;
}

function init_done($user, $time){
    $fio = '';
    $branch = '';
    $pos = '';
    $subdivision = '';
    $test_time = '';
    $user_errors = 0;
    $countQuestions = 0;
    //$flag = true;
    $u = fopen('users.txt', 'r');
    while(!feof($u)){
        $line = fgets($u, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            if($line['password'] == $user){
                $fio = $line['FIO'];
                $branch = $line['branch'];
                $pos = $line['position'];
                $subdivision = $line['subdivision'];
                $f3 = fopen(formDataValidation(translit_my('users/'.$fio.'_'.$branch.'_'.$pos.'_'.$subdivision.'.txt')), 'r');
                while(!feof($f3)){
                    $line_2 = fgets($f3, 1600);
                    if(!empty($line_2)){
                        $countQuestions++;
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
        $line = fgets($f6, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            if(array_keys($line)[0] == $user){
                $test_time = date("d.m.Y H:i:s", strtotime('+60 minutes', strtotime($line[$user])));
            }
        }else{
            break;
        }
    }
    fclose($f6);
    $inform = '';
    if($time){
        $inform = '<div class="timeMessage"><strong>Время прохождения теста истекло</strong></strong></div>';
    }

    echo '<!doctype html>
<html lang="en">
<head>
    <title>Тест по Геологии</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="icon" href="icon/geol.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" media="screen" href="style.css">
</head>
<body><main class="baseWidth_3">
    '.$inform.'
<div class="adm_row">
            <div class="col-adm1">
                '.$fio.'<br>
                '.$pos.'<br>
                '.$subdivision.'<br>
                '.$branch.'<br>
            </div>
            <div class="col_adm2">
                <div class="result">
                    <div class="date"> Время завершения теста: '.$test_time.'</div>
                    <div class="rating">Количество ответов: '.($countQuestions).'</div>
                    <div class="rating">Допущено ошибок: '.($countQuestions - $user_errors).'</div>
                </div>
            </div>
        </div>
    </main>
';
}

function myRandom($min, $max, $exception){
    $digit = rand($min, $max);
    if(!empty($exception)){
        foreach ($exception as $key => $value) {
            if($digit != $value){
            }else{
                myRandom($min, $max, $exception);
            }
        }
        return $digit;
    }else{
        return rand($min, $max);
    }
}

function value_pairs(){
    $value_pairs = [];
    $file_11 = fopen('q_a.txt', 'r');
    while(!feof($file_11)){
        $line = fgets($file_11, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            $value_pairs[$line['numberOfQuestion']] = $line['section'];
        }else{
            break;
        }
    }
    return $value_pairs;
}

function selectionQuestion($mustHaveQuestions, $value_pairs, $wasQuestion){
    $massive = array();
    foreach ($value_pairs as $key=>$value){
        if(!in_array($key, $wasQuestion['questionsNumbers'])){
            if($wasQuestion[$value] < $mustHaveQuestions[$value]){
                $massive[] = $key;
            }
        }
    }
    //print_r($wasQuestion);
    return $massive[array_rand($massive)];
};

function wasQuestions($user) {
    $fileName = usersFileName($user);
    //echo $fileName.'<br>';
    $wasQuestions = [
        'questionsNumbers' => array(),
        'Drill' => 0,
        'doc' => 0,
        'geology' => 0,
        'GIS' => 0,
        'Purchase' => 0,
        'wellTest' => 0
    ];
    $openFile = fopen($fileName, 'r');
    while(!feof($openFile)){
        $line = fgets($openFile, 1600);
        if(!empty($line)){
            $line = json_decode($line, true);
            foreach ($wasQuestions as $key=>$value){
                if($line['section'] == $key){
                    $wasQuestions[$key] += 1;
                    $wasQuestions['questionsNumbers'][] = $line['numberOfQuestion'];
                }
            }
        }else{
            fclose($openFile);
            break;
        }
    }
    return $wasQuestions;
}

function qa_init($user){
    $mustHaveQuestions = [
        'Drill' => 5,
        'doc' => 5,
        'geology' => 5,
        'GIS' => 5,
        'Purchase' => 5,
        'wellTest' => 5
    ];
    $timeout = false;
    $allQquestion_count = question_count();
    $question_count = array_sum($mustHaveQuestions);
    $current_question = current_question($user);
    if($question_count >= $current_question){
        if(is_time_over($user)){
            $valuePairs = value_pairs();
            $wasQuestions = wasQuestions($user);
            $recordFile = 'auxiliary/'.usersFileName($user);
            $recordFile_2 = '2_'.$recordFile;
            $recordNumber = 0;
            $selectedQuestion = selectionQuestion($mustHaveQuestions, $valuePairs, $wasQuestions);
            if(!file_exists($recordFile)){
                $auxiliary = fopen($recordFile, 'a');
                fwrite($auxiliary, $recordNumber+1);
                fclose($auxiliary);
                $auxiliary_2 = fopen($recordFile_2, 'a');
                fwrite($auxiliary_2, $selectedQuestion);
                fclose($auxiliary_2);
            }else{
                $auxiliary = fopen($recordFile, 'r');
                $recordNumber = fgets($auxiliary, 1024);
                fclose($auxiliary);
            }
            if($recordNumber + 1 == $current_question){
                $auxiliary_2 = fopen($recordFile_2, 'w');
                fwrite($auxiliary_2, $selectedQuestion);
                fclose($auxiliary_2);
                $auxiliary = fopen($recordFile, 'w');
                fwrite($auxiliary, $current_question);
                fclose($auxiliary);
            }else{
                $auxiliary_2 = fopen($recordFile_2, 'r');
                $selectedQuestion = fgets($auxiliary_2, 1024);
                fclose($auxiliary_2);
            }
            $endTime = end_time($user);
            $question = question($selectedQuestion);
            $user_ansewr_1 = user_ansewr_1($selectedQuestion);
            $user_ansewr_2 = user_ansewr_2($selectedQuestion);
            $user_ansewr_3 = user_ansewr_3($selectedQuestion);
            $user_ansewr_4 = user_ansewr_4($selectedQuestion);
            $questionSection = questionSection($selectedQuestion);
            record($_POST, $user);
            echo '<!doctype html>
<html lang="en">
<head>
    <title>Тест по Геологии</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" >
    <link rel="icon" href="icon/geol.ico" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" media="screen" href="style.css">
</head>
<body><main class="baseWidth_2">
    <form class="protoWidth_2" method="post">
        <div class="mainFormLegend">Тест Управления геологии, испытания и КРС</div>
        <div class="numberOfQuestion tetxtCentr">Вопрос №'.$current_question.'</div>
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
        <input type="hidden" name="section" value='.$questionSection.'>
        <input type="hidden" name="numberOfQuestion" value='.$selectedQuestion.'>
        <div class="forTime">
            <button type="submit" class="btn btn-primary startButton" name="password" value='.$_GET['user'].'>Ответить</button>
            <div id="timer">
                <div class="time_question_1">
                    <div class="hide hideTime" data-tmr="'.$endTime.'"></div>
                    Время до завершения теста:&nbsp
                    <i>минут:&nbsp </i><span id="minutes"></span>&nbsp<i>секунд:&nbsp </i><span id="seconds"></span>
                </div>
                <div class="time_question_2">Осталось вопросов: '.($question_count + 1 - $current_question).'</div>
            </div>
        </div>
    </form>

</main>';
        }else{
            $hir2 = formDataValidation($user);
            $timeout = true;
            header('location:?page=result&user='.$hir2.'&timeout='.$timeout);
        }
    }else{
        $hir2 = formDataValidation($user);
        header('location:?page=result&user='.$hir2.'&timeout='.$timeout);
    }
}