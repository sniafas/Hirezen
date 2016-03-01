<?php
class Database
{
	
	private	$host = 'localhost';
	private	$db_name = 'hirezen';
	private	$db_username = 'root';
	private	$db_password = '***';

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
    
	
	/* TODO 
	function deleteFile($userid,$pdo){

		try{
			$index_file = $pdo->prepare('UPDATE users SET filename = :filename WHERE userid = :userid ');
			$status_file-> bindParam(':userid',$userid);
			$index_file-> bindParam(':filename',"");

			$status_file -> execute();
		
			
			return $curStatus;

		}catch(PDOException $e){
			die("Cannot index the file" . $e->getMessage());
		}
	}
*/





}

















?>
