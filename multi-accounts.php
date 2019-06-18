<?php

$password = "password123"; // Password.

(!isset($_SESSION) ? session_start() : $uselessvar = true);

/*
  ___                   ____
 / _ \ _ __   ___      |  _ \ __ _  __ _  ___
| | | | '_ \ / _ \_____| |_) / _` |/ _` |/ _ \
| |_| | | | |  __/_____|  __/ (_| | (_| |  __/
 \___/|_| |_|\___|     |_|   \__,_|\__, |\___|
                                   |___/
 ____
| __ ) _   _ _
|  _ \| | | (_)
| |_) | |_| |_
|____/ \__, (_)
       |___/
 ____  _            _   __     ___ _    _             ____
| __ )| | __ _  ___| | _\ \   / (_) | _(_)_ __   __ _|  _ \ _ __ ___
|  _ \| |/ _` |/ __| |/ /\ \ / /| | |/ / | '_ \ / _` | |_) | '__/ _ \
| |_) | | (_| | (__|   <  \ V / | |   <| | | | | (_| |  __/| | | (_) |
|____/|_|\__,_|\___|_|\_\  \_/  |_|_|\_\_|_| |_|\__, |_|   |_|  \___/
                                                |___/
*/

if (isset($_POST['logout'])) {
	session_unset();
	session_destroy();
	echo "Logged out successful.";
} 

if (isset($_SESSION['token'])) {
	if (password_verify($password, $_SESSION['token'])) {
?>

<!-- User is logged in. -->
<!DOCTYPE html>
<html>
<head>
	<title>One Page | Signed In</title>
</head>
<body><p>Signed In</p><form action="" method="post"><button type="submit" name="logout">Logout</button></form>
</body>
</html>

<?php
		die();
	}
}

if (isset($_POST['password'])) {
	$pass = $_POST['password'];
	if ($pass == $password) {
		$_SESSION['token'] = password_hash($password, PASSWORD_DEFAULT); // not the best encryption/hashing, but it get's the job done.
		$self = $_SERVER['PHP_SELF'];
		header("Location: $self");
	} else {
		echo " Incorrect Password. ";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login!</title>
</head>
<body>

<form action="" method="post">
	<label>Password: </label><input type="text" name="password" autofocus="" />
	<button type="submit">Submit</button>
</form>

</body>
</html>
