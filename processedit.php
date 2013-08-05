<?php	
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	if (isset($_SESSION['username']) && $_SESSION['superuser'] == 0) {
		die("You do not have administrator access. <a href='index.php'>Back to Expendable News</a>");
	}
	
	if (!isset($_SESSION['canary']) || empty($_SESSION['canary']))
	    die("CSRF detected");

	if (!isset($_POST['canary']) || empty($_POST['canary']))
	    die("CSRF detected");

	if ($_SESSION['canary'] != $_POST['canary'])
	    die("CSRF detected");
	
	// sets up a database connection
	require_once('db.inc.php');
	
	$update_posts_query = $db->prepare("UPDATE `posts` SET `author`=:author, `title`=:title, `category`=:category, `content`=:content, `visibility`=:visibility WHERE `post_id`=:post_id");

	if (isset($_POST['visibility'])) {
		$params = array (
			':author' => $_SESSION['username'],
			':title' => $_POST['title'],
			':category' => $_POST['category'],
			':content' => $_POST['content'],
			':visibility' => $_POST['visibility'],
			':post_id' => $_POST['post_id']
		);
	}
	else {
		$params = array (
			':author' => $_SESSION['username'],
			':title' => $_POST['title'],
			':category' => $_POST['category'],
			':content' => $_POST['content'],
			':visibility' => 1,
			':post_id' => $_POST['post_id']
		);
	}

	$update_posts_query->execute($params);
	
	header("Location: open.php?id=" . $_POST['post_id']);

?>