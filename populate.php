<?php

// IMPORTANT! EXECUTE FILE ONCE AT THE BEGINNING OF THE APPLICATION!
include 'database.php';
$db = new database('localhost', 'root', '', 'project1', 'utf8');

// call the sign_up method from the database class
$db->sign_up('admin', $db::ADMIN, 'peter', NULL, 'boom', 'p.boom@test.com', 'admin');

?>