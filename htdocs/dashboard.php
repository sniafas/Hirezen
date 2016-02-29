<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
	session_start();
    require_once("hirezen.member.php");	
	$user = new Member();
	$db = new Database();


    if( isset($_GET['status']) && isset($_GET['id']) ) { // action from counsil
        
        if($user -> setStatusCounsil($_GET['id'],$_GET['status']))
        {
       	?>
        <div class="alert alert-success">
            <i class="glyphicon glyphicon-ok"></i> &nbsp; H αίτηση εγκρίθηκε!             
        </div>       	
       	<?php
        }            

    }

    if ( isset($_POST['call']) && isset($_POST['user_path']) ) {	// action from management or minister
        
        if ($_POST['management'])
        {	
	        if( $user -> setManagementUpload($_FILES["myFile"]["name"],$_POST['user_path'],2) )
	        	if( $user -> uploadFile($_POST['user_path']) )
		        {
		        	$alert = "H απόφαση απόσπασης ανέβηκε με επιτυχία!";
		        }
		}
        if ($_POST['minister'])
        {	
	        if( $user -> setMinisterUpload($_FILES["myFile"]["name"],$_POST['user_path'],3,1) ){
	        	
	        	if( $user -> uploadFile($_POST['user_path']) )
		        {
		      		$alert = "H υπογεγραμμένη σύμβαση ανέβηκε με επιτυχία!";           
		        }
		    }
		}		
    }

    if( isset($_GET['reported']) && isset($_GET['id']) ) {  		// 
        
        $user->updateReport($_GET['id'],$_GET['reported']); 
        $alert = "H σύμβαση έχει εκδοθεί στο αιτούμενο!";            

    }

?>

<!DOCTYPE HTML> 
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>-Hirezen-Dashboard</title>
	<link href="css/main.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="css/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap/css/bootstrap-theme.min.css">   
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
	<link href="css/indicator.css" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.0-rc.4/angular.min.js"></script>
	<script src="js/jquery.js"></script>
	<script src="css/bootstrap/js/bootstrap.min.js"></script>
</head>


