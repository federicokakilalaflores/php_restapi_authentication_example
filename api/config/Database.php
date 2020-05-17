<?php
	
	class Database {

		private $host = "127.0.0.1";
		private $username = "admin";
		private $password = "admin";
		private $dbname = "api_db";
		private $charset = "utf8mb4";
		private $conn = null;

		public function connect(){

			try{

				$this->conn = new PDO(
					"mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=" . 
					$this->charset,
					$this->username,
					$this->password
				);

				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

			}catch(\PDOException $e){
				echo "Connection error: " . $e->getMessage();
			}

			return $this->conn;

		}

	}

?>