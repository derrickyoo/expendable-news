<?php	
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	if (!isset($_SESSION['canary']) || empty($_SESSION['canary']))
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");

	if (!isset($_POST['canary']) || empty($_POST['canary']))
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");

	if ($_SESSION['canary'] != $_POST['canary'])
	    die("CSRF detected. <a href='index.php'>Back to Expendable News</a>");
	
	$comment = $_POST['comment'];
	$comment = htmlspecialchars($comment);
	
	// sets up a database connection
	require_once('db.inc.php');
	
	$insert_comments_query = $db->prepare("INSERT INTO `comments` (`username`, `email`, `comment`, `post_id`) VALUES (:username, :email, :comment, :post_id)
	");
	
	$params = array (
		':username' => $_SESSION['username'],
		':email' => $_POST['email'],
		':comment' => $comment,
		':post_id' => $_POST['post_id']
	);

	$insert_comments_query->execute($params);
?>