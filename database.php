<?php

class database{

    private $host;
    private $username;
    private $password;
    private $database;
    private $charset;
    private $dbh;
    
    // create class constants (admin and user)
    const ADMIN = 1;
    const USER = 2;

    public function __construct($host, $username, $password, $database, $charset){
        $this->host = $host; //localhost
        $this->username = $username; //root
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;

        try{
            // DSN connection method
            /*
            - mysql driver
            - host (localhost/127.0.0.1)
            - database (schema) name
            - charset
            */
            $dsn = "mysql:host=$this->host;dbname=$this->database;charset=$this->charset";
            $this->dbh = new PDO($dsn, $this->username, $this->password);

            // echo "Database connection successfully established"; -> not nescessary to show this on the website!

        }catch(PDOException $e){
            // die and exit are equivalent
            // exit-> Output a message and terminate the current script
            die("Unable to connect: " . $e->getMessage());
        }
    }

    private function is_new_account($username){
        
        $stmt = $this->dbh->prepare('SELECT * FROM account WHERE username=:username');
        $stmt->execute(['username'=>$username]);
        $result = $stmt->fetch();

        if(is_array($result) && count($result) > 0){
            return false;
        }

        return true;
    }

    private function is_admin($username){
        $sql = "SELECT type_id FROM account WHERE username = :username";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute(['username'=>$username]);

        // result is an associative array (key-value pair)
        $result = $stmt->fetch();
        
        if($result['type_id'] == self::ADMIN){
            return true;
        }

        // user is not admin
        return false;
    }

    private function create_or_update_account($id, $type_id, $username, $email, $password){
        $updated_at = date('Y-m-d H:i:s');

        if(is_null($id)){
        //todo: is null check ->update/insert

        // hash password to ensure password safety 
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO account VALUES (NULL, :type_id, :username, :email, :password, :created, :updated)";

        $statement = $this->dbh->prepare($sql);

        $statement->execute([
            'type_id'=>$type_id,
            'username'=>$username, 
            'email'=>$email, 
            'password'=>$hashed_password, 
            'created'=> date('Y-m-d H:i:s'), 
            'updated'=> $updated_at
        ]);
        
        $account_id = $this->dbh->lastInsertId();
        echo 'account_id is '. $account_id;
        return $account_id;

        }else{

            $sql = "
                UPDATE account 
                SET 
                    type_id = :usertype_id, 
                    username = :uname, 
                    email = :email, 
                    updated_at = :updated 
                WHERE id = :id
            ";

            $statement = $this->dbh->prepare($sql);

            $statement->execute([
                'usertype_id'=>$type_id, 
                'uname'=>$username, 
                'email'=> $email, 
                'updated'=> $updated_at,
                'id'=>$id
            ]);
            
            // todo: step here?
            $account_id = $this->dbh->lastInsertId();
            return $account_id;
        }
    }

    private function create_or_update_persoon($id, $account_id, $fname, $mname, $lname){
        $updated_at = date('Y-m-d H:i:s');
        echo 'id is '. $id;

        if(is_null($id)){
            $sql = "INSERT INTO person VALUES (NULL, :account_id, :firstname, :middlename, :lastname, :created, :updated)";

            $statement = $this->dbh->prepare($sql);
    
            $statement->execute([
                'account_id'=>$account_id, 
                'firstname'=>$fname, 
                'middlename'=>$mname, 
                'lastname'=> $lname, 
                'created'=> date('Y-m-d H:i:s'),
                'updated'=> $updated_at
            ]);
            
            $person_id = $this->dbh->lastInsertId();
            echo 'person id is '. $person_id;
            return $person_id;

        } else{

            $sql = "
                UPDATE person 
                SET 
                    first_name = :firstname, 
                    middle_name = :middlename, 
                    last_name = :lastname, 
                    updated_at = :updated 
                WHERE id = :id";

            $statement = $this->dbh->prepare($sql);
    
            $statement->execute([
                'id'=>$id,
                'firstname'=>$fname, 
                'middlename'=>$mname, 
                'lastname'=> $lname, 
                'updated'=> $updated_at
            ]);
            
            // todo: step here?
            $person_id = $this->dbh->lastInsertId();
            return $person_id;

        }
    }

    public function updateUser($account_info, $persoon_info){

        if(is_array($account_info) && is_array($persoon_info)){
            try{
                $this->dbh->beginTransaction();

                $id = $account_info['account_id'];
                $type_id = $account_info['type_id']; 
                $username = $account_info['username']; 
                $email = $account_info['email']; 

                $account_id = $this->create_or_update_account($id, $type_id, $username, $email, NULL);
                
                $id = $persoon_info['person_id'];
                $fname = $persoon_info['first_name'];
                $mname = $persoon_info['middle_name'];
                $lname = $persoon_info['last_name'];
                $this->create_or_update_persoon($id, $account_id, $fname, $mname, $lname);

                $this->dbh->commit();
                return 'User data succesfully updated';
            }catch(Exception $e){
                $this->dbh->rollback();
                echo 'Error occurred: '.$e->getMessage();
            }
            
        }
        return 'Account - and/or person information should be supplied as an array';
    }

