<?php
	session_start();
	
	$post_id = $_GET['id'];
	$_SESSION['post_id'] = $post_id;
	
	$user = $_SESSION['username'];
	
	// CSRF protection
	$canary = sha1(rand());
	$_SESSION['canary'] = $canary;
	
	// sets up a database connection
	require_once('db.inc.php');
	
	// get all posts uploaded by admin user
	$open_posts_query = $db->prepare("SELECT * FROM `posts` WHERE `post_id`=:post_id");
	
	// get all comments relating to post_id
	$get_comments_query = $db->prepare("
		SELECT 
			`comments`.`comment_id`,
			`comments`.`username`, 
			`comments`.`comment`, 
		CONVERT_TZ(`comments`.`created`, 'UTC', 'MST') AS `created` 
		FROM `comments` WHERE `post_id`=:post_id
		ORDER BY `created` ASC
	");
	
	$params = array (
		':post_id' => $post_id
	);
	
	// execute open post query
	$open_posts_query->execute($params);
	$posts = $open_posts_query->fetchAll(PDO::FETCH_ASSOC);
	
	foreach ($posts as $post) {
		$post_id = $post[post_id];
		$title = $post[title];
		$author = $post[author];
		$content = $post[content];
		$created = $post[created];
		$category = $post[category];
		$visibility = $post[visibility];
	}
	
	// execute get comments
	$get_comments_query->execute($params);
	$rows = $get_comments_query->fetchAll(PDO::FETCH_ASSOC);
	
	// unix timestamp for future get comments request
	$timestamp_query = $db->prepare("SELECT UNIX_TIMESTAMP(CURRENT_TIMESTAMP) as `ts`");
	$timestamp_query->execute();
	$row = $timestamp_query->fetch();
	$timestamp = $row['ts'];
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>open.php</title>	
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
		
			echo "</div><br>";
			
			echo "<div id='post-title'><span class='post-title' id='post-title'>Comments:</span></div>";
			
			foreach ($rows as $row) {
				$comment_id = $row['comment_id'];
				$username = $row['username'];
				$response = $row['comment'];
				$time = $row['created'];
				
				echo "<div id='open-comments'>";
				echo "<b>" . $username . "</b>" . " says:<br>";
				echo "<br>"; 
				echo $response . "<br>";
				echo "<br>";
				echo "<img src='images/calendar_2_icon&amp;16.png' width='10' height='10' alt='Calendar 2 Icon&amp;16'>" . " " . $time . " ";
				
				if ($_SESSION['username'] == $username || $_SESSION['superuser'] == 1) {
					echo "<img src='images/delete_icon&amp;16.png' width='10' height='10' alt='Delete Icon&amp;16'>" . "<a href='deletecomment.php?com=" . $comment_id . "'>" . " Delete " . "</a>";
				}
			
				echo "</div>";
			}
			echo "<div id='commentjs'></div>";
			echo "<br>";
		}
		
		if (!isset($_SESSION['username']) && $visibility == 1) {
			echo "<a href='login.php?id=" . $post_id . "'>" . "Log In</a>" . " or " . "<a href='account.php?id=" . $post_id . "'>" . "Create Account</a>" . " to comment.";
		}
		
		if (isset($_SESSION['username']) && $visibility == 1) {
		?>
			<div id='comment-form'>
				<form id='comment-fields' name='comment-fields' method='post' action='processcomment.php'>
					<input type='hidden' name='canary' id='canary' value='<?= $canary ?>'>
					<input type='hidden' name='post_id' id='post_id' value='<?= $post_id ?>'>
					<input type='hidden' name='post_title' id='post_title' value='<?= $title ?>'>
				<div id='comment-form-inner'>
					<label for='name'>
						<span class='field'>Name:</span>
						<span class='input'>
							<input type='text' name='name' id='name' value='<?= $user ?>' disabled='disabled' required>
						</span>
					</label>

					<label for='email'>
						<span class='field'>Email:</span>
						<span class='input'>
							<input type='email' name='email' id='email' placeholder='Will Not Be Published - Required'>
						</span>
					</label>

					<label for='comment'>
						<span class='field'>Write Comment:</span>
						<span class='input'>
							<textarea name='comment' id='comment'></textarea>
						</span>
					</label>

					<label for='submit'>
						<input type='submit' id='submit' value='Submit'>
					</label>
				<div>
				</form>
			</div>
		<?php
		}
		$today = date("Y-m-d H:i:s")
		?>
		
		<input type="hidden" id='date' value='<?= $today ?>'>
		
		<script src="comments.js" type="text/javascript"></script>
</body>
</html>