<?php

// Use this to easily get the hash of a password.
if (isset($_GET['hash'])) { die(password_hash($_GET['hash'], PASSWORD_BCRYPT)); }

// Configure this to your liking!
$accountList = [
	'user' => '$2y$10$n3kCxTI9eU8Zjof17pWd4uN1RFjLxQK7K.cE7DklgIRHEUEiu5rQ2', // => pass
	'admin' => '$2y$10$xnNBVXU.2UZef7mFUZwd0e37L3HMVAr9wWHCXqwKFmFamTcX/pGVS', // => password123
	'root' => '$2y$10$uD7zWqhkN2r7w3RPYJ5ZVubKR9DZxkXk/c37AizyMY2voHiqoR9D6' // => toor
];

(!isset($_SESSION) ? session_start() : $uselessvar = true);

if (isset($_GET['logout'])) {
	session_unset();
	session_destroy();
	echo "<script>alert('Logged out successful.');</script>";
	header("Location: " . $_SERVER['PHP_SELF']);
} 

if (isset($_SESSION['verified'])) {
?>
<!-- User is logged in. -->
<!DOCTYPE html>
<html>
<head>
	<title>One Page | Signed In</title>
</head>
<body>
<p>Signed In</p>

<a href="?logout">Logout</a>
</body>
</html>

<?php
	die();
}

// Only execute if requested via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['auth'])) {
	// Verify the 'user' variable was sent
	if (isset($_POST['user'])) {
		// Check if username returns a password from array (ie verify it exists)
		if (!empty($accountList[$_POST['user']])) {
			// Verify submitted password to respective username
			if (password_verify($_POST['pass'], $accountList[$_POST['user']])) {
				echo "Status: Verification successful.";
				$_SESSION['verified'] = true;
				header("Location: " . $_SERVER['PHP_SELF']);
			} else { echo "Status: Password is incorrect."; }
		} else { echo "Status: Username doesn't exist."; }
	} else { echo "Status: Please define a username."; }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login!</title>
</head>
<body>

<form action="" method="post">
	<label>Username: </label><input type="text" name="user" autofocus><br />
	<label>Password: </label><input type="password" name="pass"><br /><hr />
	<button type="submit" name="auth">Submit</button>
	<button type="reset">Reset</button>
</form>

</body>
</html>
