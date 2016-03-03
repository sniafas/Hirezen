<?php
class Database
{
	
	private	$host = 'localhost';
	private	$db_name = 'hirezen';
	private	$db_username = 'root';
	private	$db_password = 'usher';
    public $conn;
	
	public function dbConnect(){

		try
		{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->db_username, $this->db_password);
			//$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			die("Cannot connect to database: " . $e->getMessage());
		}
		return $this->conn;
	}

	public function setQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}


}


?>