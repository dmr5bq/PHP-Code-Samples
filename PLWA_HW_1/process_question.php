<?php 
session_start();

// check if the answer choice was right
$quiz_table = $_SESSION['quiz_data'];
$current_question_number = $_SESSION['question_index'];
$correct_answer_index = $quiz_table[$_SESSION['question_index']][2];
$max_options = count($quiz_table[$_SESSION['question_index']][1]);
$input_answer_index = (integer)($_POST['input']); //default

if ($input_answer_index <= 0 || $input_answer_index > $max_options) {
    header('location: play_quiz.php');
}

// update the session variables for number_wrong or number_right
echo $input_answer_index."<br>".$correct_answer_index;
if ($input_answer_index == $correct_answer_index) {
    echo'added 1 to number_right';
    $_SESSION['number_right']++;
} else if ($input_answer_index != $correct_answer_index) {
    echo 'added 1 to number wrong';
    $_SESSION['number_wrong']++;
}

// update question_index
$_SESSION['question_index'] += 1;

// jump back
header('location: play_quiz.php');
?>

