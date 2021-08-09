<?php require_once('global.inc.php'); ?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
	session_start();
}

if (isset($_SESSION['MM_Origin']) && $_SESSION['MM_Origin'] != "") {
	$source = $_SESSION['MM_Origin'];
	$_SESSION['MM_Origin'] = NULL;
	unset($_SESSION['MM_Origin']);
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
	$_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['email']) || (isset($_COOKIE['email']) && isset($_COOKIE['password']))) {
  if (isset($_POST['email'])) {
		$loginUsername=$_POST['email'];
		$password=$_POST['password'];
		$source = "from_login";
	} else {
		$loginUsername=$_COOKIE['email'];
		$password=$_COOKIE['password'];
		$source = "from_cookie";
	}
	$MM_fldUserAuthorization = "";
	$MM_redirectLoginSuccess = "index.php";
	$MM_redirectLoginFailed = "login.php";
	$MM_redirecttoReferrer = true;
  
  $LoginRS__query=sprintf("SELECT id, email FROM users WHERE email=%s AND password=%s",
		GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"));
	
	$LoginRS = $storage->query($LoginRS__query) or die($storage->error);
	$row_user = $LoginRS->fetch_assoc();
	$loginFoundUser = $LoginRS->num_rows;
	
	if ($loginFoundUser) {
		$loginStrGroup = "storage_user";
		
		if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
		//declare two session variables and assign them
		$_SESSION['MM_UserID'] = $row_user['id'];
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_UserGroup'] = $loginStrGroup;
		
		// Save cookie is remember me is selected
		if (isset($_POST['remember_me']) && $_POST['remember_me'] == "Y") {
			setcookie('email', $_POST['email'], time()+60*60*24*365, '', COOKIE_DOMAIN);
			setcookie('password', $_POST['password'], time()+60*60*24*365, '', COOKIE_DOMAIN);
		} elseif (isset($_POST['remember_me']) && $_POST['remember_me'] != "Y") {
			setcookie('email', '', time()-60*60*24*365, '', COOKIE_DOMAIN);
			setcookie('password', '', time()-60*60*24*365, '', COOKIE_DOMAIN);
		}
		
		if (isset($_SESSION['PrevUrl']) && true) {
			$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];
			$_SESSION['PrevUrl'] = NULL;
			unset($_SESSION['PrevUrl']);
		}
		header("Location: " . $MM_redirectLoginSuccess );
	} else {
		$login_failed = true;
	}
}

$MM_PageType = "simple";
include("inc/header.php");
?>
<form action="<?php echo $loginFormAction; ?>" method="POST" name="LoginForm" id="LoginForm" data-ajax="false">
  <ul data-role="listview" data-inset="true" data-theme="a">
		<li data-role="list-divider">Storage Login</li>
		<li data-role="fieldcontain" class="ui-hide-label">
			<label for="username">Email Address:</label>
			<input type="email" name="email" id="email" value="" placeholder="E-Mail" autocomplete="off" autocapitalize="off" />
		</li>
		<li data-role="fieldcontain" class="ui-hide-label">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" value="" placeholder="Password" />
		</li>
		<li data-role="fieldcontain">
			<label for="remember_me">Remember Me:</label>
			<select name="remember_me" id="remember_me" data-role="slider">
				<option value="N">No</option>
				<option value="Y">Yes</option>
			</select>
		</li>
	</ul>
  <button type="submit" data-icon="lock" data-theme="a">Login</button>
	<div data-role="controlgroup"> <a href="reset_password_select.php" data-role="button" data-icon="alert" data-transition="slide">Forgot Password?</a> </div>
</form>
<?php
include("inc/footer.php");
?>