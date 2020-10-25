<?php

include 'database.php';
include 'helper.php';

// initialize the session
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: index.php');
    exit;
}

$db = new database('localhost', 'root', '', 'project1', 'utf8');
$obj = new Helper();

// form would initially be used to add user. therefore, update should default to false.
$update_user = false;

// we get redirected to add_new_user.php when admin clicks 'edit' in the table
// to this page, we pass a value, which we retrieve the user_id from
if(isset($_GET['user_id']) && isset($_GET['person_id'])){

    $update_user = true;

    $user_id = $_GET['user_id'];
    $account_info = $db->getAccountInformation($_GET['user_id']);

    // account information
    $username = $account_info['username'];
    $email = $account_info['email'];
   
    $person_id = $_GET['person_id'];
    $person_info = $db->getPersonInformation($person_id);

    // person information
    $fname = $person_info['first_name'];
    $mname = $person_info['middle_name'];
    $lname = $person_info['last_name'];
}
// checks if posted form is a new entry or update of existing entry
$nameAttr = 'submit';

if(isset($_POST['update'])){
    $nameAttr = 'update';
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST[$nameAttr]) && !empty($_POST[$nameAttr])){
    // array with values of the name attribute of the form (required fields)
    $fields = [
        'type_id', 'uname', 'email', 'pwd', 'fname', 'lname'
    ];
   
    $fields_validated = $obj->field_validation($fields);

    if($fields_validated){
        // account
        $type_id = $_POST['type_id'];
        $uname = trim(strtolower($_POST['uname']));
        $email = trim(strtolower($_POST['email']));
        $pwd = trim(strtolower($_POST['pwd']));

        // person
        $fname = trim(strtolower($_POST['fname']));
        $mname = isset($_POST['mname']) ? trim(strtolower($_POST['mname'])) : NULL; //nullable
        $lname = trim(strtolower($_POST['lname']));

        if($nameAttr == 'submit'){
            echo 'in submit';
            $msg = $db->sign_up($uname, $type_id, $fname, $mname, $lname, $email, $pwd);
            echo 'after msg';
        }else{
            echo 'in update';
            $account = [
                'account_id'=>$_POST['user_id'],
                'type_id'=>$_POST['type_id'], 
                'username'=>$_POST['uname'], 
                'email'=>$_POST['email']
            ];

            $persoon = [
                'person_id'=>$_POST['persoon_id'],
                'first_name'=>$_POST['fname'], 
                'middle_name'=>$_POST['mname'], 
                'last_name'=>$_POST['lname']
            ];

            $update_msg = $db->updateUser($account, $persoon);
            sleep(3);
            header('location: view_edit_delete.php');
            exit;
        }
    }else{
        $missingFieldError = "Input for one of more fields missing. Please provide all required values and try again.";
    }
}
?>

<html>
    <head>
        <title>Welcome!</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="topnav">
            <a class="active" href="welcome_admin.php">Home</a>
            <a href="add_new_user.php">Add user</a>
            <a href="view_edit_delete.php">View, edit and/or delete user</a>
            <a href="logout.php">Logout</a>
        </div>
        <form action="add_new_user.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">
            <input type="hidden" name="persoon_id" value="<?php echo isset($_GET['person_id']) ? $_GET['person_id'] : ''; ?>">
            
            <h1> Account details </h1>
            <select name='type_id' id='type_id'>
                <option value=1>Admin</option>
                <option value=2>User</option>
            </select><br>
            <input type="text" id="uname" name="uname" placeholder="Gebruikersnaam" value="<?php if(isset($_POST["uname"])){ echo htmlentities($_POST["uname"]);}elseif($update_user){echo $username;}else{echo '';}; ?>" required /><br>
            <input type="email" id="email" name="email" placeholder="Email" value="<?php if(isset($_POST["email"])){ echo htmlentities($_POST["email"]);}elseif($update_user){echo $email;}else{echo '';}; ?>" required /><br>
            <input type="password" id=pwd" name="pwd" placeholder="Password" value='helloworld' <?php if($update_user){?> hidden <?php } ?> required /><br>

            <h1> Person details </h1>
            <input type="text" id="fname" name="fname" placeholder="Voornaam" value="<?php if(isset($_POST["fname"])){ echo htmlentities($_POST["fname"]);}elseif($update_user){echo $fname;}else{echo '';}; ?>" required /><br>
            <input type="text" id="mname" name="mname" placeholder="Tussenvoegsel" value="<?php if(isset($_POST["mname"])){ echo htmlentities($_POST["mname"]);}elseif($update_user){echo $mname;}else{echo '';}; ?>"/><br>
            <input type="text" id="lname" name="lname" placeholder="Achternaam" value="<?php if(isset($_POST["lname"])){ echo htmlentities($_POST["lname"]);}elseif($update_user){echo $lname;}else{echo '';}; ?>" required /><br>
            
            <span class='succes'><?php echo ((isset($update_msg) && $update_msg != '') ? htmlentities($update_msg) ." <br>" : '')?></span>
            <span class='succes'><?php echo ((isset($msg) && $msg != '') ? htmlentities($msg) ." <br>" : '')?></span>
            <input type="submit" name="<?php if($update_user){echo 'update';}else{echo 'submit';}?>" value="<?php if($update_user){echo 'Update';}else{echo 'Add user';}?>" />
        </form>
        
    </body>
</html>