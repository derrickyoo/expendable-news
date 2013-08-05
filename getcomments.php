<?php

	session_start();

	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}

	if(isset($_SESSION['username']) && $_SESSION['superuser'] == 0) {
		die("You do not have administrator access. <a href='index.php'>Back to Expendable News</a>");
	}

	require_once('db.inc.php');

	$timestamp = 0;
	if (isset($_GET['ts']) && !empty($_GET['ts'])) {
	    $timestamp = intval($_GET['ts']);
	}
	
	$comments_query = $db->prepare("
	    SELECT
	        `comments`.`username`,
	        `comments`.`comment`,
	        CONVERT_TZ(`comments`.`created`, 'UTC', 'MST') AS `created`,
	        UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS `ts`
	    FROM `comments`
	    WHERE UNIX_TIMESTAMP(`created`) > :ts
		AND `post_id`=:post_id
	");

	$params = array(
	    ':ts' => $timestamp,
		':post_id' => $_SESSION['post_id']
	);

	$comments_query->execute($params);
	$rows = $comments_query->fetchAll(PDO::FETCH_ASSOC);

	echo json_encode($rows);

?>