<div id="k2easy-filter-<?php echo $module->id; ?>" class="easyfilter<?php echo $moduleclass_sfx; ?>">
	<style>
		.fields-container input, .fields-container select {
			display: inline-block;
			margin-bottom: 5px;
			width: 200px;
		}
		.fields-container input.datepicker {
			width: 150px;
		}
		#k2easy-filter-<?php echo $module->id; ?> .btn { margin-top: 10px; }
	</style>

	<?php if($descr != "") : ?>
		<p>
			<?php echo $descr; ?>
		</p>
	<?php endif; ?>

	<form action="<?php echo JRoute::_('index.php?option=com_k2&view=itemlist&task=easyfilter'); ?>" method="get" name="K2EasyFilter">

		<?php $app=&JFactory::getApplication(); ?>
		<?php if(!$app->getCfg('sef')): ?>
		<input type="hidden" name="option" value="com_k2" />
		<input type="hidden" name="view" value="itemlist" />
		<input type="hidden" name="task" value="easyfilter" />
		<?php endif; ?>

		<div class="fields-container">
			<?php if($keyword == 1) require $fpath.DS.'keyword.php'; ?>
			<?php if($showtag == 1) require $fpath.DS.'tag_select.php'; ?>
			<?php if($showcategory == 1) echo $catsfilter; ?>
			<?php if($showauthor == 1) require $fpath.DS.'authors_select.php'; ?>
			<?php if($created == 1) require $fpath.DS.'created_range.php'; ?>
		</div>

		<?php if($showbutton) : ?>
		<input type="submit" value="<?php echo $buttontext; ?>" class="button<?php echo $moduleclass_sfx; ?> btn btn-success" />
		<?php endif; ?>

		<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
		<input type="hidden" name="restcat" value="<?php echo $catids; ?>" />
  </form>
</div>
