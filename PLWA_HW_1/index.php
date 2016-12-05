<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php
            $day = getdate()['wday'];
            if (isset($_SESSION['username'])) {$username = $_SESSION['username'];}
        
            $_SESSION['weekday'] = $day;
            
            if (isset($_COOKIE['cookie_remember_me']) && !isset($_COOKIE[$_SESSION['username']])) {
                header('location: load_quiz.php');
            } 
        
            if (isset($_SESSION['failed_login'])) {
                echo 'Try again, please. Cannot log in as '. $_SESSION['username'];
                session_destroy();
            }
        ?>
        
        <form method="post" action='login.php'>
            <table>
                <tr>
                    <td>
                        <p>Username: </p>
                    </td>
                    <td>
                        <input type='text' name='username'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Password: </p>
                    </td>
                    <td>
                        <input type='password' name='password'>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="checkbox" name='remember_me'>
                    </td>
                    <td>
                        <p>Remember me next time.</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button type="submit">Log in</button>                 
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>