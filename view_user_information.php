<?php
include 'database.php';

//initialize session
session_start();
?>

<html>
    <head>
        <title>Personal data</title>
         <!-- include css file -->
         <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <!-- the menu -->
        <div class="topnav">
            <a class="active" href="welcome_user.php">Home</a>
            <a href="view_user_information.php">Show profile details</a>
            <a href="logout.php">Logout</a>
        </div>

        <?php

        $db = new database('localhost', 'root', '', 'project1', 'utf8');
        // show_profile_details_user returns an associative array (get first index first)
        $result_set = $db->get_user_information($_SESSION['username'])[0];

        // result_set is an associative array, get keys with array_keys and values with array_values.
        $columns = array_keys($result_set);
        $row_data = array_values($result_set);

        // table starts here
        echo "<table>";
            // table row
            echo "<tr>";
                // loop all available columns, and store them in the top of the table (bold)
                foreach($columns as $column){
                    // table header
                    echo "<th><strong> $column </strong></th>";
                }
            echo "</tr>";

            // table rows. this part contains the data which will be shown in the table
            echo "<tr>";
                foreach($row_data as $value){
                    // table data
                    echo "<td> $value </td>";
                }
            echo "</tr>";
        echo "</table>"
        ?>
    </body>
</html>