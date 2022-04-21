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

JToolBarHelper::title(JText::_( 'ESHOP_TRANSLATION'), 'generic.png');
JToolBarHelper::addNew('new_item', 'ESHOP_NEW_ITEM');
JToolBarHelper::apply('language.save');
JToolBarHelper::cancel('language.cancel');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton)
	{
		var form = document.adminForm;
		if (pressbutton == 'new_item') {
			Joomla.newLanguageItem();
			return;				
		} else {
			Joomla.submitform(pressbutton, form);
		}
	}
	Joomla.newLanguageItem = function() {
		table = document.getElementById('lang_table');
		row = table.insertRow(1);
		cell0  = row.insertCell(0);
		cell0.innerHTML = '<input type="text" name="extra_keys[]" class="inputbox" size="50" />';
		cell1 = row.insertCell(1);
		cell2 = row.insertCell(2);
		cell2.innerHTML = '<input type="text" name="extra_values[]" class="inputbox" size="100" />';
	}
</script>
<form action="index.php?option=com_eshop&view=language" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>			
			<td style="text-align: left;">
				<?php echo JText::_( 'ESHOP_FILTER' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search'];?>" class="text_area search-query" onchange="document.adminForm.submit();" />		
				<button onclick="this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_GO' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();" class="btn"><?php echo JText::_( 'ESHOP_RESET' ); ?></button>
			</td>
			<td style="text-align: right">
				<?php echo JText::_('ESHOP_TRANSLATION_INTRO'); ?>
				<?php echo $this->lists['lang']; ?>
				<?php echo $this->lists['item']; ?>
			</td>
		</tr>
	</table>
	<table class="adminlist table table-striped" id="lang_table">
		<thead>
			<tr>
				<th class="text_left" width="20%">
					<?php echo JText::_( 'ESHOP_KEY' ); ?>
				</th>
				<th class="text_left" width="35%">
					<?php echo JText::_( 'ESHOP_ORIGINAL' ); ?>
				</th>
				<th class="text_left" width="35%">
					<?php echo JText::_( 'ESHOP_TRANSLATION' ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="4"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tfoot>
		<tbody>
			<?php
			$item = $this->item;
			if (strpos($item, 'admin.') !== false)
				$item = substr($item, 6);
			$original = $this->trans['en-GB'][$item];
			$trans = $this->trans[$this->lang][$item];
			$search = $this->lists['search'];
			foreach ($trans as $key => $value)
			{
				$show = true;
				if (isset($trans[$key]))
				{
					$translatedValue = $trans[$key];
					$missing = false;
				}
				else
				{
					$translatedValue = $value;
					$missing = true;
				}								
				?>
				<tr>
					<td><?php echo $key; ?></td>
					<td><?php echo $value; ?></td>
					<td>
						<input type="hidden" name="keys[]" value="<?php echo $key; ?>" />
						<input type="text" name="<?php echo $key; ?>" class="input-xxlarge" value="<?php echo htmlspecialchars($translatedValue); ?>" />
						<?php
							if ($missing)
							{
								?>
								<span style="color:red;">*</span>
								<?php
							}
						?>
					</td>
				</tr>
				<?php				
			}
			?>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_eshop" />	
	<input type="hidden" name="task" value="" />			
	<?php echo JHtml::_( 'form.token' ); ?>
</form>