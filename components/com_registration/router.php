<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JLoader::registerPrefix('Registration', JPATH_SITE . '/components/com_registration/');

/**
 * Class RegistrationRouter
 *
 * @since  3.3
 */
class RegistrationRouter extends \Joomla\CMS\Component\Router\RouterBase
{
	/**
	 * Build method for URLs
	 * This method is meant to transform the query parameters into a more human
	 * readable form. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$session = JFactory::getSession();

		$segments = array();
		$view     = null;

		if (isset($query['task']))
		{
			$taskParts  = explode('.', $query['task']);
			$segments[] = implode('/', $taskParts);
			$view       = $taskParts[0];
			unset($query['task']);
		}

		if (isset($query['view']))
		{
			$segments[] = $query['view'];
			$view = $query['view'];

			unset($query['view']);
		}

		if (isset($query['id']))
		{
			if ($view !== null)
			{
				$segments[] = $query['id'];
			}
			else
			{
				$segments[] = $query['id'];
			}

			unset($query['id']);
		}

		return $segments;
	}

	/**
	 * Parse method for URLs
	 * This method is meant to transform the human readable URL back into
	 * query parameters. It is only executed when SEF mode is switched on.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$vars = array();
		// View is always the first element of the array

		if($segments[0] != 'registration' && $segments[0] != 'registrations' && $segments[0] != 'registrationform'){
			$str_userrname = $segments[0];
		}
		$vars['view'] = array_shift($segments);

		$session = JFactory::getSession();
		if($str_userrname != ''){
			$session->set('landingpage_username', $str_userrname);

			//$userId    = JUserHelper::getUserId($str_userrname);
			//change request: $str_userrname is userid

			JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_registration/models', 'RegistrationModel');
			$modelRegis = JModelLegacy::getInstance('RegistrationForm', 'RegistrationModel', array('ignore_request' => true));

			if($str_userrname > 0){
				$userLandingpage = $modelRegis->getUserLandingpage($str_userrname);
				if($userLandingpage > 0){
					$userId = $userLandingpage;
				}
			}else{
				$userId = JUserHelper::getUserId($str_userrname);
			}

			// $userObject = JFactory::getUser($str_userrname);
			// $userId = $userObject->id;

			if(!is_numeric($str_userrname) && $userId > 0){
				$appRedirect = JFactory::getApplication();
				$messageRedirect = '';
				$appRedirect->redirect(JUri::root().'agent/'.$userId.'.html', $messageRedirect, 'warning');
			}

			if($userId > 0){
				if($userLandingpage > 0){
					$block_landingpage = 0;
				}else{
					//$block_landingpage    = JFactory::getUser($userId)->block_landingpage;
					$block_landingpage = 1;
				}

				if($block_landingpage == 1){
					$app3 = JFactory::getApplication();
					//$message3 = 'Trang này với Đại lý "'.$str_userrname.'" tạm khoá, vui lòng liên hệ Admin hoặc Người gửi link để mở lại!';
					$message3 = '';
					//$app->redirect(JRoute::_('index.php?option=com_config&controller=config.display.templates', false), $message, 'error');
					//$app3->redirect(JRoute::_('index.php?Itemid=437', false), $message3, 'warning');
					$app3->redirect(JRoute::_('index.php?Itemid=259', false), $message3, 'warning');
				}
			}
		}

		$session = JFactory::getSession();
		$session->set('landingpage_userid', 0);
		$session->set('landingpage_pageid', '');

		if($segments[0] == 'workshop2h'){
			$session->set('landingpage_pageid', $segments[0]);
		}else{
			$session->set('landingpage_pageid', '');
		}

		if($userId > 0){
			$session->set('landingpage_userid', $userId);
		}else{
			if($str_userrname != ''){
				$app2 = JFactory::getApplication();
				$app2->redirect(JRoute::_('index.php?Itemid=259', false, 0));
				$session->set('landingpage_userid', 0);
			}

		}


		if($vars['view'] !='registration' && $vars['view'] != 'registrations' && $vars['view'] !=''){
			$vars['view'] = 'registrationform';
		}
		$model        = RegistrationHelpersRegistration::getModel($vars['view']);

		while (!empty($segments))
		{

			$segment = array_pop($segments);

			// If it's the ID, let's put on the request
			if (is_numeric($segment))
			{
				$vars['id'] = $segment;
			}
			else
			{
				$vars['task'] = $vars['view'] . '.' . $segment;
			}
		}

		return $vars;
	}
}
