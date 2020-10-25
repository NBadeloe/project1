<?php

include 'database.php';
include 'helper.php';

// check if there's post data and it the form is submitted.
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])){

    // array with values of the name attribute of the form (required fields)
    $fields = [
        'fname', 'lname', 'email', 'uname', 'pwd', 'cpwd'
    ];

    // make an instance of the Helper class
    $obj = new Helper();

    /*
    call the field_validation method from the Helper class
    method returns true when input provided for all required fields 
    */
    $fields_validated = $obj->field_validation($fields);

    // create variables and store user input from form in them
    if($fields_validated){
        /*
        trim strips whitespaces or 
        other characters form the beginning and end of a string: 
        https://www.php.net/manual/en/function.trim.php

        make all input lower case with strtolower()
        */
        $uname = trim(strtolower($_POST['uname']));
        $fname = trim(strtolower($_POST['fname']));

        /*
        check if mname is set, pass NULL if it's not
        the way it's written is a ternary operator: https://www.geeksforgeeks.org/php-ternary-operator/
        */
        $mname = isset($_POST['mname']) ? trim(strtolower($_POST['mname'])) : NULL; //nullable
        $lname = trim(strtolower($_POST['lname']));
        $email = trim(strtolower($_POST['email']));
        $pwd = trim(strtolower($_POST['pwd']));
        $cpwd = trim(strtolower($_POST['cpwd']));

         // check if passwords match, if they do, proceed with signup
        if($pwd !== $cpwd){
            $pwdError = "Passwords do not match. Please fix your input errors and try again.";
        }else{
            // create an instance of the database class
            // provided arguments will be passed on to the constructor!
            $db = new database('localhost', 'root', '', 'project1', 'utf8');

            // call the sign_up method from the database class
            $msg = $db->sign_up($uname, $db::USER, $fname, $mname, $lname, $email, $pwd);
        }
    }else{
        $missingFieldError = "Input for one of more fields missing. Please provide all required values and try again.";
    }
}
?>

<html>
    <head>
        <!-- include css file -->
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form action="signup.php" method="post">
            <input type="text" name="fname" placeholder="Voornaam" value="<?php echo isset($_POST["fname"]) ? htmlentities($_POST["fname"]) : ''; ?>" required /><br>
            <input type="text" name="mname" placeholder="Tussenvoegsel" value="<?php echo isset($_POST["mname"]) ? htmlentities($_POST["mname"]) : ''; ?>"/><br>
            <input type="text" name="lname" placeholder="Achternaam" value="<?php echo isset($_POST["lname"]) ? htmlentities($_POST["lname"]) : ''; ?>" required /><br>
            <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST["email"]) ? htmlentities($_POST["email"]) : ''; ?>" required /><br>
            <input type="text" name="uname" placeholder="Gebruikersnaam" value="<?php echo isset($_POST["uname"]) ? htmlentities($_POST["uname"]) : ''; ?>" required /><br>
            <input type="password" name="pwd" placeholder="Wachtwoord" required /><br>
            <input type="password" name="cpwd" placeholder="Herhaal wachtwoord" required /><br>
            <span>
                <?php 
                    echo ((isset($msg) && $msg != '') ? htmlentities($msg) ." <br>" : '');
                    echo ((isset($pwdError) && $pwdError != '') ? htmlentities($pwdError) ." <br>" : '')
                ?>
            </span>
            <input type="submit" name="submit" value="Sign up!"/>
            <span><?php echo ((isset($missingFieldError) && $missingFieldError != '') ? htmlentities($missingFieldError) : '')?></span>
        </form>
    </body>
</html>
