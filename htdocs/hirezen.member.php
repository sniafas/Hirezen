<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../php/sqlconnect.php');
//public $db_username = apache_getenv('DB_USER');
//public $db_password = apache_getenv('DB_PASS');
class Member
{
    private $conn;
    private  $v=0;

    public function __construct()
    {
      $database = new Database();
      //$db = $database->dbConnect($db_username,$db_password);      
      $db = $database->dbConnect();      
      $this->conn = $db;
    }

    public function runQuery($sql)
    {
      $stmt = $this->conn->prepare($sql);
      return $stmt;
    }

    public function register($name,$surname,$username,$email,$password)
    {
       try
       {
          $n_password = password_hash($password, PASSWORD_DEFAULT);

          $property = 1;
          $status = 0;
          $callFileStatus = 0;
          $reported = 0;
          $newuser_sql = $this->conn->prepare('INSERT INTO users (name,surname,username,email,password,property,status,callFileStatus,reported)  VALUES (:name,:surname,:username,:email,:password,:property,:status,:callFileStatus,:reported)');

          $newuser_sql->bindParam(':name',$name);
          $newuser_sql->bindParam(':surname',$surname);          
          $newuser_sql->bindParam(':username',$username);
          $newuser_sql->bindParam(':email',$email);
          $newuser_sql->bindParam(':password',$n_password);
          $newuser_sql->bindValue(':property',$property);
          $newuser_sql->bindValue(':status',$status);
          $newuser_sql->bindValue(':callFileStatus',$callFileStatus);
          $newuser_sql->bindValue(':reported',$reported);

          $newuser_sql -> execute();


          return $newuser_sql; 
       }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }    
    }

    public function commitLogin($username,$password)
    {


      //prepare query
      try
      { 
        $sql = $this->conn->prepare('SELECT userid,password,property  FROM users WHERE username=  :username');
        $sql->bindParam(':username',$username);
        $sql->execute();
        $userRow=$sql->fetch(PDO::FETCH_ASSOC);

        if($sql->rowCount() > 0)
        {
          if( password_verify($password, $userRow['password']) && $userRow['property'] !=0 )
          {
            
            $_SESSION['userid'] = $userRow['userid'];     //// Session user /////
            $_SESSION['username'] = $username;
            $_SESSION['property'] = $userRow['property'];


            return true;
          }
          else
          {
            return false;
          }
        }
      }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      } 
    }

    public function is_loggedin()
    {
      if(isset($_SESSION['userid']))
      {
         return true;
      }
    }

    public function redirect($url)
    {
      var_dump($url);
      header("Location: $url");
    }

    public function logout()
    {
      session_destroy();
      unset($_SESSION['userid']);
      return true;
    }

    public function getGaugeValue($status)
    {

      switch ($status) {
        case '0':
        $v = 0.0;
          break;
        case '1':
          $v = 0.25;
          break;

        case '2':
          $v = 0.50;
          break;
        
        case '3':
          $v = 0.75;
        break;

        case '4':
          $v = 1.00;
        break;


        default:
          //$actual = 1.0;
          break;
      }
      return $v;
    }

    public function deleteFile($userid){

      try{
        $delete_file = $this->conn->prepare('UPDATE users SET filename = NULL, status="0", callFile = NULL, callFileStatus = "0", signedCallFile = NULL, reported = "0"
                                             WHERE userid = :userid ');
        $delete_file-> bindParam(':userid',$userid);
        $delete_file -> execute();
      
        
        return $delete_file;

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }
    public function uploadFile($username)       // Ανέβασμα Αρχείου
    {


      $path_to_save = './uploads/' . $username . "/";
      define("UPLOAD_DIR", $path_to_save);


      // Eπεργασία αρχείου ανεβάσματος

      if (!empty($_FILES["myFile"])) 
      {

        $myFile = $_FILES["myFile"];

        if ($myFile["error"] !== UPLOAD_ERR_OK) {
          echo "<p>Μεγάλο Μέγεθος Αρχείου.</p>";
          exit;
        }

        // προσωρινή ονομασία
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);

        // προσωρινή αποθήκευση ονομασίας
        $i = 0;
        $parts = pathinfo($name);

        while (file_exists(UPLOAD_DIR . $name)) {
          $i++;
          $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }

        
        $success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
        if (!$success) { 
          echo "<p>Unable to save file.</p>";
          exit;
        }

        // ρυθμιση δικαιωμάτων
        if (chmod(UPLOAD_DIR . $name, 0775)) return true;
      }
    }

    public function setUserUpload($filename,$userid,$status)  // Update User Data when uploads the application
    {


      try{
        $index_file = $this->conn->prepare('UPDATE users SET filename = :filename, status = :status WHERE userid = :userid ');
        $index_file-> bindParam(':filename',$filename);
        $index_file-> bindValue(':status',$status);
        $index_file-> bindParam(':userid',$userid);

        $index_file -> execute(); 
        
        return 1;

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }

    public function setStatusCounsil($userid,$status){              // Έγκριση αίτησης από το "Διοικητικό Συμβούλιο"

      try{
        $index_file = $this->conn->prepare('UPDATE users SET status = :status WHERE userid = :userid ');
        $index_file-> bindParam(':userid',$userid);
        $index_file-> bindParam(':status',$status);

        $index_file -> execute();
      
        
        return true;

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }

    public function getCounsilApp(){

      try{
        $allApp = $this->conn->prepare("SELECT * FROM users WHERE property = 1 AND (status = '0' || status = '1' ) ");
        $allApp -> execute();
        return $allApp;
        //while( $fetch = $allApp->fetch(PDO::FETCH_ASSOC)) {return $fetch;}

      }
      catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      } 
    }    


    
    public function getManagementApp(){           // Fetching all the available application for "MANAGEMENT" department

      try{
        $allApp = $this->conn->prepare("SELECT * FROM users WHERE property = 1 AND (status = '1' || status = '2' || status = '3' || status = '4') ");
        $allApp -> execute();
        return $allApp;
        //while( $fetch = $allApp->fetch(PDO::FETCH_ASSOC)) {return $fetch;}
        }
        catch(PDOException $e){
          die("Cannot index the file" . $e->getMessage());
        }
    }

    public function setManagementUpload($filename,$username,$nextStatus){          // Refresh Applicants Data when 'MANAGEMENT' department, proceeds with confirmations

      try{
        $index_file = $this->conn->prepare('UPDATE users SET callFile = :filename, status = :status WHERE username = :username ');
        $index_file-> bindParam(':filename',$filename);
        $index_file-> bindParam(':username',$username);
        $index_file-> bindValue(':status',$nextStatus);
        $index_file -> execute(); 
        
        return true;

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }

    public function getMinisterApp(){

      try{
        $allApp = $this->conn->prepare("SELECT * FROM users WHERE property = 1 AND (status = '2' || status = '3' || status = '4' ) ");
        $allApp -> execute();
        return $allApp;
        //while( $fetch = $allApp->fetch(PDO::FETCH_ASSOC)) {return $fetch;}

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      } 

    }

    public function setMinisterUpload($filename,$username,$nextStatus,$callStatus){     // upografi ypourgou
     
      try{
        $index_file = $this->conn->prepare('UPDATE users SET status = :status, callFileStatus = :callStatus, signedCallFile = :filename  WHERE username = :username ');
        $index_file-> bindValue(':status',$nextStatus);  
        $index_file-> bindValue(':callStatus', $callStatus);              
        $index_file-> bindParam(':filename',$filename);
        $index_file-> bindParam(':username',$username);

        $index_file -> execute(); 
        
        return 1;

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }    

    public function updateReport($userid,$reported){      // Eπιβεβαίωση εγγράφου υπουργού από το Τμήμα Διοίκισης

      $status = 4;
      try{
        $index_file = $this->conn->prepare('UPDATE users SET status = :status, reported = :reported WHERE userid = :userid ');
        $index_file-> bindParam(':userid',$userid);
        $index_file-> bindParam(':reported',$reported);
        $index_file-> bindValue(':status',$status);
        $index_file -> execute();
      
        
        

      }catch(PDOException $e){
        die("Cannot index the file" . $e->getMessage());
      }
    }    


}
?>