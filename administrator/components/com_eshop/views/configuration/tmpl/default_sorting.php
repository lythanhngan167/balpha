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
?>
<div class="control-group">
	<div class="control-label">
		<?php echo EshopHtmlHelper::getFieldLabel('social_enable', JText::_('ESHOP_CONFIG_DEFAULT_SORTING')); ?>
	</div>
	<div class="controls">
		<?php echo $this->lists['default_sorting']; ?>
	</div>
</div>
<table class="admintable adminform" width="90%">
	<tr>
		<?php
		for ($i = 0; $n = count($this->sortValues), $i < $n; $i++)
		{
			?>
			<td width="25%">
				<label class="checkbox">
					<input <?php echo (in_array($this->sortValues[$i], $this->sortOptions) ? 'checked' : ''); ?> type="checkbox" name="sort_options[]" value="<?php echo $this->sortValues[$i]; ?>"><?php echo $this->sortTexts[$i]; ?>
				</label>
			</td>	
			<?php
			if (($i + 1) % 4 == 0)
			{
				?>
				</tr>
				<tr>
				<?php
			}
		}
		?>
	</tr>
</table>
