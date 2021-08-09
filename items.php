<?php require_once('global.inc.php'); ?>
<?php
$colname_items = "0";
if (isset($_GET['parent_id'])) {
	$colname_items = $_GET['parent_id'];
}

$title = "";
$parent_id = "";

if ($colname_items <> "0") {
	$query_items = sprintf("SELECT name, parent_id FROM items WHERE id=%s AND user_id=%s AND type=1", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
	$items = $storage->query($query_items) or die($storage->error);
	$row_items = $items->fetch_assoc();
	$totalRows_items = $items->num_rows;
	$title = isset($row_items['name']) ? $row_items['name'] : "";
	$parent_id = isset($row_items['parent_id']) ? $row_items['parent_id'] : "";
	$items->free();
	
	if ($totalRows_items <= 0) {
		header("Location: items.php");
  	exit;
	}
}

$query_items = sprintf("SELECT id, parent_id, name, type, quantity, description FROM items WHERE parent_id=%s AND user_id=%s ORDER BY type ASC, last_updated DESC", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
$items = $storage->query($query_items) or die($storage->error);
$row_items = $items->fetch_assoc();
$totalRows_items = $items->num_rows;

if (isset($parent_id) && $parent_id <> "")
	$prev_link = "items.php" . ($parent_id <> "0" ? "?parent_id=" . $parent_id : "");

$MM_Access = "login";
$MM_PageID = "ItemsPage";
$section = "item";
include("inc/header.php");
?>
<form action="search.php" method="get" class="ui-listview-filter search-form" role="search" data-transition="fade">
	<label for="search-app" class="ui-hidden-accessible">Search</label>
	<input type="search" name="search" id="search-app" value="" placeholder="Search" autocapitalize="off" />
</form>

<a href="add_item.php<?php echo isset($colname_items) && $colname_items <> "0" ? "?parent_id=" . $colname_items : ""; ?>" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-left">Add Item</a>
<a href="add_folder.php<?php echo isset($colname_items) && $colname_items <> "0" ? "?parent_id=" . $colname_items : ""; ?>" class="ui-btn ui-btn-inline ui-icon-plus ui-btn-icon-left">Add Folder</a>

<?php if ($row_items > 0) { ?>
<ul data-role="listview" data-split-icon="gear" data-inset="true">
	<?php do {
	$link = "#";
	if ($row_items['type'] == "1")
		$link = "items.php?parent_id=" . $row_items['id'];
  else
    $link = "view_item.php?id=" . $row_items['id'];
	
	$items_count = $row_items['quantity'];
	if ($row_items['type'] == "1") {
		$query_items_quantity = sprintf("SELECT id FROM items WHERE parent_id=%s and type=2 and user_id=%s", GetSQLValueString($row_items['id'], "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
		$items_quantity = $storage->query($query_items_quantity) or die($storage->error);
		$tow_items_quantity = $items_quantity->fetch_assoc();
		$totalRows_items_quantity = $items_quantity->num_rows;
		$items_count = $totalRows_items_quantity;
		$items_quantity->free();
	}
	?>
	<li><a href="<?php echo $link; ?>" data-transition="slide">
		<?php if (file_exists("_assets/item_img/" . $row_items['id'] . "-thumb.jpg")) { ?>
		<img src="_assets/item_img/<?php echo $row_items['id']; ?>-thumb.jpg">
		<?php } elseif ($row_items['type'] == "1") { ?>
		<img src="_assets/item_img/folder_default.png"/>
		<?php } else { ?>
		<img src="_assets/item_img/item_default.png"/>
		<?php } ?>
		<h2><?php echo $row_items['name']; ?></h2>
		<p><?php echo $row_items['description']; ?></p><span class="ui-li-count"><?php echo $items_count; ?></span></a>
		<?php if ($row_items['type'] == "1") { ?>
		<a href="view_folder.php?id=<?php echo $row_items['id']; ?>" data-transition="slide">View Folder</a>
		<?php } ?>
	</li>
	<?php } while ($row_items = $items->fetch_assoc()); ?>
</ul>
<?php } else { ?>
<h2>Empty</h2>
<?php } ?>
<?php
$items->free();
include("inc/footer.php");
?>