<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  plg_system_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

class  plgSystemJoomprofile extends JPlugin
{
    protected $baseUrl = 'https://www.function90.com/index.php?product=joomprofile';

	function __construct(& $subject, $config = array())
	{
		parent::__construct($subject, $config);
	}
	
	function onUserAfterDelete($user, $succes, $msg)
	{
		$userId = $user['id'];

		$sql = "DELETE FROM `#__joomprofile_field_values` WHERE `user_id` = ".$userId;
		$db = JFactory::getDBO();
		$db->setQuery($sql);
		return $db->query();
	}

    public function onInstallerBeforePackageDownload(&$url, &$headers)
    {
        // are we trying to update our extension?
        if (strpos($url, $this->baseUrl) !== 0)
        {
            return true;
        }

        // fetch download id from extension parameters, or
        // wherever you want to store them
        // Get the component information from the #__extensions table
        JLoader::import('joomla.application.component.helper');
        $component = JComponentHelper::getComponent('com_joomprofile');

        // assuming the download id provided by user is stored in component params
        // under the "update_credentials_download_id" key
        $downloadId = $component->params->get('downloadid', '');

        // bind credentials to request by appending it to the download url
        if (!empty($downloadId))
        {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . 'downloadId=' . $downloadId;
        }
        return true;
    }
}

