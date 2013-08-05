<?php	
	session_start();
	
	if (!isset($_SESSION['canary']) || empty($_SESSION['canary']))
	    die("CSRF detected. <a href='login.php'>Log In</a>");

	if (!isset($_POST['canary']) || empty($_POST['canary']))
	    die("CSRF detected. <a href='login.php'>Log In</a>");

	if ($_SESSION['canary'] != $_POST['canary'])
	    die("CSRF detected. <a href='login.php'>Log In</a>");
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	// sets up a database connection
	require_once('db.inc.php');
				
	$get_user_query = $db->prepare("SELECT * FROM `users` WHERE `username`=:username");

	$params = array(
		':username' => $_POST['username']
	);

	$get_user_query->execute($params);
	$user = $get_user_query->fetch();
	
	$userPass = $user['password'];
	

	
	if (crypt($password, $userPass) === $userPass) {
		$_SESSION['username'] = $user['username'];
		$_SESSION['superuser'] = $user['superuser'];
		
		if (isset($_SESSION['post_id'])) {
			header("Location: open.php?id=" . $_SESSION['post_id']);
		}
		else {
			header('Location: index.php');
		}
	}
	else {
		die("Invalid login attempt. Make sure your username and password are correct! <a href='login.php'><button>Log In</button></a>");
	}
?>