<?php 
    session_start();

    $total_right = 0;
    $total_wrong = 0;
    $quiz_file = '';

    /*
    if (isset($_COOKIE['quiz_taken'])) {
        if (strcmp($_COOKIE['quiz_taken'], $_SESSION['username'] == 0)) {
            header('location: index.php');
        }
    }*/


    // poll the session variables
    if (isset($_SESSION['number_right'])) {$total_right = $_SESSION['number_right'];}
    if (isset($_SESSION['number_wrong'])) {$total_wrong = $_SESSION['number_wrong'];}
    if (isset($_SESSION['quiz_file'])) { $quiz_file =   $_SESSION['quiz_file']; }
    
    // locals
    $quizzes_file = 'quizzes.txt';


    // open quizzes file to get lines
    // store lines in an array
    
    $fileptr_rmode = fopen($quizzes_file, 'r');
    // check each line to see if the quiz filename matches the first element in explode('#', $line);

    $array_of_lines = array();
    $line_number = 0;

    while(!feof($fileptr_rmode)) {
        $array_of_lines[$line_number] = fgets($fileptr_rmode);
        $line_number++;
    }

    $array_of_lines = file($quizzes_file);
    fclose($fileptr_rmode);
    
    $selected_line_number = -1;
    $array_of_exploded_lines = array();
    /* KEY TO A_O_E_L 
            $a_o_e_l[index][0] -> Quiz Name
            $a_o_e_l[index][1] -> Number of Questions
            $a_o_e_l[index][2] -> Number of Takers
            $a_o_e_l[index][3] -> Total Correct
            $a_o_e_l[index][4] -> Total Incorrect
    */ 

    $line_number = 0;
    foreach ($array_of_lines as $line) {
        $array_of_exploded_lines[$line_number] = explode('#', $line);
        if ( strcmp($quiz_file, $array_of_exploded_lines[$line_number][0]) == 0 ) {
            $selected_line_number = $line_number;
        }
        $line_number++;
    }
    
    // update the selected index with the new information
if($selected_line_number != -1) {
    
    // wipe file clean
    $fileptr_wmode = fopen($quizzes_file, 'w');
    fwrite($fileptr_wmode,'');
    fclose($fileptr_wmode);
    
    // update each field that needs an update:
    // no. of takers
    $current_takers = $array_of_exploded_lines[$selected_line_number][2];
    $current_takers += 1;
    $array_of_exploded_lines[$selected_line_number][2] = (string) ($current_takers);
    
    // number right
    $current_total_right = $array_of_exploded_lines[$selected_line_number][3];
    $array_of_exploded_lines[$selected_line_number][3] = (string) ($current_total_right + $total_right);
    
    // number wrong
    $current_total_wrong = $array_of_exploded_lines[$selected_line_number][4];
    $array_of_exploded_lines[$selected_line_number][4] = (string) ($current_total_wrong + $total_wrong);
    
    //reconcatenate line
    $rebuilt_line = '';
    $exploded_line = $array_of_exploded_lines[$selected_line_number];
    
    foreach ($exploded_line as $line_chunk) {
        $rebuilt_line = $rebuilt_line.$line_chunk.'#';
    }
    $rebuilt_line = rtrim($rebuilt_line, '#');
    $rebuilt_line = $rebuilt_line."\n";
    
    $array_of_lines[$selected_line_number] = $rebuilt_line;
    
    $fileptr_amode = fopen($quizzes_file, 'a');
    
    foreach($array_of_lines as $write_line) {
        fwrite($fileptr_amode, $write_line);
    }
    
    fclose($fileptr_amode);
    
    echo '<p>You got a total of '.(string)(($total_right * 100)/($total_right + $total_wrong)).'% of the answers correct!</p>';

    echo '<p>Across all users, the average score was '.(string)(($current_total_right + $total_right) * 100)/(($current_total_right + $total_right + $total_wrong + $current_total_wrong)).'% correct.</p>';
    
    echo '<br><br>You may take the next quiz tomorrow. Check back for more!';
}
    
    echo '<a href="index.php">Go Home.</a>';
    
    // Destroy the session
    session_destroy();
    
?>  

