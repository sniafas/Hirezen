<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    session_start();
    require_once("hirezen.member.php");
    $user = new Member();
    if( !($user -> is_loggedin()) ) $user->redirect('index.php');
    $username = $_SESSION['username'];
    $userid = $_SESSION['userid'];

    
?>

<!DOCTYPE HTML> 
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> -Hirezen-</title>

    <link href="css/main.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="css/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap/dist/css/bootstrap-theme.min.css">   
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
    <link href="assets/progressindicator/indicator.css" rel="stylesheet">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.4/angular.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="css/bootstrap/dist/js/bootstrap.min.js"></script>
</head>


<body ng-app="indicator">


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
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">  
                    <span class="glyphicon glyphicon-user"></span>&nbsp;Welcome <?php echo $_SESSION['username']; ?>&nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">              
                            <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Αποσύνδεση</a></li>
                        </ul>
                </li>
            </ul>
        </div>
     </div>
    </nav>


    <div style="margin: 80px;">
    </div>
    <div class="container">                <!-- bootstrap container -->
    <div class="row">                   <!-- bootstrap row -->
    <?php 

    if ( isset($_POST['submit']) )
    {
        if( $user -> setUserUpload($_FILES["myFile"]["name"],$userid,0) ) // set file to database
            if( $user -> uploadFile($username) )   //upload file to path
    ?>
        <div class="alert alert-success">
            <i class="glyphicon glyphicon-ok"></i> &nbsp; To αρχείο ανέβηκε επιτυχώς!             
        </div>
    <?php       
    } 
    if ( $user -> is_loggedin() ){          //if user logged in
    ?> 
    <?php
        $row = $user->runQuery('SELECT * FROM users WHERE userid = :userid');
        $row->execute(array(':userid'=>$userid));
        $row -> execute();
        $userData = $row->fetch(PDO::FETCH_ASSOC);
        $appStatus = $user -> getGaugeValue($userData['status']); // refactor app status to gauge value
    ?>    




           


           <div class="col-lg-12 col-sm-12 col-12"> 
                <div class="col-md-4"> 
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2> Νέα αίτηση </h2>
                        </div>
                        <div class="upload-div">
                                <form action="myapplication.php" method="post" enctype="multipart/form-data"> 
                                    <span class="file-input btn btn-primary btn-file">
                                                Browse&hellip; or Drop 
                                        <input type="file" multiple name="myFile">
                                        <input class="btn btn-default" name="submit" type="submit" value="Upload">
                                    </span>
                                </form>
                            
                        </div>
                    </div>
                </div>
                                              


                <?php 
                if ( !isset($userData['filename']) )
                {
                    $href = "";
                    $hname = "<i class='fa fa-file-pdf-o fa-8'> </i>" ;
                }
                else
                {
                    $href = "<a target='_blank' href=" . "uploads/".  $userData['username'] . "/" . $userData['filename'] . " >" ;
                    $hname = "<i class='fa fa-file-pdf-o fa-8'>" . $userData['filename'] . "</i>" ;                            
                }
                ?>

                <div class="col-md-4">  
                    <div class="panel panel-default">
                        <div class="panel-heading">         
                            <h2> Η Αίτησή μου </h2>
                        </div>
                        <div class="upload-div">                     
                            <div class="widget-container"
                                data-expected="1.00"
                                data-actual=" <?php echo($appStatus); ?> " indicator-widget>
                            </div>
                            <div style="margin: 10px 10px 10px 10px;">
                            <?php 
                                echo $href; 
                                echo $hname;
                            ?>                           
                            </div>
                            <div style="margin: 10px 10px 10px 10px;">
                                <a class="btn btn-danger" href="">
                                <i class="fa fa-trash-o fa-lg"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
            

                <?php 
                if ( !isset($userData['signedCallFile']) )
                {
                    $hrefCall = "<i class='glyphicon glyphicon-alert'></i> &nbsp; Aναμένεται...   ";
                    $hnameCall = "";
                }
                else
                {
                    $hrefCall = "<a target='_blank' href=" . "uploads/".  $userData['username'] . "/" . $userData['signedCallFile'] . " >" ;
                    $hnameCall = "<i class='fa fa-file-pdf-o fa-8'>" . $userData['signedCallFile'] . "</i>" ;                      
                }
                ?>

                <div class="col-md-4"> 
                    <div class="panel panel-default">
                        <div class="panel-heading">  
                            <h2> Σύμβαση </h2>
                        </div>
                        <div class="upload-div" style="align:center">                                       
                            <div style="margin: 10px 10px 10px 10px;">
                            <?php 
                                echo $hrefCall; 
                                echo $hnameCall;
                            ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- col mg 12 --> 

        </div> <!-- row -->
    </div>     <!-- container -->
       
        
    <script type="text/ng-template" id="indicator.html">
        <svg class="indicator-circle" 
            width="150" height="150" 
            version="1.1" 
            xmlns="http://www.w3.org/2000/svg">
        <g>
            <circle cx="50%" cy="50%" r="45" />
            <text class="progress-text actual" text-anchor="middle" alignment-baseline="middle" dx="50%" dy="50%">
                <tspan class="actual">{{ actual_formatted }}</tspan>
                <tspan class="percent">%</tspan>
            </text>
            <text class="progress-text" text-anchor="middle" alignment-baseline="middle" dx="50%" dy="60%">Progress</text>
        </g>
        <g path-group>
            <path class="progress-bar inner-bar normal" stroke-linejoin="round" inner-path />
            <path class="progress-bar outer-bar" stroke-linejoin="round" outer-path />
        </g>
        </svg>
    </script>
    
    <?php
    }     
    ?>

    <script src="assets/progressindicator/indicator.js"></script>
    <script src="js/dropzone.js"></script>
    <script src="assets/d3/d3.v3.min.js"></script> 

</body>
</html>