<?php

namespace App\Model;


use PDO;
use PDOException;



class User
{
   
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }



	public function create(){
	    $query = "INSERT INTO " . $this->table_name . "
	            SET
	                firstname = :firstname,
	                lastname = :lastname,
	                email = :email,
	                password = :password";
	 
	    // prepare the query
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->firstname =htmlspecialchars(strip_tags($this->firstname));
	    $this->lastname  =htmlspecialchars(strip_tags($this->lastname));
	    $this->email     =htmlspecialchars(strip_tags($this->email));
	    $this->password  =htmlspecialchars(strip_tags($this->password));
	 
	    // bind the values
	    $stmt->bindParam(':firstname', $this->firstname);
	    $stmt->bindParam(':lastname', $this->lastname);
	    $stmt->bindParam(':email', $this->email);
	 
	    // hash the password before saving to database
	    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
	    $stmt->bindParam(':password', $password_hash);
	 
	    // execute the query, also check if query was successful
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}


	public function emailExists(){
 
	    // query to check if email exists
	    $query = "SELECT id, firstname, lastname, password
	            FROM " . $this->table_name . "
	            WHERE email = ?
	            LIMIT 0,1";
	 
	    // prepare the query
	    $stmt = $this->conn->prepare( $query );
	 
	    // sanitize
	    $this->email=htmlspecialchars(strip_tags($this->email));
	 
	    $stmt->bindParam(1, $this->email);	 
	    $stmt->execute();
	    $num = $stmt->rowCount();

	    if($num>0){
	        $row = $stmt->fetch(PDO::FETCH_ASSOC);
	        $this->id = $row['id'];
	        $this->firstname = $row['firstname'];
	        $this->lastname  = $row['lastname'];
	        $this->password  = $row['password'];
	        return true;
	    }
	 
	    // return false if email does not exist in the database
	    return false;
	}


	public function update(){ 
	    // if password needs to be updated
	    $password_set=!empty($this->password) ? ", password = :password" : "";	 
	    // if no posted password, do not update the password
	    $query = "UPDATE " . $this->table_name . "
	            SET
	                firstname = :firstname,
	                lastname = :lastname,
	                email = :email
	                {$password_set}
	            WHERE id = :id";
	 
	    // prepare the query
	    $stmt = $this->conn->prepare($query);
	 
	    // sanitize
	    $this->firstname =htmlspecialchars(strip_tags($this->firstname));
	    $this->lastname  =htmlspecialchars(strip_tags($this->lastname));
	    $this->email     =htmlspecialchars(strip_tags($this->email));
	 
	    // bind the values from the form
	    $stmt->bindParam(':firstname', $this->firstname);
	    $stmt->bindParam(':lastname', $this->lastname);
	    $stmt->bindParam(':email', $this->email);
	 
	    // hash the password before saving to database
	    if(!empty($this->password)){
	        $this->password=htmlspecialchars(strip_tags($this->password));
	        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
	        $stmt->bindParam(':password', $password_hash);
	    }
	 
	    // unique ID of record to be edited
	    $stmt->bindParam(':id', $this->id);
	 
	    // execute the query
	    if($stmt->execute()){
	        return true;
	    }
	 
	    return false;
	}

	 





}
