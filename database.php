<?php

class Database
{
    private $host;
    private $user;
    private $password;  
    private $database;
    private $charset;
    private $db;

 

    public function __construct($host, $user, $password, $database, $charset)
    {

        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        try {
            $dsn = "mysql:host=localhost;$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($dsn, $this->user, $this->password);
            echo "connected!";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("connection failed");
        }
    }

    public function executeQuery(){
        $fname = $_POST['fname'];
        $insertion = $_POST['insertion'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];
        $usrname = $_POST['username'];
        $passwrd = $_POST['password'];
        $hash = md5($passwrd);
            $sql = "INSERT INTO project1 (first_name, insertion, last_name, email, username, password) VALUES ($fname, $insertion, $lname, $email, $usrname, $hash) ";
            
            $statement = $this->db->prepare($sql);
            $statement->execute(array($sql)); 
            $statement->fetch();       
    }
}

?>