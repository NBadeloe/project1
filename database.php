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

        //make db connection
        try {
            $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->db = new PDO($dsn, $this->user, $this->password);
            echo "connected!";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("connection failed");
        }
    }

    public function insert($fname, $insertion, $lname, $email, $usrname, $hash){
            $this->db->beginTransaction();

            //insert in account
            $sql = "INSERT INTO account (email, password) 
                VALUES (:email, :password) ";
            $statement = $this->db->prepare($sql);
            $statement->execute(['email' => $email, 'password' => $hash]);

            //get account_id
            $acc_id = $this->db->lastInsertId();

            //insert in person
            $sql = "INSERT INTO person (account_id, first_name, insertion, last_name, email, username, password) 
                VALUES (:account_id, :first_name, :insertion, :last_name, :email, :username, :password) ";
            $statement = $this->db->prepare($sql);
            $statement->execute(['account_id'=> $acc_id, 'first_name'=> $fname , 'insertion' => $insertion, 'last_name' => $lname , 'email' => $email, 'username' => $usrname, 'password' => $hash]); 
            $this->db->commit();       
    }
}

?>