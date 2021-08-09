<?php require_once('global.inc.php'); ?>
<?php
$colname_items = "0";
if (isset($_REQUEST['id'])) {
	$colname_items = $_REQUEST['id'];
}
$query_items = sprintf("SELECT id, parent_id, name, quantity, description FROM items WHERE id=%s AND user_id=%s AND type=2", GetSQLValueString($colname_items, "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
$items = $storage->query($query_items) or die($storage->error);
$row_items = $items->fetch_assoc();
$totalRows_items = $items->num_rows;

if ($totalRows_items <= 0) {
  header("Location: items.php");
  exit;
}

$updateGoTo = "items.php" . (isset($row_items['parent_id']) && $row_items['parent_id'] <> "0" ? "?parent_id=" . $row_items['parent_id'] : "");
$_SESSION['MM_LastViewedItem'] = $row_items['id'];

function validate_form(&$errors) {
  $errors = new obj;
  $form_ok = true;
  
  if (!isset($_POST['item_name']) || $_POST['item_name'] == "") {
    $errors->item_name = "Please enter item name";
    $form_ok = false;
  }
  
  return $form_ok;
}

if (isset($_POST["MM_update"]) && $_POST["MM_update"] == "ViewItemForm") {
  $form_ok = validate_form($errors);
	
	if ($form_ok) {
		$updateSQL = sprintf("UPDATE items SET name=%s, quantity=%s, description=%s, last_updated=%s WHERE id=%s AND user_id=%s",
												 GetSQLValueString($_POST['item_name'], "text"),
												 GetSQLValueString($_POST['quantity'], "int"),
												 GetSQLValueString($_POST['description'], "text"),
												 GetSQLValueString("now()", "text_no_quote"),
												 GetSQLValueString($_POST['id'], "int"),
												 GetSQLValueString($_SESSION['MM_UserID'], "int"));		
		$stmt = $storage->prepare($updateSQL) or die ($storage->error);
		$stmt->execute();
		$stmt->close();
		
		// Update folder's last update
		if ($_POST['parent_id'] <> "0") {
			$updateSQL = sprintf("UPDATE items SET last_updated=%s WHERE id=%s AND user_id=%s AND type=1",
													 GetSQLValueString("now()", "text_no_quote"),
													 GetSQLValueString($_POST['parent_id'], "int"),
													 GetSQLValueString($_SESSION['MM_UserID'], "int"));		
			$stmt = $storage->prepare($updateSQL) or die ($storage->error);
			$stmt->execute();
			$stmt->close();
		}
		
		if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == "0") {
      include "_assets/lib/phMagick/phMagick.php";
      $width_greater = false;
      $height_greater = false;
      
      $source_image = $_FILES['item_image']['tmp_name'];
      $target_image = "_assets/item_img/" . $_POST['id'] . ".jpg";
			$thumb_image = "_assets/item_img/" . $_POST['id'] . "-thumb.jpg";
      $original_image_size = getimagesize($source_image);
      
      if ($original_image_size[0] > $original_image_size[1])
        $width_greater = true;
      else
        $height_greater = true;
      
      $phMagick = new \phMagick\Core\Runner();
      $action = new \phMagick\Action\SimpleCrop($source_image, $target_image);

			if ($width_greater) {
				$action->setWidth($original_image_size[1]);
				$action->setHeight($original_image_size[1]);
			} else {
				$action->setWidth($original_image_size[0]);
				$action->setHeight($original_image_size[0]);
			}

			$action->setTop(0);
			$action->setLeft(0);
			$action->setGravity('center');
			$phMagick->run($action);
			
			// Create thumbnail
      $action = new \phMagick\Action\Resize\Proportional($target_image, $thumb_image);
      $action->setWidth(THUMBNAIL_IMAGE_SIZE);
      $phMagick->run($action);
    }
		
		header(sprintf("Location: %s", $updateGoTo));
		exit;
	}
}

$form_fields = new obj;

$form_fields->item_name = isset($_POST['item_name']) ? $_POST['item_name'] : $row_items['name'];
$form_fields->quantity = isset($_POST['quantity']) ? $_POST['quantity'] : $row_items['quantity'];
$form_fields->description = isset($_POST['description']) ? $_POST['description'] : $row_items['description'];

$prev_link = $updateGoTo;

$MM_Access = "login";
include("inc/header.php");
?>
<form action="view_item.php" method="post" name="ViewItemForm" id="ViewItemForm" data-ajax="false" enctype="multipart/form-data">
	<?php if (file_exists("_assets/item_img/" . $row_items['id'] . ".jpg")) { ?>
  <div id="item-image"><img src="_assets/item_img/<?php echo $row_items['id']; ?>.jpg"></div>
  <?php } ?>
  <h2><?php echo $form_fields->item_name; ?></h2>
  <div align="center">
    <a href="#" class="ui-btn ui-corner-all ui-btn-inline ui-icon-action ui-btn-icon-top">Move</a>
    <a href="#DeleteItemDialog" data-rel="popup" data-position-to="window" data-transition="pop" class="ui-btn ui-corner-all ui-btn-inline ui-icon-delete ui-btn-icon-top">Delete</a>
		<div data-role="popup" id="DeleteItemDialog" data-overlay-theme="a" data-dismissible="false" style="max-width:400px;">
			<div data-role="header">
				<h1>Delete Item?</h1>
			</div>
			<div role="main" class="ui-content">
				<h3 class="ui-title">Are you sure you want to delete this item?</h3>
				<p>This action cannot be undone.</p>
				<a href="view_item.php?id=<?php echo $row_items['id']; ?>" class="ui-btn ui-corner-all ui-shadow ui-btn-inline" data-rel="back">Cancel</a>
				<a href="delete_item.php?id=<?php echo $row_items['id']; ?>" class="ui-btn ui-corner-all ui-shadow ui-btn-inline ui-btn-h" data-transition="slide">Delete</a>
			</div>
		</div>
  </div>
	<div class="ui-field-contain">
    <label for="item_image">Item Image:</label>
    <input type="file" name="item_image" id="item_image" accept="image/*" (change)="getFile($event)">
    <br>
		<label for="item_name">Item Name:</label>
    <?php if (isset($errors->item_name)) { ?><div><b><font color="#ff0000"><?php echo $errors->item_name; ?></font></b></div><?php } ?>
		<input type="text" name="item_name" id="item_name" value="<?php echo $form_fields->item_name; ?>" placeholder="Enter Item Name"<?php if (isset($errors->item_name)) { ?> data-theme="h"<?php } ?>>
		<br>
		<label for="quantity">Quantity:</label>
		<input type="range" name="quantity" id="quantity" value="<?php echo $form_fields->quantity; ?>" min="1" max="10">
		<br>
		<label for="description">Description:</label>
		<textarea cols="40" rows="3" name="description" id="description"><?php echo $form_fields->description; ?></textarea>
	</div>
  
  <button type="submit" data-icon="plus" data-theme="f">Save Changes</button>
	<a href="<?php echo $updateGoTo; ?>" data-role="button" data-icon="delete" data-theme="h" data-transition="slide" data-direction="reverse">Cancel</a>
	<input type="hidden" name="id" value="<?php echo $colname_items; ?>">
	<input type="hidden" name="parent_id" value="<?php echo $row_items['parent_id']; ?>">
	<input type="hidden" name="MM_update" value="ViewItemForm">
</form>
<?php
$items->free();
include("inc/footer.php");
?>