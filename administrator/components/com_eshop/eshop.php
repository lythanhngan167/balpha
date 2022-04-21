<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

//OS Framework
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
require_once JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/rad/bootstrap.php';

$input = JFactory::getApplication()->input;
$command = $input->get('task', 'display');

// Check for a controller.task command.
if (strpos($command, '.') !== false)
{
	list ($controller, $task) = explode('.', $command);
	$path = JPATH_COMPONENT . '/controllers/' . $controller . '.php';

	if (file_exists($path))
	{
		require_once $path;

		$className  = 'EShopController' . ucfirst($controller);
		$controller = new $className();
	}
	else
	{
		//Fallback to default controller
		$controller = new EShopController(array('entity_name' => $controller, 'name' => 'Eshop'));
	}

	$input->set('task', $task);
}
else
{
	$path = JPATH_COMPONENT . '/controller.php';
	require_once $path;
	$controller = new EshopController();
}

JFactory::getDocument()->addStyleSheet(JUri::base() . '/components/com_eshop/assets/css/style.css');

// Perform the Request task
$controller->execute($input->get('task'));
$controller->redirect();
