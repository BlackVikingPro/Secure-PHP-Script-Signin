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

// Simple configuration.
$tolen = 64; // Token Length (default is 64)
$nobrute = false; // Enable/Disable Anti-Brute-Force Feature.

// Anti-Brute-Force Settings.
$brutecount = 10; // Number of tries before temporarily banned.
$maxbrutecount = 20; // Number of tries before permanently banned.
$brutetimeout = 5; // Time length of temporary ban (5*60 = 5 mins; 6*60 = 6 mins.. so on...).

$max_execution_time = (int)$brutetimeout + 1;
ini_set('max_execution_time', $max_execution_time); // this fixes a fatal bug in PHP.

if (isset($_SESSION['failed-attempts'])) {

	if ($_SESSION['failed-attempts'] >= $brutecount && $_SESSION['failed-attempts'] <= $maxbrutecount) {

		echo "Temporarily banned. Please wait " . (string)$brutetimeout . " seconds.";

		ob_end_flush();

		flush();
		sleep($brutetimeout);

	} else if ($_SESSION['failed-attempts'] >= $maxbrutecount) {

		die("Permanently banned due to too many failed attempts.");

	}

}

/**
 * Code obtained from: http://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
 *
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	$str = '';
	$max = mb_strlen($keyspace, '8bit') - 1;
	for ($i = 0; $i < $length; ++$i) {
		$str .= $keyspace[random_int(0, $max)];
	}
	return $str;
	/*
	Usage:
	$a = random_str(32);
	$b = random_str(8, 'abcdefghijklmnopqrstuvwxyz');
	*/
}


if (isset($_POST['logout'])) {
	session_unset();
	session_destroy();
	echo "Logged out successful.";
} 

if (isset($_SESSION['one-page'])) {
	if ($_SESSION['one-page'] == base64_encode($_SESSION['token'].sha1($password))) {

		(!isset($_SESSION['failed-attempts']) ? $_SESSION['failed-attempts'] = 1 : $_SESSION['failed-attempts'] = 1);
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
		// (tolen == 64 ? $_SESSION['token'] = random_str(64) : $_SESSION['token'] = random_str(tolen));
		$_SESSION['token'] = random_str($tolen);
		$_SESSION['one-page'] = base64_encode($_SESSION['token'].sha1($password)); // not the best encryption/hashing, but it get's the job done.
		$self = $_SERVER['PHP_SELF'];
		header("Location: $self");
	} else {
		echo " Incorrect Password. ";

		if ($nobrute == true) {

			(!isset($_SESSION['failed-attempts']) ? $_SESSION['failed-attempts'] = 1 : $_SESSION['failed-attempts']++);

			if ($_SESSION['failed-attempts'] < $brutecount) {
				// echo "<br />" . $brutecount - $attempts . " requests left before temp-ban.";

				(($_SESSION['failed-attempts'] == 10) ? header("Location: PHP_SELF") : $useless = true);
				
				echo ($brutecount + 1) - ($_SESSION['failed-attempts'] + 1)." requests left before temp-ban.";
			} else if ($_SESSION['failed-attempts'] < $maxbrutecount) {
				// echo "<br />" . $maxbrutecount - $attempts . " requests left before perma-ban.";

				echo $maxbrutecount - $_SESSION['failed-attempts']." requests left before perma-ban.";
			}

			echo "<br /><strong style='color: red;'>Failed Attempts: " . $_SESSION['failed-attempts'] . "</strong>";

		} else if ($nobrute == false) {
			// anti-brute-force system disabled/
			(!isset($_SESSION['failed-attempts']) ? $_SESSION['failed-attempts'] = 1 : $_SESSION['failed-attempts'] = 1);

		}
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
