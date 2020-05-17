<?php

	class User{

		private $tbl_name = 'tbl_users';
		private $conn;

		public $id;
		public $lastname;
		public $firstname;
		public $email;
		public $password;
		public $created;


		public function __construct($conn){
			$this->conn = $conn;
		}


		public function create(){

			$query = "INSERT INTO " . $this->tbl_name . 
			" (lastname, firstname, email, password, created) VALUES (:lastname, :firstname, :email, :password, :created)";

			$stmt = $this->conn->prepare( $query );

			$this->lastname = htmlspecialchars( strip_tags( $this->lastname ) );
			$this->firstname = htmlspecialchars( strip_tags( $this->firstname ) );
			$this->email = htmlspecialchars( strip_tags( $this->email ) );
			$this->password = htmlspecialchars( strip_tags( $this->password ) );
			$this->created = htmlspecialchars( strip_tags( $this->created ) );

			$stmt->bindParam(':lastname', $this->lastname);
			$stmt->bindParam(':firstname', $this->firstname);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':password', $this->password);
			$stmt->bindParam(':created', $this->created);

			if( $stmt->execute() ) {
				return true;
			}

			$this->showSqlError($stmt);
			return false;

		}


		public function showSqlError($stmt){ 
			echo "<pre>";
				print_r($stmt->errorInfo);
			echo "</pre>";
		}



	}