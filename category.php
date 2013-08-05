<?php
	session_start();
	
	$category = $_GET['cat'];
	$_SESSION['category'] = $category;
	
	// sets up a database connection
	require_once('db.inc.php');

	// pagination
	
	if (isset($_GET["page"])) { 
		$page  = $_GET["page"]; 
	} 
	else { 
		$page = 1; 
	};
	
	$limit = 5;
	$start  = ($page - 1) * $limit;

	//Get all posts uploaded by admin user
	$get_category_query = $db->prepare("SELECT * FROM `posts` WHERE `category`=:category ORDER BY `created` DESC LIMIT {$start}, {$limit}");
	$params = array (
		':category' => $category
	);
	$get_category_query->execute($params);
	$categories = $get_category_query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>category.php</title>	
	<link rel='stylesheet' href='style.css' type='text/css'>
</head>
<body>
	<div id='main-header'>
		<table>
			<tr>
				<td id='main-logo'>
					<a href='index.php'>Ex</a>
				</td>
				<td id='main-title'>
					<a href='index.php'>&bull;pend&bull;a&bull;ble News</a>
				</td>
				<td id="session-name">
					<?php
						if (isset($_SESSION['username']) || !empty($_SESSION['username'])) {
							echo "Welcome, " . $_SESSION['username'] . "!";
					}
					?>
				</td>
				<td id='main-post-category'>
					<?php
						if (isset($_SESSION['username']) && $_SESSION['superuser'] == 1) {
							echo "<img src='images/doc_plus_icon&amp;16.png' width='10' height='10' alt='Doc Plus Icon&amp;16'>" . "<a href='post.php'>" . " New Post" . "</a>";
						}
					?>
					<img src="images/folder_open_icon&amp;16.png" width="10" height="10" alt="Folder Open Icon&amp;16"> <a href='category.php?cat=<?= $category ?>'><?= $category ?></a>
				</td>
				<td id='main-login'>
					<?php
						if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
							echo "<img src='images/padlock_closed_icon&amp;16.png' width='10' height='10' alt='Padlock Closed Icon&amp;16'>" . "<a href='login.php'>" . " login" . "</a>";
						}
						else {
							echo "<img src='images/padlock_open_icon&amp;16.png' width='10' height='10' alt='Padlock Open Icon&amp;16'>" . "<a href='logout.php'>" . " logout" . "</a>";
						}
					?>
				</td>
			</tr>
		</table>
	</div>
	<?php
	foreach ($categories as $cat) {
		$post_id = $cat[post_id];
		$title = $cat[title];
		$author = $cat[author];
		$content = $cat[content];
		$created = $cat[created];
		$category = $cat[category];
		$visibility = $cat[visibility];
	
		echo "<div class='output'>";
		echo "<span class='post-title'>" . "<a href='open.php?id=" . $post_id . "'>" . $title . "</a>" . "</span>" . "<br>";
		echo "by" . " " . $author . "<br>" . "<br>";
		
		echo $content . "<br>" . "<br>";
		
		// post date
		echo "<img src='images/calendar_2_icon&amp;16.png' width='10' height='10' alt='Calendar 2 Icon&amp;16'>" . " " . $created . " ";
		
		// post category
		
		echo "<img src='images/folder_open_icon&amp;16.png' width='10' height='10' alt='Folder Open Icon&amp;16'>" . " "  . "<a class='post-category-link' href='category.php?cat=" . $category . "'>" . $category . "</a>" . " ";
		
		if (isset($_SESSION['username']) && $_SESSION['superuser'] == 1) {
			// edit post
			echo "<img src='images/doc_edit_icon&amp;16.png' width='10' height='10' alt='Doc Edit Icon&amp;16'>" . "<a href='edit.php?id=" . $post_id . "'>" . " Edit " . "</a>";
			
			// delete post
			echo "<img src='images/doc_delete_icon&amp;16.png' width='10' height='10' alt='Doc Delete Icon&amp;16'>" . "<a href='processdelete.php?id=" . $post_id . "'>" . " Delete " . "</a>";
		}
		
		if ($visibility == 1) {
			// comments
			echo "<img src='images/chat_bubble_message_square_icon&amp;16.png' width='10' height='10' alt='Chat Bubble Message Square Icon&amp;16'>" . "<a href='open.php?id=" . $post_id . "'>" . " Comment" . "</a>";
		}
		
		echo "</div>";
	}
		echo "<br>";
	?>
	
	<?php
		// get post count and pagination
		$count_categories_query = $db->prepare("SELECT * FROM `posts` WHERE `category`=:category");
		$params = array(
			':category' => $category
		);
		$count_categories_query->execute($params);
		$rows = $count_categories_query->fetchALL(PDO::FETCH_ASSOC);
		
		$total_posts = count($rows);
		$total_pages = ceil($total_posts / $limit);
		
		for ($i=1; $i<=$total_pages; $i++) {
			echo "<div class='pagination'>";
		    echo "<a href='categorypage.php?page=" . $i . "'>" . $i . "</a>";
			echo "</div>";
			echo " ";
		};
	?>
</body>
</html>