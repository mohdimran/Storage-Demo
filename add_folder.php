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
  
  if (!isset($_POST['folder_name']) || $_POST['folder_name'] == "") {
    $errors->folder_name = "Please enter folder name";
    $form_ok = false;
  }
  
  return $form_ok;
}

if (isset($_POST["MM_add"]) && $_POST["MM_add"] == "AddFolderForm") {
	$form_ok = validate_form($errors);
  
  if ($form_ok) {
    $insertSQL = sprintf("INSERT INTO items (user_id, parent_id, type, name, description) VALUES (%s, %s, %s, %s, %s)",
												 GetSQLValueString($_SESSION['MM_UserID'], "int"),
												 GetSQLValueString($_POST['parent_id'], "int"),
												 GetSQLValueString(FOLDER, "int"),
												 GetSQLValueString($_POST['folder_name'], "text"),
												 GetSQLValueString($_POST['description'], "text"));		
		$stmt = $storage->prepare($insertSQL) or die ($storage->error);
		$stmt->execute();
    $insert_id = $stmt->insert_id;
		$stmt->close();
    
    if (isset($_FILES['folder_image']) && $_FILES['folder_image']['error'] == "0") {
      include "_assets/lib/phMagick/phMagick.php";
      $width_greater = false;
      $height_greater = false;
      
      $source_image = $_FILES['folder_image']['tmp_name'];
      $target_image = "_assets/item_img/" . $insert_id . ".jpg";
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
    }

		header(sprintf("Location: %s", $insertGoTo));
		exit;
  }
}
	
$MM_Access = "login";
include("inc/header.php");
?>
<h2>Add Folder</h2>
<form action="add_folder.php" method="post" name="AddFolderForm" id="AddFolderForm" data-ajax="false" enctype="multipart/form-data">
	<div class="ui-field-contain">
		<label for="folder_image">Folder Image:</label>
    <input type="file" name="folder_image" id="folder_image" accept="image/*" (change)="getFile($event)">
    <br>
    <label for="folder_name">Folder Name:</label>
    <?php if (isset($errors->folder_name)) { ?><div><b><font color="#ff0000"><?php echo $errors->folder_name; ?></font></b></div><?php } ?>
		<input type="text" name="folder_name" id="folder_name" value="" placeholder="Enter Folder Name"<?php if (isset($errors->folder_name)) { ?> data-theme="h"<?php } ?>>
		<br>
		<label for="description">Description:</label>
		<textarea cols="40" rows="3" name="description" id="description"><?php echo isset($_POST['description']) ? $_POST['description'] : ""; ?></textarea>
	</div>

	<button type="submit" data-icon="plus" data-theme="f">Save</button>
	<a href="<?php echo $insertGoTo; ?>" data-role="button" data-icon="delete" data-theme="h">Cancel</a>
  <input type="hidden" name="parent_id" value="<?php echo !isset($_REQUEST['parent_id']) ? "0" : $_REQUEST['parent_id']; ?>">
	<input type="hidden" name="MM_add" value="AddFolderForm">
</form>
<?php
include("inc/footer.php");
?>