<body ng-app="indicator">

	<div style="margin-top:50px;" id="error">
        <?php
        if(isset($alert))
        {
        ?>
        <div class="alert alert-success">
            <i class="glyphicon glyphicon-ok"></i> &nbsp; <?php echo $alert; ?>             
        </div>
        <?php 
    	}
        ?>
    </div>




	<nav class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
	    <div class="navbar-header">  
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



	<?php
	if ($_SESSION['property'] == 2){ 												 // "COUNSIL" departement 
	?>

	<div class="container" style="margin-top: 50px;">
		<div class="container" style="margin-top: 50px;">
		    <div class="row">
		        <div class="col-lg-6 col-sm-6 col-8">
		            <h2> Εκκρεμείς Αιτήσεις προς Έγκριση </h2>
		            <div class="upload-div"> 


							<table class="table table-hover">
								<thead>
									<tr>
										<th> Όνομα </th>
										<th> Επώνυμο </th>
										<th> Αίτηση </th>
										<th> Κατάσταση</th>
									</tr>
								</thead>
								<tbody>	<?php

									$data = $user->getCounsilApp();												// lamvanei oles tis aitiseis	
									while( $allApplications = $data->fetch(PDO::FETCH_ASSOC)) {
										
										$name = $allApplications['name'];
										$surname = $allApplications['surname'];
										$username = $allApplications['username'];
										$f = $allApplications['filename'];
										$filename = "uploads/" . $username . "/" . $f; 
										$id = $allApplications['userid'];
								?>
									
									<tr>
										<td><?php echo "$name"; ?></td>
										<td><?php echo "$surname"; ?></td>
										<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href=" <?php echo $filename ; ?>" ></td>
									<?php
										if ( $allApplications['status'] == 0){	// oi aitiseis poy den exoyn epivevaiothei, perimenoyn egrisi. Stelnontas status=1
									?>																	
											<td><button onclick="location.href='dashboard.php?status=1&id=<?php echo $id; ?>'"> Εγκρίθηκε </button>
											</td>
											<!--<td><button  id='btn1' > Εγκρίθηκε </button>
											</td>
											<td><button onclick="location.href='dashboard.php?status=1&id=<?php echo $id; ?>'" id='btn1' > Εγκρίθηκε </button>
											</td>-->
									<?php }
											else echo "<td><span style='color:green'><i class='fa fa-check'></i></span></td>"; // alliws emfanizontai CHECKED!
									?>
									</tr>
									<?php
									}	
									?>
								</tbody>
							</table>
						</div>					
					</div>
		        </div>
			</div>
		</div>	

	<?php
	}
	if ($_SESSION['property'] == 3){						// "MANAGEMENT" departement
	?>

	<div class="container" style="margin-top: 50px;">
		<div class="container" style="margin-top: 50px;">
		    <div class="row">
		        <div class="col-lg-6 col-sm-6 col-8">
		            <h2> Εκκρεμείς Aποφάσεις προς Απόσπαση </h2>
		            <div class="upload-div"> 


							<table class="table table-hover">
								<thead>
									<tr>
										<th> Όνομα </th>
										<th> Επώνυμο </th>
										<th> Αίτηση </th>
										<th> Έγκριση</th>
										<th> Aπόφαση Απόσπασης </th>
										<th> Κατάσταση Απόφασης </th>
										<th> Κοινοποίηση </th>
									</tr>
								</thead>
								<tbody>	<?php

									$data = $user-> getManagementApp();		// "MANAGEMENT" department fetches all applications with status 1 || status 2
									while( $allApplications = $data->fetch(PDO::FETCH_ASSOC)) {
										
										$id = $allApplications['userid'];
										$name = $allApplications['name'];
										$surname = $allApplications['surname'];										
										$username = $allApplications['username'];
										$c = $allApplications['callFile'];
										$f = $allApplications['filename'];
										$reported = $allApplications['reported'];
										$filename = "uploads/" . $username . "/" . $f; 
										$callFile = "uploads/" . $username . "/" . $c; 
								?> 
									<tr>
										<td><?php echo "$name"; ?></td>
										<td><?php echo "$surname"; ?></td>										
										<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href=" <?php echo $filename ; ?>" ></td>
										<td><span style='color:green'><i class='fa fa-check'></i></span></td>
										<?php
										if ( $c== "" ) { 		// aitiseis poy den exoyn akoma tin apofasi toy management , EMFANIZOYN upload form
										?>
										<td>
											<form action="dashboard.php" method="post" enctype="multipart/form-data"> 
												<span class="btn btn-primary btn-file">
													Browse&hellip; or Drop <input type="file" multiple name="myFile">
													<input class="btn btn-primary" name="call" type="submit" value="Upload">
													<input type="hidden" name="user_path" value="<?php echo $username; ?>">
													<input type="hidden" name="management" value="1">
													<input type="hidden" name="minister" value="0">


												</span>
											</form>
										</td>
										<?php
										}
										else echo '<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href="' .$callFile .'" ></td>'  // alliws ta call twn aitisewn
										?>
										<?php
										if ( $allApplications['callFileStatus'] == 0){			// apofasi tou ypourgoy
										?>
											<td> <span style='color:orange'> Aναμένεται </span>
											</td>
									<?php }
											else {																						// yparxei apofasi kai perimenei ek neou egkrisi
												echo "<td><span style='color:green'><i class='fa fa-check'></i></span></td>";
												if ( $reported == "0")		echo "<td><a href='dashboard.php?reported=1&id=" . $id . "'>Κοινοποίηση στον αιτούμενο</a></td>";
												else echo "<td><span style='color:green'>Κοινοποιήθηκε!</span></td>";																				 		
									 		}
									 }
								
									}?>	
								</tbody>
							</table>
						</div>					
					</div>
		        </div>
			</div>
		</div>

	<?php
	if ($_SESSION['property'] == 4){ 			// "MINISTER" departement
	?>						

	<div class="container" style="margin-top: 50px;">
		<div class="container" style="margin-top: 50px;">
		    <div class="row">
		        <div class="col-lg-6 col-sm-6 col-8">
		            <h2> Εκκρεμείς Aποφάσεις προς Υπογραφή </h2>
		            <div class="upload-div"> 


							<table class="table table-hover">
								<thead>
									<tr>
										<th> Όνομα </th>
										<th> Επώνυμο </th>
										<th> Αίτηση </th>
										<th> Έγκριση</th>
										<th> Aπόφαση Απόσπασης </th>
										<th> Κατάσταση Απόφασης </th>
										<th> Yπογεγραμμένη Σύμβαση </th>

									</tr>
								</thead>
								<tbody>	<?php

									$data = $user->getMinisterApp();	
									while( $allApplications = $data->fetch(PDO::FETCH_ASSOC)) {
										
										$id = $allApplications['userid'];
										$name = $allApplications['name'];
										$surname = $allApplications['surname'];
										$username = $allApplications['username'];
										$c = $allApplications['callFile'];
										$f = $allApplications['filename'];
										$s = $allApplications['signedCallFile'];
										$callFileStatus = $allApplications['callFileStatus'];
										$filename = "uploads/" . $username . "/" . $f; 
										$callFile = "uploads/" . $username . "/" . $c; 
										$signedCallFile = "uploads/" . $username . "/" . $s; 
								?> 
									<tr>
										<td><?php echo "$name"; ?></td>
										<td><?php echo "$surname"; ?></td>										
										<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href=" <?php echo $filename ; ?>" ></td>
										<td><span style='color:green'><i class='fa fa-check'></i></span></td>
										<?php
										if ( $c== "" ) { ?>
											<td> <span style='color:orange'> Aναμένεται από το τμήμα διοίκησης </span>
											<?php
										}
										else echo '<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href="' .$callFile .'" ></td>' ?>
											<?php
										if ( $callFileStatus == 0){	?>
											<td> <span style='color:orange'> Aναμένεται </span>
											</td>
											<?php 
										}
										else echo "<td><span style='color:green'><i class='fa fa-check'></i></span></td>";
										
										if ( $callFileStatus == 0){	?>
										<td>
											<form action="dashboard.php" method="post" enctype="multipart/form-data"> 
												<span class="btn btn-primary btn-file">
													Browse&hellip; or Drop <input type="file" multiple name="myFile">
													<input class="btn btn-primary" name="call" type="submit" value="Upload">
													<input type="hidden" name="user_path" value="<?php echo $username; ?>">
													<input type="hidden" name="minister" value="1">
													<input type="hidden" name="management" value="0">

												</span>
											</form>
										</td>
										<?php
										}
										else echo '<td><a class="fa fa-file-pdf-o fa-4" target="_blank" href="' .$signedCallFile .'" ></td>'; 
								 		}
	}?>	
								</tbody>
							</table>
						</div>					
					</div>
		        </div>
			</div>
		</div>		


    <script src="js/main.js"></script>   
    <script src="js/jquery.js"></script>

</body>
</html>