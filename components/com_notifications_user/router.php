<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Notifications_user
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;

/**
 * Class Notifications_userRouter
 *
 */
class Notifications_userRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_notifications_user');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$notificationusers = new RouterViewConfiguration('notificationusers');
		$notificationusers->setKey('id')->setNestable();
		$this->registerView($notificationusers);
			$notificationuser = new RouterViewConfiguration('notificationuser');
			$notificationuser->setKey('id')->setParent($notificationusers, 'catid');
			$this->registerView($notificationuser);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('Notifications_userRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('Notifications_userHelpersNotifications_user', __DIR__ . '/helpers/notifications_user.php');
			$this->attachRule(new Notifications_userRulesLegacy($this));
		}
	}


	
			/**
			 * Method to get the segment(s) for a category
			 *
			 * @param   string  $id     ID of the category to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getNotificationusersSegment($id, $query)
			{
				$category = Categories::getInstance('notifications_user.notificationusers')->get($id);

				if ($category)
				{
					$path = array_reverse($category->getPath(), true);
					$path[0] = '1:root';

					if ($this->noIDs)
					{
						foreach ($path as &$segment)
						{
							list($id, $segment) = explode(':', $segment, 2);
						}
					}

					return $path;
				}

				return array();
			}
		/**
		 * Method to get the segment(s) for an notificationuser
		 *
		 * @param   string  $id     ID of the notificationuser to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getNotificationuserSegment($id, $query)
		{
			return array((int) $id => $id);
		}

	
			/**
			 * Method to get the id for a category
			 *
			 * @param   string  $segment  Segment to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getNotificationusersId($segment, $query)
			{
				if (isset($query['id']))
				{
					$category = Categories::getInstance('notifications_user.notificationusers', array('access' => false))->get($query['id']);

					if ($category)
					{
						foreach ($category->getChildren() as $child)
						{
							if ($this->noIDs)
							{
								if ($child->alias == $segment)
								{
									return $child->id;
								}
							}
							else
							{
								if ($child->id == (int) $segment)
								{
									return $child->id;
								}
							}
						}
					}
				}

				return false;
			}
		/**
		 * Method to get the segment(s) for an notificationuser
		 *
		 * @param   string  $segment  Segment of the notificationuser to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getNotificationuserId($segment, $query)
		{
			return (int) $segment;
		}
}
