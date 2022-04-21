<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class  plgUserAffiliates extends JPlugin
{
	
	function __construct(& $subject, $config)
	{
		
		$lang = JFactory::getLanguage();
		$lang->load('com_affiliatetracker', JPATH_SITE);
		
		parent::__construct($subject, $config);

	}
	
	function onUserAfterSave($user, $isnew, $success, $msg)
	{

		if ($isnew)
		{
			
			$this->create_conversion($user);
		
		}
		
		return true;
	}
	
	function create_conversion($data){
		
		$db = JFactory::getDBO();
		
		//create a log, just in case
		if (!empty($_COOKIE["atid"]) && $data['id']) {
			// if the cookie is available and the user is logged in, we check if there is a log linked to that user

			$cookiecontent = unserialize(base64_decode($_COOKIE["atid"]));
			$atid = (int)$cookiecontent["atid"] ;

			$query = " SELECT id FROM #__affiliate_tracker_logs WHERE user_id = " . $data['id'] ." AND atid = " . $atid;
			$db->setQuery($query);
			$exists = $db->loadResult();
			
			if(!$exists){
				// the record linking the atid with the user id doesn't exist. we create a new log
				$logdata['atid'] = $atid ;
				$logdata['user_id'] = $data['id'] ;
				plgSystemAffiliate_tracker::saveLog($logdata);
			}
		}
		
		//we check if this particular order had already been tracked
		$query = " SELECT id FROM #__affiliate_tracker_conversions WHERE component = 'com_users' AND type = 1 AND reference_id = ".$data['id'];
		$db->setQuery($query);
		$exists = $db->loadResult();

		//if it didn't exist, we attemp to create the conversion
		if(!$exists){

			$user_id = $data['id'];

			$conversion_data = array(
									"name" => "User registration",
									"component" => "com_users",
									"extended_name" => $data['username'],
									"type" => 1,
									"value" => (float)$this->params->get('value') ,
									"reference_id" => $data['id'],
									"approved" => $this->params->get('activation'),
									"atid" => 0
								);
			//print_r($conversion_data);die;

			require_once(JPATH_SITE.DS.'components'.DS.'com_affiliatetracker'.DS.'helpers'.DS.'helpers.php');
			AffiliateHelper::create_conversion($conversion_data, $user_id);
		}
	}
	
}


