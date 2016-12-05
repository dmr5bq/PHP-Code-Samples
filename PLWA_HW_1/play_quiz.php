<?php
    session_start();
     
    if (!isset($_SESSION['question_index'])) {
        $_SESSION['question_index'] = 0;
    }
    
    $quiz_table = $_SESSION['quiz_data'];
    $current_index = $_SESSION['question_index'];
    $number_of_questions = count($quiz_table);

    if ( $current_index >= $number_of_questions ) { // end of quiz handling
         header('location: quiz_end.php');
    } else {
        generate_question_table($quiz_table[$current_index]);
    }
    
    function generate_question_table($question_entry) {
        
        echo '
        <h4>'.$question_entry[0].'</h4>
        <form method="post" action="process_question.php">
            <table>
                '.generate_all_input_lines($question_entry).'
                <tr><td><input type="number" name="input"></td></tr>
                <tr><td><button type="submit">Check answer</button></td></tr>
            <table>
        </form>
        ';
    }

    function generate_single_line($option_number, $option_text) {
       echo '
                <td><br>
                ('.$option_number.') '.$option_text.'</td>
            ';
    }

    function generate_all_input_lines($question_entry) {
        $option_list = $question_entry[1];
        
        $option_index = 1;
        
        foreach ($option_list as $option_text) {
            echo '  
                    <tr>'.
                        generate_single_line($option_index, $option_text)
                    .'</tr>'
                ;
            $option_index++;
        } 
        
    }
?>