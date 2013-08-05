<?php	
	session_start();
	
	if (!isset($_SESSION['canary']) || empty($_SESSION['canary']))
	    die("CSRF detected. <a href='account.php'>Join</a>");

	if (!isset($_POST['canary']) || empty($_POST['canary']))
	    die("CSRF detected. <a href='account.php'>Join</a>");

	if ($_SESSION['canary'] != $_POST['canary'])
	    die("CSRF detected <a href='account.php'>Join</a>");
	
	$password = $_POST['password'];
	$passConfirm = $_POST['password-confirm'];
	
	if ($password != $passConfirm) {
		die("Passwords do not match. <a href='account.php'>Join</a>");
	}
	
	// sets up a database connection
	require_once('db.inc.php');

	// checks to see if username exists
	$user_exists_query = $db->prepare("SELECT * FROM `users` WHERE `username`=:username");

	$params = array (
		':username' => $_POST['username']
	);

	$user_exists_query->execute($params);
	$user = $user_exists_query->fetch();

	if(!empty($user)) {
		die("Username exists. Please choose another username! <a href='account.php'><button>Join</button></a>");
	}
	else {
		// generates random salt to hash user password using bcrypt()
		$alphabet = "/.abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$string = str_repeat($alphabet, 10);
		$string = str_shuffle($string);

		$salt = "$2a$07$" . $string . "$";

		// encrypts password using generated salt
		$hashPass = crypt("$password", $salt);

		$insert_user_query = $db->prepare("
			INSERT INTO `users` 
				(`username`, `password`) 
			VALUES
				(:username, :password)
		");

		$params = array (
			':username' => strip_tags($_POST['username']),
			':password' => $hashPass
		);

		$insert_user_query->execute($params);
		
		$_SESSION['username'] = strip_tags($_POST['username']);
		
		if (isset($_SESSION['post_id'])) {
			header("Location: open.php?id=" . $_SESSION['post_id']);
		}
		else {
			header('Location: index.php');
		}
	}
?>