<?php

include 'database.php';
// $_SERVER["REQUEST_METHOD"] == "POST";
// $fname = $_POST['fname'];
// $insertion = $_POST['insertion'];
// $lname = $_POST['lname'];
// $email = $_POST['email'];
// $usrname = $_POST['username'];
// $passwrd = $_POST['password'];
// $hash = md5($passwrd);

$db = new Database('localhost', 'root', '', 'project1', 'utf8');
$db->executeQuery($fname, $insertion, $lname, $email, $usrname, $hash);

?>
<html>
  <body>
    <h2>sign up</h2>

    <form action="database.php" method="POST">
      <input type="text" id="fname" name="fname" required placeholder="First name"><br>

      <input type="text" id="insertion" name="insertion" placeholder="Insertion"><br> 

      <input type="text" id="lname" name="lname" required placeholder="Last name"><br>

      <input type="text" id="email" name="email" required placeholder="Email"><br>

      <input type="text" id="username" name="username" required placeholder="Username"><br>

      <input type="password" id="password" name="password" required placeholder="password"><br>

      <input type="password" id="repassword" name="repassword" required placeholder="Repeat password"><br>

      <button type="submit">sign up</button>

    </form>
  </body>
</html>