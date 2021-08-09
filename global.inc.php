<?php require_once('Connections/storage.php'); ?>
<?php
class obj {};

if (!isset($_SESSION)) {
	session_start();
}

// Configure me
define("MAIN_DIR", "");
define("COOKIE_DOMAIN", "storage-demo.mohdimran.com");

// Constants
define("FOLDER", 1);
define("ITEM", 2);
define("THUMBNAIL_IMAGE_SIZE", 300);

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
	global $storage;
	
	if (PHP_VERSION < 6) {
		$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
	}

	$theValue = $storage->real_escape_string($theValue);

	switch ($theType) {
		case "text":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "text_no_quote":
			$theValue = ($theValue != "") ? $theValue : "NULL";
			break;
		case "like":
			$theValue = ($theValue != "") ? "'%" . $theValue . "%'" : "NULL";
			break;
		case "long":
		case "int":
			$theValue = ($theValue != "") ? intval($theValue) : "NULL";
			break;
		case "double":
			$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
			break;
		case "date":
			$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
			break;
		case "defined":
			$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
			break;
	}
	return $theValue;
}
}

function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
	// For security, start by assuming the visitor is NOT authorized. 
	$isValid = False; 

	// When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
	// Therefore, we know that a user is NOT logged in if that Session variable is blank. 
	if (!empty($UserName)) { 
		// Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
		// Parse the strings into arrays. 
		$arrUsers = Explode(",", $strUsers); 
		$arrGroups = Explode(",", $strGroups); 
		if (in_array($UserName, $arrUsers)) { 
			$isValid = true; 
		} 
		// Or, you may restrict access to only certain users based on their username. 
		if (in_array($UserGroup, $arrGroups)) { 
			$isValid = true; 
		} 
		if (($strUsers == "") && false) { 
			$isValid = true; 
		} 
	} 
	return $isValid; 
}

function requireLogin() {
	$MM_authorizedUsers = "storage_user";
	$MM_donotCheckaccess = "false";
	
	// *** Restrict Access To Page: Grant or deny access to this page
	$MM_restrictGoTo = "login.php";
	if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {	 
		$MM_qsChar = "?";
		$MM_referrer = $_SERVER['PHP_SELF'];
		if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
		if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
		$MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
		$MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
		header("Location: ". $MM_restrictGoTo); 
		exit;
	}
}

function updateFolderLastUpdated($folder_id) {
	global $storage;
}
?>