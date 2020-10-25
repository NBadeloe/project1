<?php

include 'database.php';
include 'helper.php';

// check the signup.php file for explanation on code
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){
    $fields = ['uname', 'pwd'];

    $obj2 = new Helper();
    $fields_validated = $obj2->field_validation($fields);

    if($fields_validated){
        $uname = trim($_POST['uname']);
        $password = trim($_POST['pwd']);
        
        $obj = new database('localhost', 'root', '', 'project1', 'utf8');

        // user redirected to welcome page in case of succesful login.
        // unsuccessfull login results in an error message (string)
        $loginError = $obj->login($uname, $password);
    }
}
?>

<html>
    <head>
            <title>Login</title>

            <!-- link cascading style sheet -->
            <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form action="index.php" method="post">
            <input type="text" name="uname" placeholder="Username" required /><br>
            <input type="password" name="pwd" placeholder="Password" required /><br>
            <span><?php echo ((isset($loginError) && $loginError != '') ? $loginError ."<br>" : '')?></span>
            <input type="submit" name='submit' /><br>
            <a href="signup.php" >New user? Sign up!</a><br>
            <a href="lostpwd.php">Forgot password?</a><br>
        </form>
    </body>
</html>
