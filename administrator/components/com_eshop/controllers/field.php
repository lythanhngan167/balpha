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
use Joomla\Utilities\ArrayHelper;

/**
 * EShop controller
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopControllerField extends EShopController
{

	/**
	 * Constructor function
	 *
	 * @param array $config
	 */
	function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('un_required', 'required');
	}

	public function required()
	{
		$input = JFactory::getApplication()->input;
		$cid = $input->get('cid', array());
		$cid = ArrayHelper::toInteger($cid);
		$task = $this->getTask();
		if ($task == 'required')
		{
			$state = 1;
		}
		else
		{
			$state = 0;
		}
		$model = $this->getModel('Field');
		$model->required($cid , $state);
		$msg = JText::_('ESHOP_FIELD_REQUIRED_STATE_UPDATED');
		$this->setRedirect(JRoute::_('index.php?option=com_eshop&view=fields', false), $msg);
	}


}
