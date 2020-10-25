<?php
// initialize the session
session_start();

// check if the user is logged in. redirect to login if not the case.
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header('location: index.php');
    exit;
}
?>

<html>
    <head>
        <title>Welcome</title>
    </head>
    <body>
        <div class="topnav">
            <a class="active" href="welcome_admin.php">Home</a>
            <a href="add_new_user.php">Add user</a>
            <a href="view_edit_delete.php">View, edit and/or delete user</a>
            <a href="logout.php">Logout</a>
        </div>
        <!-- make sure to encode to avoid loading any script -->
        <?php echo "Welcome " . htmlentities( $_SESSION['username']) ."!" ?>
    </body>
</html>