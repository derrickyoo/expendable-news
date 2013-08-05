<?php

// sets up a database connection
require_once('db.inc.php');

// get all posts uploaded by admin user
$comment_query = $db->prepare("SELECT * FROM `posts` WHERE `post_id`=:post_id");

// get all comments relating to post_id
$comment_query = $db->prepare("
	SELECT 
		`comments`.`comment_id`,
		`comments`.`username`, 
		`comments`.`comment`,
	FROM `comments` WHERE `post_id`=:post_id
");

$params = array (
	':post_id' => $post_id
);

// execute open post query
$open_posts_query->execute($params);
$posts = $open_posts_query->fetchAll(PDO::FETCH_ASSOC);

?>