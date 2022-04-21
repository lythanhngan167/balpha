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
defined('_JEXEC') or die();

/**
 * HTML View class for EShop component
 *
 * @static
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopViewTools extends JViewLegacy
{

	function display($tpl = null)
	{
		// Check access first
		$mainframe = JFactory::getApplication();
		if (!JFactory::getUser()->authorise('eshop.tools', 'com_eshop'))
		{
			$mainframe->enqueueMessage(JText::_('ESHOP_ACCESS_NOT_ALLOW'), 'error');
			$mainframe->redirect('index.php?option=com_eshop&view=dashboard');
		}
		else 
		{
			parent::display($tpl);
		}
	}
	/**
	 * 
	 * Function to create the buttons view.
	 * @param string $link targeturl
	 * @param string $image path to image
	 * @param string $text image description
	 */
	function quickiconButton($link, $image, $text, $textConfirm)
	{
		$language = JFactory::getLanguage();
		?>
		<div style="float:<?php echo ($language->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a onclick="javascript:confirmation('<?php echo $textConfirm; ?>', '<?php echo $link; ?>');" title="<?php echo $text; ?>" href="#">
					<?php echo JHtml::_('image', 'administrator/components/com_eshop/assets/icons/' . $image, $text); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
}