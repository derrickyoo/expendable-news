<?php
	session_start();
	
	// if user clicked login from a post stores the post_id in a session
	$post_id = $_GET['id'];
	$_SESSION['post_id'] = $post_id;
	
	// CSRF protection
	$canary = sha1(rand());
	$_SESSION['canary'] = $canary;
?>
<!DOCTYPE html>
<html lang='en'>
<head>
	<meta charset='utf-8'>
	<title>account.php</title>	
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
			<p>Create An Expendable News Account</p>
		</div>
		<div id='promoTitleShort'>
			<p>Create an account with Expendable News to be able to comment and participate our blog posts.</p>
		</div>
		
		<div id='account-form'>
			<form name='account-fields' method='post' action='processaccount.php'>
				<input type='hidden' name='canary' value='<?= $canary ?>'>
				<div id='account-form-inner'>
				<label for='username'>
					<span class='field'>Username:</span>
					<span class='input'>
						<input type='text' name='username' id='username' autofocus='autofocus' required>
					</span>
				</label>

				<label for='password'>
					<span class='field'>Password:</span>
					<span class='input'>
						<input type='password' name='password' id='password' required>
					</span>	
				</label>
				
				<label for='password-confirm'>
					<span class='field'>Re-enter Password:</span>
					<span class='input'>
						<input type='password' name='password-confirm' id='password-confirm' required>
					</span>	
				</label>
		
				<label for='join'>
					<button name='join' id='join'>Join</button> or <a href="login.php">Log In</a>	
				</label>
				<div>
			</form>
	
		</div>

	</body>
</html>