<?php
    session_start();
    
    /*
        AUTHENTICATION script
        
        uses users.txt
    */
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    $qtd = 'quiz_taken_day';

    check_cookies();
    
    session_store($username, $password);    

    $username = rtrim($username);
    $password = rtrim($password);
    
    $login_success = false;
    $login_failure = false;
    
    $file_array = file('users.txt');

    foreach ($file_array as $line):
        $tmp_array = explode('#', $line);

        $file_username = $tmp_array[0];
        $file_password = $tmp_array[1];

        $file_username = rtrim($file_username);
        $file_password = rtrim($file_password);
        
        $login_success = validate($username, $password, $file_username, $file_password);

        if ($login_success) {
            if ( count($_POST) > 2 ) {
                setcookie('cookie_remember_me', $username."#".$password."#".time(), time() + 60000);
            }
            header('location: load_quiz.php');
        }
    endforeach; 

    if ($login_success) {
        $_SESSION['failed_login'] = 1;
        header('location: index.php');
    }
        
    function validate($username, $password, $file_username, $file_password) {
        if ( strcmp($file_username, $username) == 0 ) {
            if ( strcmp($file_password, $password) == 0 ) {
                $login_success = true;
            }
        } else {
            $login_success = false;
        }
        return $login_success;
    }

    function check_cookies() {
        if (isset($_COOKIE[$qtd])) {
            if ($_COOKIE[$qtd] != $_SESSION['weekday']) {
                unset($_COOKIE[$qtd]);
                unset($_COOKIE['quiz_taken']);
                session_destroy();
            }
        }
    }

    function session_store($username, $password) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
    }
?>