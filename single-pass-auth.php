<?php
/*
 * Multi-Account One-Page PHP Authentication Script
 * Created by Willy Fox (@BlackVikingPro)
 * Last Updated - 6/18/2019
 * Primary Repository - https://github.com/BlackVikingPro/Secure-PHP-Script-Signin/
*/

// Use this to easily get the hash of a password.
if (isset($_GET['hash'])) { die(password_hash($_GET['hash'], PASSWORD_BCRYPT)); }

// Configure this to your liking!
$masterPassword = "$2y$10$00c/ykj/Z.ci1sM6YO/6..Vcux2wMbXYjrbmf6TKk4Y63Wdy2WdMa"; // => password

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
	// Verify the 'pass' variable was sent
	if (isset($_POST['pass'])) {
		// Verify submitted password to respective username
		if (password_verify($_POST['pass'], $masterPassword)) {
			echo "Status: Verification successful.";
			$_SESSION['verified'] = true;
			header("Location: " . $_SERVER['PHP_SELF']);
		} else { echo "Status: Password is incorrect."; }
	} else { echo "Status: Please define a password."; }
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login!</title>
</head>
<body>

<form action="" method="post">
	<label>Password: </label><input type="password" name="pass" autofocus><br /><hr />
	<button type="submit" name="auth">Submit</button>
	<button type="reset">Reset</button>
</form>

</body>
</html>
