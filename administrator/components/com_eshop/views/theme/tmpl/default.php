<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();
JHtml::_('behavior.tooltip');
?>
<script type="text/javascript">	
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'theme.cancel') {
			Joomla.submitform(pressbutton, form);
			return;				
		} else {
			//Validate the entered data before submitting													
			Joomla.submitform(pressbutton, form);
		}								
	}		
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form form-horizontal">
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="adminform">
				<legend><?php echo JText::_('ESHOP_THEME_DETAILS'); ?></legend>
				<div class="control-group">
					<div class="control-label">
						<?php echo  JText::_('ESHOP_NAME'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->name; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo  JText::_('ESHOP_TITLE'); ?>
					</div>
					<div class="controls">
						<input class="text_area" type="text" name="title" id="title" size="40" maxlength="250" value="<?php echo $this->item->title;?>" />
					</div>
				</div>					
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_AUTHOR'); ?>
					</div>
					<div class="controls">
						<input class="text_area" type="text" name="author" id="author" size="40" maxlength="250" value="<?php echo $this->item->author;?>" />
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_CREATION_DATE'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->creation_date; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_COPYRIGHT'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->copyright; ?>
					</div>
				</div>	
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_LICENSE'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->license; ?>
					</div>
				</div>							
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_AUTHOR_EMAIL'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->author_email; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_AUTHOR_URL'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->author_url; ?>
					</div>
				</div>				
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_VERSION'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->version; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_DESCRIPTION'); ?>
					</div>
					<div class="controls">
						<?php echo $this->item->description; ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<?php echo JText::_('ESHOP_PUBLISHED'); ?>
					</div>
					<div class="controls">
						<?php					
							echo $this->lists['published'];					
						?>						
					</div>
				</div>
			</fieldset>				
		</div>						
		<div class="span6">
			<fieldset class="adminform">
				<legend><?php echo JText::_('ESHOP_THEME_PARAMETERS'); ?></legend>
				<?php
					foreach ($this->form->getFieldset('basic') as $field)
					{
					?>
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label;?>
							</div>					
							<div class="controls">
								<?php echo  $field->input; ?>
							</div>
						</div>	
				<?php
					}
				?>
			</fieldset>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php echo JHtml::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_eshop" />
	<input type="hidden" name="cid[]" value="<?php echo intval($this->item->id); ?>" />
	<input type="hidden" name="task" value="" />
</form>