v<?php require_once('global.inc.php'); ?>
<?php
$section = "search";
include("inc/header.php");
?>
<form action="search.php" method="get" class="ui-listview-filter search-form" role="search" data-transition="slide">
	<label for="search-app" class="ui-hidden-accessible">Search</label>
	<input type="search" name="search" id="search-app" value="" placeholder="Search" autocapitalize="off" />
</form>

<img src="<?php echo MAIN_DIR; ?>/_assets/img/ogy.png" />

<?php
include("inc/footer.php");
?>