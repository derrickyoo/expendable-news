<?php
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	// sets up a database connection
	require_once('db.inc.php');
	
	$delete_comment_query = $db->prepare("DELETE FROM `comments` WHERE `comment_id`=:comment_id");
	
	$params = array (
		':comment_id' => $_GET['com']
	);
	
	$delete_comment_query->execute($params);
	
	header("Location: open.php?id=" . $_SESSION['post_id']);
?>