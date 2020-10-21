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
            // echo "connected!";
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit("connection failed");
        }
    }

    public function insert($fname, $insertion, $lname, $email, $usrname, $hash){
          $created_at = $updated_at = date('Y-m-d H:i:s');
            $this->db->beginTransaction();

            //insert in account
            $sql = "INSERT INTO account (email, password, type, created_at, updated_at) 
                VALUES (:email, :password, :type, :created_at, :updated_at) ";
            $statement = $this->db->prepare($sql);
            $statement->execute(['email' => $email, 'password' => $hash, 'type' => 1, 'created_at' => $created_at, 'updated_at' => $updated_at]);

            //get account_id
            $acc_id = $this->db->lastInsertId();

            //insert in person
            $sql = "INSERT INTO person (account_id, first_name, insertion, last_name, created_at, updated_at) 
                VALUES (:account_id, :first_name, :insertion, :last_name, :created_at, :updated_at) ";
            $statement = $this->db->prepare($sql);
            $statement->execute(['account_id'=> $acc_id, 'first_name'=> $fname , 'insertion' => $insertion, 'last_name' => $lname , 'created_at' => $created_at, 'updated_at' => $updated_at]); 
            $this->db->commit();       
    }
}


?>