<?php	
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	if (isset($_SESSION['username']) && $_SESSION['superuser'] == 0) {
		die("You do not have administrator access. <a href='index.php'>Back to Expendable News</a>");
	}

	if (!isset($_SESSION['canary']) || empty($_SESSION['canary']))
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");

	if (!isset($_POST['canary']) || empty($_POST['canary']))
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");

	if ($_SESSION['canary'] != $_POST['canary'])
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");
	
	// sets up a database connection
	require_once('db.inc.php');
	
	$insert_post_query = $db->prepare("
		INSERT INTO `posts` 
			(`author`, `title`, `category`, `content`, `visibility`) 
		VALUES
			(:author, :title, :category, :content, :visibility)
	");

	if (isset($_POST['visibility'])) {
		$params = array (
			':author' => $_SESSION['username'],
			':title' => $_POST['title'],
			':category' => $_POST['category'],
			':content' => $_POST['content'],
			':visibility' => $_POST['visibility']
		);
	}
	else {
		$params = array (
			':author' => $_SESSION['username'],
			':title' => $_POST['title'],
			':category' => $_POST['category'],
			':content' => $_POST['content'],
			':visibility' => 1
		);
	}

	$insert_post_query->execute($params);
	
	header('Location: index.php');

?>