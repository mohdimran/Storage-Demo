<?php require_once('global.inc.php'); ?>
<?php
$insertGoTo = "items.php" . (isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] <> "0" ? "?parent_id=" . $_REQUEST['parent_id'] : "");

// Check if parent id exists and belongs to this user
if (isset($_REQUEST['parent_id']) && $_REQUEST['parent_id'] <> "0") {
  $query_items = sprintf("SELECT name FROM items WHERE id=%s and type=1 and user_id=%s", GetSQLValueString($_REQUEST['parent_id'], "int"), GetSQLValueString($_SESSION['MM_UserID'], "int"));
  $items = $storage->query($query_items) or die($storage->error);
  $row_items = $items->fetch_assoc();
  $totalRows_items = $items->num_rows;
  $items->free();
  
  if ($totalRows_items <= 0) {
    header("Location: items.php");
    exit;
  }
}

function validate_form(&$errors) {
  $errors = new obj;
  $form_ok = true;
  
  if (!isset($_POST['item_name']) || $_POST['item_name'] == "") {
    $errors->item_name = "Please enter item name";
    $form_ok = false;
  }
  
  return $form_ok;
}

if (isset($_POST["MM_add"]) && $_POST["MM_add"] == "AddItemForm") {
  $form_ok = validate_form($errors);
  
  if ($form_ok) {
		$insertSQL = sprintf("INSERT INTO items (user_id, parent_id, type, name, quantity, description) VALUES (%s, %s, %s, %s, %s, %s)",
												 GetSQLValueString($_SESSION['MM_UserID'], "int"),
												 GetSQLValueString($_POST['parent_id'], "int"),
												 GetSQLValueString(ITEM, "int"),
												 GetSQLValueString($_POST['item_name'], "text"),
												 GetSQLValueString($_POST['quantity'], "int"),
												 GetSQLValueString($_POST['description'], "text"));		
		$stmt = $storage->prepare($insertSQL) or die ($storage->error);
		$stmt->execute();
    $insert_id = $stmt->insert_id;
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
      
      //move_uploaded_file($_FILES['item_image']['tmp_name'], $original_image);
      $source_image = $_FILES['item_image']['tmp_name'];
      $target_image = "_assets/item_img/" . $insert_id . ".jpg";
      $thumb_image = "_assets/item_img/" . $insert_id . "-thumb.jpg";
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
      
      //move_uploaded_file($_FILES['item_image']['tmp_name'], "_assets/item_img/" . $insert_id . "." . pathinfo($_FILES['item_image']['name'], PATHINFO_EXTENSION));
    }

		header(sprintf("Location: %s", $insertGoTo));
		exit;
  }
}

$MM_Access = "login";
include("inc/header.php");
?>
<h2>Add Item</h2>
<form action="add_item.php" method="post" name="AddItemForm" id="AddItemForm" data-ajax="false" enctype="multipart/form-data">
	<div class="ui-field-contain">
		<label for="item_image">Item Image:</label>
    <input type="file" name="item_image" id="item_image" accept="image/*" (change)="getFile($event)">
    <br>
    <label for="item_name">Item Name:</label>
    <?php if (isset($errors->item_name)) { ?><div><b><font color="#ff0000"><?php echo $errors->item_name; ?></font></b></div><?php } ?>
		<input type="text" name="item_name" id="item_name" value="" placeholder="Enter Item Name"<?php if (isset($errors->item_name)) { ?> data-theme="h"<?php } ?>>
		<br>
		<label for="quantity">Quantity:</label>
		<input type="range" name="quantity" id="quantity" value="<?php echo isset($_POST['quantity']) ? $_POST['quantity'] : "1"; ?>" min="1" max="10">
		<br>
		<label for="description">Description:</label>
		<textarea cols="40" rows="3" name="description" id="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ""; ?></textarea>
	</div>

	<button type="submit" data-icon="plus" data-theme="f">Save</button>
	<a href="<?php echo $insertGoTo; ?>" data-role="button" data-icon="delete" data-theme="h">Cancel</a>
  <input type="hidden" name="parent_id" value="<?php echo !isset($_REQUEST['parent_id']) ? "0" : $_REQUEST['parent_id']; ?>">
	<input type="hidden" name="MM_add" value="AddItemForm">
</form>
<?php
include("inc/footer.php");
?>