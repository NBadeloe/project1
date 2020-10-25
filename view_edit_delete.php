<?php
include 'database.php';

//initialize session
session_start();

$db = new database('localhost', 'root', '', 'project1', 'utf8');

if (isset($_GET['user_id']) && isset($_GET['person_id'])) {
    $id = $_GET['user_id'];
    $person_id = $_GET['person_id'];

    $db->deleteUser($id, $person_id);

    // redirect to overview
    header("location: view_edit_delete.php");
    exit;
}

// not my own code, works though...
if(isset($_POST['export'])){
    $filename = "user_data_export.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $print_header = false;

    $result = $db->get_user_information(NULL);
    if(!empty($result)){
        foreach($result as $row){
            if(!$print_header){
                echo implode("\t", array_keys($row)) ."\n";
                $print_header=true;
            }
            echo implode("\t", array_values($row)) ."\n";
        }
    }
    exit;
   


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

        <?php
            $db = new database('localhost', 'root', '', 'project1', 'utf8');
            // admin should be able to see all users. should not filter on user, hence the NULL.
            $results = $db->get_user_information(NULL);
            
            // get the first index of results, which is an associative array.
            $columns = array_keys($results[0]);
            ?>

        <table>
            <thead>
                <tr>
                    <?php foreach($columns as $column){ ?>
                        <th>
                            <strong> <?php echo $column ?> </strong>
                        </th>
                    <?php } ?>
                    <th colspan="2">action</th>
                </tr>
            </thead>
            <?php foreach($results as $rows => $row){ ?>

                <?php $row_id = $row['id']; ?>
                <tr>
                    <?php   foreach($row as $row_data){?>

                    
                        <td>
                            <?php echo $row_data ?>
                        </td>
                    <?php } ?>

                    <td>
                        <a href="add_new_user.php?user_id=<?php echo $row_id; ?>&person_id=<?php echo $row['person_id']?>" class="edit_btn" >Edit</a>
                    </td>
                    <td>
                        <a href="view_edit_delete.php?user_id=<?php echo $row_id; ?>&person_id=<?php echo $row['person_id']?>" class="del_btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <form action='view_edit_delete.php' method='POST'>
            <input type='submit' name='export' value='Export to excel file' />
        </form>
    </body>
</html>