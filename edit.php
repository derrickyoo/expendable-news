<?php	
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
		die("You are not logged in. <a href='login.php'>Log In</a>");
	}
	
	if (isset($_SESSION['username']) && $_SESSION['superuser'] == 0) {
		die("You do not have administrator access. <a href='index.php'>Back to Expendable News</a>");
	}
	
	// CSRF protection
	$canary = sha1(rand());
	$_SESSION['canary'] = $canary;
	
	// sets up a database connection
	require_once('db.inc.php');
	
	$get_posts_query = $db->prepare("SELECT * FROM `posts` WHERE `post_id`=:post_id");
	
	$params = array (
		':post_id' => $_GET['id']
	);
	
	$get_posts_query->execute($params);
	$edit = $get_posts_query->fetch();
	
	$post_id = $edit[post_id];
	$title = $edit[title];
	$content = $edit[content];
	$category = $edit[category];
	$visibility = $edit[visibility];
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>edit.php</title>	
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
				</td>
				<td id='main-login'>
					<?php
						if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
							echo  "<img src='images/padlock_closed_icon&amp;16.png' width='10' height='10' alt='Padlock Closed Icon&amp;16'>" . "<a href='login.php'>" . " login" . "</a>";
						}
						else {
							echo "<img src='images/padlock_open_icon&amp;16.png' width='10' height='10' alt='Padlock Open Icon&amp;16'>" . "<a href='logout.php'>" . " logout" . "</a>";	
						}
					?>
				</td>
			</tr>
		</table>
	</div>
	<div class='wrapper'>
		
		<div id='promoTitle'>
			<p>Edit This Post On Expendable News</p>
		</div>
		<div id='promoTitleShort'>
			<p>Edit the title, category, or blog post message and submit to update new changes to Expendable News.</p>
		</div>

		<div id='post-form'>
			<form name='post-fields' method='post' action='processedit.php'>
				<input type='hidden' name='canary' value='<?= $canary ?>'>
				<input type='hidden' name='post_id' id='post_id' value='<?= $post_id ?>'>
				<div id='post-form-inner'>
					<label for='Title'>
						<span class='field'>Title:</span>
						<span class='input'>
							<input type='text' name='title' id='title' value='<?= $title ?>' autofocus='autofocus' required>
						</span>
					</label>

					<label for='category'>
						<span class='field'>Category:</span>
						<span class='input'>
							<input type='field' name='category' id='category' value='<?= $category ?>'>
						</span>	
					</label>
		
					<label for='content'>
						<span class='field'>Blog Post Message:</span>
						<span class='input'>
							<textarea name='content' id='content' required><?= $content ?></textarea>
						</span>	
					</label>
				
					<?php
						if ($visibility == 0) {
							echo "<label for='visibility'>"; 
							echo "<input type='checkbox' name='visibility' value='0' checked='checked'>" . "Disable Comments" . "<br>";
							echo "</label>";
						}
						else {
							echo "<label for='visibility'>"; 
							echo "<input type='checkbox' name='visibility' value='0'>" . "Disable Comments" . "<br>";
							echo "</label>";
						}
					?>
					<label for='submit'>
						<button name='submit' id='submit'>Save</button>
					</label>
				<div>
			</form>
			<br><a href="index.php">Discard Changes</a>
		</div>
	</div>
</body>
</html>