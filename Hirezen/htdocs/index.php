<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    require_once("hirezen.member.php");
    $user = new Member();
    $reg = NULL;

    if ( isset($_POST['submit']) ) {
        
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        if($user->commitLogin($username,$password) )
        {
            echo "Now redirecting.";
            $ok = 1;
            if ($_SESSION['property'] == 1)   $user->redirect('myapplication.php');
            else $user->redirect('dashboard.php');
        }
        else
        {
            $error = "Wrong Details !";
            echo $error;
        }
    }
?>

<!DOCTYPE HTML> 
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> -Hirezen-</title>


<link href="css/main.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap/css/bootstrap-theme.min.css">   
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
<script src="js/jquery.js"></script>

</head>

<body>


<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">  
    <!-- <button type="button" data-target="#menuCollapse" data-toggle="collapse" class="navbar-toggle"> -->
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" rel="home" href="#" title="Hirezen">
            <img style="max-width:35px; margin-top: -7px;"
                 src="/img/hirezen.png">
        </a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav"> 
            <li class="active"><a href='index.php'>Αρχική</a></li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php 
                if ( (isset($_SESSION['username']))  ) { 
            ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">  
                <span class="glyphicon glyphicon-user"></span>&nbsp;Welcome <?php echo $_SESSION['username']; ?>&nbsp;<span class="caret"></span></a>
                    <ul class="dropdown-menu">              
                        <?php
                        if ($_SESSION['property']==1){
                            echo '<li><a href="myapplication.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Oι αιτήσεις μου</a></li>';
                        }
                        else {
                            echo '<li><a href="dashboard.php"><span class="glyphicon glyphicon-user"></span>&nbsp;Dashboard</a></li>';
                        }
                        ?>
                        <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Αποσύνδεση</a></li>
                    </ul>
            </li>
        </ul>
        <?php 
        }       
         else{
        ?>
        <form class="navbar-form navbar-right"  method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <button type="submit" name="submit" class="btn btn-default">Σύνδεση</button>
        </form>
        <?php
        } ?>

    </div>
 </div>
</nav>

<!-- <div id="testy" style="margin: 0 auto;"></div> -->
<div style="margin:100px;"> </div>
<?php
if ( !isset($_SESSION['username']) ) 
{
?>

<div class="container">

    <div style="margin-top:50px;" id="error">
        <?php
        if(isset($error))
        {
        ?>
        <div class="alert alert-danger">
            <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> 
        </div>
        <?php
        }
        if(isset($ok))
        {
        ?>
        <div class="alert alert-success">
            <i class="glyphicon glyphicon-ok"></i> &nbsp; <?php echo "Καλώς Ορίσατε"; ?>             
        </div>
    </div>
        <?php 
          
        }
        ?>
    <div style="padding:50px;">
        <div class="row centered-form">
        <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                        <h3 class="panel-title">Κάντε την εγγραφή σας στο HIREZEN </h3>
                        </div>
                        <div class="panel-body">
                        <form  method="post" >
                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="name" id="first_name" class="form-control input-sm floatlabel" placeholder="Όνομα">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="surname" id="last_name" class="form-control input-sm floatlabel" placeholder="Επίθετο">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="text" name="username" id="last_name" class="form-control input-sm" placeholder="username">
                                
                            </div>
                            <div class="form-group">
                                
                                <input type="email" name="email" id="email" class="form-control input-sm" placeholder="Email">
                            </div>                            

                            <div class="row">
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="password" name="password" id="password" class="form-control input-sm" placeholder="Κωδικός">
                                    </div>
                                </div>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control input-sm" placeholder="Επιβεβαίωση Κωδικού">
                                    </div>
                                </div>
                            </div>
                            <?php
                                 include("register_controllers.php");
                            ?>

                            <input type="submit" name="register" value="Εγγραφή" class="btn btn-info btn-block">
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

        <?php 
        
        if( isset($errors)){
              
            foreach($errors as $errors)
            {
                 ?>
                 <div class="alert alert-danger">
                    <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $errors; ?>
                 </div>
                 <?php
            }            
        }
        else if( $reg )
        {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered 
                 </div>
    <?php
        }
}
?>






    <div class="container">

        <!-- Marketing Icons Section -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                   Καλώς Ορίσατε στο Hirezen
                </h1>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-check"></i> Ηλεκτρονική Διακυβέρνηση</h4>
                    </div>
                    <div class="panel-body">
                        <p>Η εφαρμογή ανοίγει νέους ορίζοντες στον τρόπο ηλεκτρονικής διακυβέρνησης και εξυπηρέτησης των πολιτών.</p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-gift"></i> Δωρεάν Πρόσβαση </h4>
                    </div>
                    <div class="panel-body">
                        <p>Όλοι οι υποψήφιοι εργαζόμενοι έχουν τώρα τη δυνατότητα να αποστείλουν τις αιτήσεις και τα βιογραφικά τους, 
                            online, με τον πιο εύκολο και γρήγορο τρόπο. </p>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><i class="fa fa-fw fa-compass"></i> Η υπηρεσία</h4>
                    </div>
                    <div class="panel-body">
                        <p>Η υπηρεσία παρέχει ζωντανή πορεία της αίτησης του χρήστη καθώς και την δυνατότητα στους υπηρεσιακούς υπαλλήλους να 
                         επεξεργαστούν και να ελέγξουν με απλό τρόπο την πορεία των αιτήσεων.</p>
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row -->
   
    <!-- Load JS here for greater good =============================-->
    <script src="css/bootstrap/js/bootstrap.min.js"></script>


</body>
</html>