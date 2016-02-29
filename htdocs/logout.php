<?php

	session_start();
	session_destroy();
	echo "<h3> You are now logged out </h3>";
	header('Refresh:2; URL=index.php');

?>