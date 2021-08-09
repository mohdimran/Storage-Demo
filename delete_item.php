<?php require_once('global.inc.php'); ?>
<?php
$colname_items = "0";
if (isset($_GET['id'])) {
	$colname_items = $_GET['id'];
}

if ($colname_items <> $_SESSION['MM_LastViewedItem']) {
	header("Location: items.php");
  exit;
}

$query_items = sprintf("SELECT parent_id FROM items WHERE id=%s AND user_id=%s AND type=2", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
$items = $storage->query($query_items) or die($storage->error);
$row_items = $items->fetch_assoc();
$totalRows_items = $items->num_rows;
$parent_id = $row_items['parent_id'];
$items->free();

if ($totalRows_items <= 0) {
  header("Location: items.php");
  exit;
}

$deleteSQL = sprintf("DELETE FROM items WHERE id=%s AND user_id=%s AND type=2", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
$stmt = $storage->prepare($deleteSQL) or die($storage->error);
$stmt->execute();
$stmt->close();

if (file_exists("_assets/item_img/" . $colname_items . ".jpg"))
	unlink("_assets/item_img/" . $colname_items . ".jpg");

if (file_exists("_assets/item_img/" . $colname_items . "-thumb.jpg"))
	unlink("_assets/item_img/" . $colname_items . "-thumb.jpg");

// Update folder's last update
if ($parent_id <> "0") {
	$updateSQL = sprintf("UPDATE items SET last_updated=%s WHERE id=%s AND user_id=%s AND type=1",
											 GetSQLValueString("now()", "text_no_quote"),
											 GetSQLValueString($parent_id, "int"),
											 GetSQLValueString($_SESSION['MM_UserID'], "int"));		
	$stmt = $storage->prepare($updateSQL) or die ($storage->error);
	$stmt->execute();
	$stmt->close();
}

header("Location: items.php" . (isset($parent_id) && $parent_id <> "0" ? "?parent_id=" . $parent_id : ""));
exit;
?>