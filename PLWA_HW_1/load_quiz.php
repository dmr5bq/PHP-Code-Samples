<?php
    session_start();

    /* This file chooses the correct filename for the quiz based on the day of the week and then builds the quiz table from the file
        Then, the play_quiz file is launched and the session is updated to hold the quiz table */

    // $quiz_table is an array of arrays
    // $quiz_table[<index>][1] is an array of potential answers
    
    $u = $_SESSION['username'];
    $wd = $_SESSION['weekday'];
    $qtd = 'quiz_taken_day';

    if (isset($_COOKIE[$u])) {
        header('location: index.php');
    }

    if (!isset($_COOKIE[$u])) {
        setcookie($u, "taken_quiz");
    }

    if (!isset($_COOKIE[$qtd])) {
        setcookie($qtd, $wd);
    }

    if (!isset($_COOKIE[$u])) {
        $day = $wd;
        $_SESSION['number_right'] = 0;
        $_SESSION['number_wrong'] = 0;
        $quiz_filename = 'test_quiz.txt';
        $_SESSION['question_index'] = 0;

        $quiz_filename = 'quiz'.$day.'.txt';

        $_SESSION['quiz_file'] = $quiz_filename;

        $quiz_lines = file($quiz_filename); 

        $index = 0;

        $quiz_table = array();

        foreach ($quiz_lines as $current_line) {
            $quiz_table[$index] = explode('#', $current_line);
            $quiz_table[$index][1] = explode(':', $quiz_table[$index][1]);
            $index++;
        }

        $_SESSION['quiz_data'] = $quiz_table;

        header('location: play_quiz.php');
    } else {
        header('location: index.php');
    }
?>