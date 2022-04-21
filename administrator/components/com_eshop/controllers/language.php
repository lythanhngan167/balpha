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
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerLanguage extends JControllerLegacy
{

	function save()
	{
	    $model = $this->getModel('language');
		$input = new RADInput();
		$post  = $input->post->getData(RAD_INPUT_ALLOWRAW);
		$model->save($post);
		$lang = $post['lang'];
		$item = $post['item'];
		$url = 'index.php?option=com_eshop&view=language&lang=' . $lang . '&item=' . $item;
		$msg = JText::_('ESHOP_TRANSLATION_SAVED');
		$this->setRedirect($url, $msg);
	}

	function cancel()
	{
		$this->setRedirect('index.php?option=com_eshop&view=dashboard');
	}
}