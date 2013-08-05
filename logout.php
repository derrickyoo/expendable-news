<?php
	session_start();
	
	$_SESSION = array();
	if (isset($_SESSION['username'])) {
		session_destroy();
	}
		
	header('Location: index.php');
?>