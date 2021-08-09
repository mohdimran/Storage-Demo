	</div>
	<!-- /content -->
	
  <?php if (isset($MM_PageType) && $MM_PageType == "simple") { ?>
  <div data-role="footer" data-position="fixed" data-tap-toggle="false" class="jqm-footer">
		<p>Version 1.0_dev</p>
		<p>&copy; Copyright 2021</p>
	</div>
  <?php } else { ?>
	<div data-role="footer" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar">
			<ul>
				<li><a href="#"<?php if (isset($section) && $section == "home") { ?> class="ui-btn-active ui-state-persist"<?php } ?> id="home" data-icon="grid" data-prefetch="true" data-transition="fade"></a></li>
				<li><a href="<?php echo MAIN_DIR; ?>/items.php"<?php if (isset($section) && $section == "item") { ?> class="ui-btn-active ui-state-persist"<?php } ?> id="items" data-icon="bullets" data-prefetch="true" data-transition="fade"></a></li>
				<li><a href="<?php echo MAIN_DIR; ?>/search.php"<?php if (isset($section) && $section == "search") { ?> class="ui-btn-active ui-state-persist"<?php } ?> id="search" data-icon="search" data-prefetch="true" data-transition="fade"></a></li>
				<li><a href="<?php echo MAIN_DIR; ?>/settings.php"<?php if (isset($section) && $section == "setting") { ?> class="ui-btn-active ui-state-persist"<?php } ?> id="settings" data-icon="bars" data-prefetch="true" data-transition="fade"></a></li>
			</ul>
		</div>
		<!-- /navbar --> 
	</div>
  <?php } ?>
	<!-- /footer --> 
	
</div>
<!-- /page -->

</body>
</html>
