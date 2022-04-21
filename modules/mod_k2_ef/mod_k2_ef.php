<?php 

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view' );

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
require_once (dirname(__FILE__).DS.'helper.php');

$moduleclass_sfx = $params->get('moduleclass_sfx', '');	
$descr = $params->get('descr', '');	

$showbutton = $params->get('showbutton', 1);
$buttontext = $params->get('buttontext', 'Search');	

$onchange = $params->get('onchange', 0);

$keyword = $params->get('keyword', 1);	
$showtag = $params->get('showtag', 1);	
$showcategory = $params->get('showcategory', 1);	
$showauthor = $params->get('showauthor', 0);	
$created = $params->get('created', 0);	

$catids = $params->get('catids', '');

if($params->get('itemid_mode', 0) == 0) {
	$itemid = JRequest::getInt("Itemid");
}
else {
	$itemid = $params->get('itemid', '101');
}	

if(!JPluginHelper::isEnabled('system', 'k2easyfilter')) {
	if(JRequest::getVar("option") == "com_k2" && JRequest::getVar("view") == "itemlist" && JRequest::getVar("task") == "easyfilter") {
		echo "K2 Easy Filter plugin is not published.<br />";
	}
}

$tags = modK2EasyFilterHelper::getTags($params);

if($showcategory == 1) {
	$catsfilter = modK2EasyFilterHelper::treeselectbox($params);
}

if($showauthor == 1) {
	$authors = modK2EasyFilterHelper::getAuthors($params);
}

$fpath = JPATH_BASE.DS."modules".DS."mod_k2_ef".DS."tmpl".DS."fields";

require JModuleHelper::getLayoutPath('mod_k2_ef', 'default');
			
?>