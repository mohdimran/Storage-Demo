<?php require_once('global.inc.php'); ?>
<?php
$colname_items = "0";
if (isset($_REQUEST['id'])) {
	$colname_items = $_REQUEST['id'];
}
$query_items = sprintf("SELECT id, parent_id, name, description FROM items WHERE id=%s AND user_id=%s AND type=1", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
$items = $storage->query($query_items) or die($storage->error);
$row_items = $items->fetch_assoc();
$totalRows_items = $items->num_rows;

if ($totalRows_items <= 0) {
  header("Location: items.php");
  exit;
}

$updateGoTo = "items.php" . (isset($row_items['parent_id']) && $row_items['parent_id'] <> "0" ? "?parent_id=" . $row_items['parent_id'] : "");
$_SESSION['MM_LastViewedFolder'] = $row_items['id'];

$form_fields = new obj;

$form_fields->folder_name = isset($_POST['folder_name']) ? $_POST['folder_name'] : $row_items['name'];
$form_fields->description = isset($_POST['description']) ? $_POST['description'] : $row_items['description'];

$prev_link = $updateGoTo;

$MM_Access = "login";
include("inc/header.php");
?>
<form action="view_folder.php" method="post" name="ViewFolderForm" id="ViewFolderForm" data-ajax="false" enctype="multipart/form-data">
	<?php if (file_exists("_assets/item_img/" . $row_items['id'] . ".jpg")) { ?>
  <div id="folder-image"><img src="_assets/item_img/<?php echo $row_items['id']; ?>.jpg"></div>
  <?php } ?>
	<h2><?php echo $form_fields->folder_name; ?></h2>
	<div align="center">
		<a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-icon-action ui-btn-icon-top">Move</a>
		<a href="#DeleteFolderDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-btn-inline ui-icon-delete ui-btn-icon-top">Delete</a>
		<div data-role="popup" id="DeleteFolderDialog" data-overlay-theme="a" data-dismissible="false" style="max-width:400px;">
			<div data-role="header">
				<h1>Delete Folder?</h1>
			</div>
			<div role="main" class="ui-content">
				<h3 class="ui-title">Are you sure you want to delete this folder?</h3>
				<p>All items and sub-folder under this folder will be deleted.</p>
				<p>This action cannot be undone.</p>
				<a href="view_folder.php?id=<?php echo $row_items['id']; ?>" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Cancel</a>
				<a href="delete_folder.php?id=<?php echo $row_items['id']; ?>" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-h" data-transition="slide">Delete</a>
			</div>
		</div>
	</div>
	<div class="ui-field-contain">
		<label for="folder_image">Folder Image:</label>
    <input type="file" name="folder_image" id="folder_image" accept="image/*" (change)="getFile($event)">
    <br>
    <label for="folder_name">Folder Name:</label>
    <?php if (isset($errors->folder_name)) { ?><div><b><font color="#ff0000"><?php echo $errors->folder_name; ?></font></b></div><?php } ?>
		<input type="text" name="folder_name" id="folder_name" value="<?php echo $form_fields->folder_name; ?>" placeholder="Enter Folder Name"<?php if (isset($errors->folder_name)) { ?> data-theme="h"<?php } ?>>
		<br>
		<label for="description">Description:</label>
		<textarea cols="40" rows="3" name="description" id="description"><?php echo $form_fields->description; ?></textarea>
	</div>

	<button type="submit" data-icon="plus" data-theme="f">Save</button>
	<a href="<?php echo $updateGoTo; ?>" data-role="button" data-icon="delete" data-theme="h" data-transition="slide" data-direction="reverse">Cancel</a>
  <input type="hidden" name="parent_id" value="<?php echo !isset($_REQUEST['parent_id']) ? "0" : $_REQUEST['parent_id']; ?>">
	<input type="hidden" name="MM_add" value="AddFolderForm">
</form>
<?php
include("inc/footer.php");
?>