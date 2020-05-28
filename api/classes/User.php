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

			// HASH PASSWORD USING BCRYPT ALGO
			$this->password = password_hash($this->password, PASSWORD_BCRYPT);

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


		public function update(){

			$query = "UPDATE " . $this->tbl_name . 
			" SET lastname = :lastname,
			firstname = :firstname,
			email = :email,
			password = :password
			WHERE id = :id";

			$stmt = $this->conn->prepare( $query );

			$this->id = htmlspecialchars( strip_tags( $this->id ));
			$this->lastname = htmlspecialchars( strip_tags( $this->lastname ));
			$this->firstname = htmlspecialchars( strip_tags( $this->firstname ));
			$this->email = htmlspecialchars( strip_tags( $this->email ));
			$this->password = htmlspecialchars( strip_tags( $this->password ));

			// HASH PASSWORD USING BCRYPT ALGO
			$this->password = password_hash($this->password, PASSWORD_BCRYPT);

			$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':lastname', $this->lastname);
			$stmt->bindParam(':firstname', $this->firstname);
			$stmt->bindParam(':email', $this->email);
			$stmt->bindParam(':password', $this->password);

			if( $stmt->execute() ){
				return true;
			}

			$this->showSqlError($stmt);
			return false;

		}
		

		public function isEmailExist(){

			$query = "SELECT * FROM " . $this->tbl_name . 
			" WHERE email=? LIMIT 0,1";

			$stmt = $this->conn->prepare( $query );

			$this->email = htmlspecialchars( strip_tags( $this->email ) );

			$stmt->bindParam(1, $this->email);

			$stmt->execute();

			if( $stmt->rowCount() > 0 ) {

				$row = $stmt->fetch(); 

				$this->id = $row['id']; 
				$this->lastname = $row['lastname'];
				$this->firstname = $row['firstname'];
				$this->password = $row['password'];
				$this->created = $row['created'];

				return true;

			}

			return false;
		
		}


		public function showSqlError($stmt){ 
			echo "<pre>";
				print_r($stmt->errorInfo);
			echo "</pre>";
		}



	}
