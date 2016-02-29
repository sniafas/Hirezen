<?php
    
    //require_once '../php/sqlconnect.php';

    error_reporting(E_ALL | E_STRICT);
    ini_set('display_errors', 'On');
    


    if ( isset($_POST['register']) ) {

        $name = $_POST['name'];
        $surname = $_POST['surname'];  
        $username = $_POST['username'];              
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $pass_c = $_POST['password_confirmation'];
        if( empty($name)){         //////////// Name /////////
            $errors[] = 'Κενό Όνομα';        }

        else if( empty($surname ) ){           /////// Username /////////
            $errors[] = 'Κενό Επώνυμο';
        }
        else if( empty($username )) {           /////// Username /////////
            $errors[] = 'Κενό όνομα χρήστη';
        }
        else if( !filter_var($email, FILTER_VALIDATE_EMAIL) ){                            ///// E Mail //////////
            $errors[] = 'Κενή διεύθυνση email';
        }
        else if(  empty($pass) ){   //// Password ///////
            $errors[]= 'Kενός Κωδικός!';
        }
        else if(  $pass != $pass_c ){   //// Password ///////
            $errors[]= 'Ασυμφωνία κωδικών!';
        }
        else if(  strlen($pass) < 6 ){   //// Password ///////
            $errors[]= 'Το password πρέπει να περιέχει τουλάχιστον 6 χαρακτήρες!';
            
        }
        
        else{

            try{
                $users_folder = './uploads/' . $username . '/';
                if (!mkdir($users_folder, 0777, true)) {
                    die('Αδυναμία δημιουργίας φακέλου χρήστη');
                }

                $property = 1;
                $status = 0;
                $callFileStatus = 0;
                $reported = 0;
                $sql = $user->runQuery("SELECT name,surname,username,email FROM users WHERE name = :name OR surname = :surname OR username=:username OR email=:email");

                $sql->bindParam(':name',$name);
                $sql->bindParam(':surname',$surname);
                $sql->bindParam(':username',$username);
                $sql->bindParam(':email',$email);
                $sql -> execute();  
                $checkDublicate = $sql -> fetch(PDO::FETCH_ASSOC);

                if($checkDublicate['username']==$username) {
                    $errors[] = "Το όνομα χρήστη υπάρχει ήδη !";
                }
                else if($checkDublicate['email']==$email) {
                    $errors[] = "To email υπάρχει !";
                }
                else if($checkDublicate['name']==$name) {
                    $errors[] = "To ονομα υπάρχει !";
                }
                else if($checkDublicate['surname']==$surname) {
                    $errors[] = "To επώνυμο υπάρχει !";
                }                                
                else
                {
                    if($user->register($name,$surname,$username,$email,$pass)){  
                        $reg = true;
                    }
                }
             
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }            

        }

    if( isset($_GET['regsuccess']) )
        {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Επιτυχής Εγγραφή
                 </div>
    <?php
        }

    }





?>