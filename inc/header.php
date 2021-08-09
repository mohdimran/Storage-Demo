<?php
if (isset($MM_Access) && $MM_Access == "login")
	requireLogin();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Storage Demo</title>
<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700">
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css" />
<link rel="stylesheet" href="<?php echo MAIN_DIR; ?>/_assets/css/swatches.css" />
<?php if (isset($MM_PageType) && $MM_PageType == "simple") { ?>
<link rel="stylesheet" href="<?php echo MAIN_DIR; ?>/_assets/css/jqm.css?v=<?=time();?>">
<?php } ?>
<link rel="stylesheet" href="<?php echo MAIN_DIR; ?>/_assets/css/style.css?v=<?=time();?>" />
<link rel="apple-touch-icon" href="<?php echo MAIN_DIR; ?>/_assets/img/apple-touch-icon.png" />
<link rel="manifest" href="<?php echo MAIN_DIR; ?>/manifest.json">
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
<script src="<?php echo MAIN_DIR; ?>/_assets/js/index.js"></script>
</head>
<body>	
<div data-role="page" id="<?php echo isset($MM_PageID) ? $MM_PageID : "pageid"; ?>" class="jqm-demos" data-quicklinks="true">
	<?php if (isset($MM_PageType) && $MM_PageType == "simple") { ?>
  <div data-role="header" class="jqm-header">
		<h2><img src="<?php echo MAIN_DIR; ?>/_assets/img/storage-logo.png" width="200" alt="Storage"></h2>
		<p>Organize your stuffs</p>
	</div> 
  <?php } else { ?>
	<div data-role="header" data-position="fixed" data-tap-toggle="false">
		<?php if (isset($prev_link)) { ?>
		<a href="<?php echo $prev_link; ?>" data-prefetch="false" data-icon="arrow-l" data-iconpos="left" data-mini="true" class="ui-btn-left" data-direction="reverse" data-transition="slide"><?php echo isset($prev_text) ? $prev_text : "Back"; ?></a>
		<?php } ?>
		<h1><?php echo isset($title) ? $title : "&nbsp;"; ?></h1>
		<?php if (isset($next_link)) { ?>
		<a href="<?php echo $next_link; ?>" data-prefetch="false" data-icon="arrow-r" data-iconpos="right" data-mini="true" class="ui-btn-right" data-transition="slide"><?php echo isset($prev_text) ? $prev_text : "Next"; ?></a>
		<?php } ?>
		<!-- /navbar --> 
	</div>
  <?php } ?>
	<!-- /header -->
	
	<div role="main" class="ui-content jqm-content jqm-fullwidth">