<?php require_once('global.inc.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "login.php";
if (!isset($_SESSION)) {
	session_start();
}
$_SESSION['MM_UserID'] = NULL;
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;

unset($_SESSION['MM_UserID']);
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);

// Reset cookie
setcookie('email', '', time()-60*60*24*365, '', COOKIE_DOMAIN);
setcookie('password', '', time()-60*60*24*365, '', COOKIE_DOMAIN);
			
if ($logoutGoTo != "") {
	header("Location: $logoutGoTo");
	exit;
}
?>