    public function sign_up($username, $type_id, $firstname, $mname, $lastname, $email, $password){
        // THIS METHOD IS DONE!
        try{
           // create a database transaction
            $this->dbh->beginTransaction();

            // make sure to check if it's a non-existing user
            if(!$this->is_new_account($username)){
                return "Username already exists. Please pick another one, and try again.";
            }

            // insert into table account first since person has a fk to account(id)
            $account_id = $this->create_or_update_account(NULL, $type_id, $username, $email, $password);
            $this->create_or_update_persoon(NULL, $account_id, $firstname, $mname, $lastname);

            // commit database changes
            $this->dbh->commit();

            // check if there's a session (created in login, should only visit here in case of admin)
            if(isset($_SESSION) && $_SESSION['usertype'] == self::ADMIN){
                return "New user has been succesfully added to the database";
            }

            // user gets redirected to login if method is not called by admin. 
            header('location: index.php');
            // exit makes sure that further code isn't executed.
            exit;
       }catch(Exception $e){
            // rollback database changes in case of an error to maintain data integrity.
            $this->dbh->rollback();
            echo "Signup failed: " . $e->getMessage();
       }
    }

    public function login($username, $password){
        // get id, usertype_id and password from account
        $sql = "
            SELECT 
                a.id as account_id, 
                p.id as person_id, 
                a.type_id, 
                a.password 
            FROM account as a 
            LEFT JOIN person as p 
            ON p.account_id = a.id 
            WHERE username = :username
        ";

        // prepare returns an empty statement object. there is no data stored in $stmt.
        $stmt = $this->dbh->prepare($sql);
        // execute prepared statement. pass arg, which is an associative array. 
        // key should match replacement field on line 168 (:username)!!
        $stmt->execute(['username'=>$username]);

        // fetch should return an associative array (key, value pair)
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // check $result is an array
        if(is_array($result)){

            // apply count on if $result is an array (and thus if user exists, only existing users should be able to login)
            if(count($result) > 0){

                // get hashed_password from database result with key 'password'
                $hashed_password = $result['password'];
                var_dump( password_verify($password, $hashed_password));

                // verify that user exists and that provided password is the same as the hashed password
                if($username && password_verify($password, $hashed_password)){
                    session_start();
    
                    // store userdata in session variable (=array)
                    $_SESSION['account_id'] = $result['account_id'];
                    $_SESSION['person_id'] = $result['person_id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['usertype'] = $result['type_id'];
                    $_SESSION['loggedin'] = true;
    
                    // check if user is an administrator. If so, redirect to the admin page.
                    // if not administrator, redirect to user page.
                    if($this->is_admin($username)){
                        header("location: welcome_admin.php");
                        //make sure that code below redirect does not get executed when redirected.
                        exit;
                    }

                    // redirect user to the user-page if not admin.
                    header("location: welcome_user.php");
                    exit;
                }else{
                    // returned an error message to show in span element in login form (index.php).
                    return "Incorrect username and/or password. Please fix your input and try again.";
                }
            }
        }else{
            // no matching user found in db. Make sure not to tell the user directly.
            return "Failed to login. Please try again";
        }
    }

    public function getAccountInformation($id){
        $statement = $this->dbh->prepare("SELECT * FROM account WHERE id=:id");
        $statement->execute(['id'=>$id]);
        $account = $statement->fetch(PDO::FETCH_ASSOC);
        return $account;
    }

    public function getPersonInformation($id){
        $statement = $this->dbh->prepare("SELECT * FROM person WHERE id=:id");
        $statement->execute(['id'=>$id]);
        $account = $statement->fetch(PDO::FETCH_ASSOC);
        return $account;
    }

    public function get_user_information($username){

        $sql = "
            SELECT 
                a.id, 
                p.id as person_id,
                u.type, 
                p.first_name, 
                p.middle_name, 
                p.last_name, 
                a.username, 
                a.email 
            FROM person as p 
            LEFT JOIN account as a
            ON p.account_id = a.id
            LEFT JOIN usertype as u
            ON a.type_id = u.id       
        ";

        if($username !== NULL){
            // query for specific user when a username is supplied
            $sql .= 'WHERE a.username = :username';
        }

        $stmt = $this->dbh->prepare($sql);

        // check if username is supplied, if so, pass assoc array to execute
        $username !== NULL ? $stmt->execute(['username'=>$username]) : $stmt->execute();
        
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function deleteUser($account_id, $person_id){
        echo $account_id, $person_id;
        try{
            $this->dbh->beginTransaction();

            $stmt = $this->dbh->prepare("DELETE FROM person WHERE id=:id");
            $stmt->execute(['id'=>$person_id]);

            $stm = $this->dbh->prepare("DELETE FROM account WHERE id=:id");
            $stmt->execute(['id'=>$account_id]);

            $this->dbh->commit();

        }catch(Exception $e){
            $this->dbh->rollback();
            echo 'Error: '.$e->getMessage();
        }
    }
}
?>
