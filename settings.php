<?php require_once('global.inc.php'); ?>
<?php
$MM_Access = "login";
$section = "setting";
include("inc/header.php");
?>
<h2>User Demo</h2>
<p>email@email.com</p>

<ul data-role="listview" data-inset="true">
	<li><a href="#"><img src="_assets/img/user_profile.png" alt="User Profile" class="ui-li-icon ui-corner-none">User Profile</a></li>
	<li><a href="#"><img src="_assets/img/settings.png" alt="Settings" class="ui-li-icon">Settings</a></li>
	<li><a href="#"><img src="_assets/img/activity_history.png" alt="Activity History" class="ui-li-icon">Activity History</a></li>
	<li><a href="logout.php"><img src="_assets/img/sign_out.png" alt="Sign Out" class="ui-li-icon ui-corner-none">Sign Out</a></li>
</ul>

<?php
include("inc/footer.php");
?>