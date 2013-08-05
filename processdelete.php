<?php
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	if (isset($_SESSION['username']) && $_SESSION['superuser'] == 0) {
		die("You do not have administrator access. <a href='index.php'>Back to Expendable News</a>");
	}

	// sets up a database connection
	require_once('db.inc.php');
	
	$delete_posts_query = $db->prepare("DELETE FROM `posts` WHERE `post_id`=:post_id");
	$delete_comments_query = $db->prepare("DELETE FROM `comments` WHERE `post_id`=:post_id");
	
	$params = array (
		':post_id' => $_GET['id']
	);
	
	$delete_posts_query->execute($params);
	$delete_comments_query->execute($params);
	
	header('Location: index.php')

?>