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
class EShopViewDownload extends EShopViewForm
{
	
	function _buildListArray(&$lists, $item)
	{
		jimport('joomla.filesystem.folder');
		$files = JFolder::files(JPATH_ROOT . '/media/com_eshop/downloads');
		sort($files);
		$options = array();
		$options[] = JHtml::_('select.option', '', JText::_('ESHOP_NONE'));
		for ($i = 0, $n = count($files); $i < $n; $i++)
		{
			$file = $files[$i];
			$options[] = JHtml::_('select.option', $file, $file);
		}
		$lists['existed_file'] = JHtml::_('select.genericlist', $options, 'existed_file', 'class="inputbox advselect"', 'value', 'text', $item->filename);
		parent::_buildListArray($lists, $item);
	}
}