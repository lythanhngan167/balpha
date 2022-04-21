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
JLoader::registerNamespace('api', JPATH_PLUGINS . '/api', false, false, 'psr4');




jimport('joomla.filesystem.file');

//Fix PayPal IPN sending to wrong URL
if (!empty($_POST['txn_type']) && empty($_REQUEST['task']) && empty($_REQUEST['view']))
{
	$_REQUEST['task']			= 'checkout.verifyPayment';
	$_REQUEST['payment_method']	= 'os_paypal';
}

//Require the controller
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/defines.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/inflector.php';
require_once JPATH_ROOT . '/administrator/components/com_eshop/libraries/autoload.php';
require_once JPATH_ADMINISTRATOR . '/components/com_eshop/libraries/rad/bootstrap.php';

$input = JFactory::getApplication()->input;

$command = $input->getCmd('task', 'display');

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
	require_once JPATH_COMPONENT . '/controller.php';

	$controller = new EshopController();
}

// Load Bootstrap CSS and JS
if (EshopHelper::getConfigValue('load_bootstrap_css'))
{
	EshopHelper::loadBootstrapCss();
}

if (EshopHelper::getConfigValue('load_bootstrap_js'))
{
	EshopHelper::loadBootstrapJs();
}

$siteUrl = EshopHelper::getSiteUrl();
JHtml::_('script', $siteUrl . 'components/com_eshop/assets/js/noconflict.js', false, false);
JHtml::_('script', $siteUrl . 'components/com_eshop/assets/js/eshop.js', false, false);

// Load CSS of corresponding theme
$document = JFactory::getDocument();
$theme    = EshopHelper::getConfigValue('theme');
$baseUri  = JUri::base(true);

if (JFile::exists(JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/css/style.css'))
{
	$document->addStyleSheet($baseUri . '/components/com_eshop/themes/' . $theme . '/css/style.css');
}
else
{
	$document->addStyleSheet($baseUri . '/components/com_eshop/themes/default/css/style.css');
}

// Load custom CSS file
if (JFile::exists(JPATH_ROOT . '/components/com_eshop/themes/' . $theme . '/css/custom.css'))
{
	$document->addStyleSheet($baseUri . '/components/com_eshop/themes/' . $theme . '/css/custom.css');
}
elseif (JFile::exists(JPATH_ROOT . '/components/com_eshop/themes/default/css/custom.css'))
{
	$document->addStyleSheet($baseUri . '/components/com_eshop/themes/default/css/custom.css');
}

// Perform the Request task
$controller->execute($input->getCmd('task', 'display'));
$controller->redirect();