<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
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
 * Class Request_packageRouter
 *
 */
class Request_packageRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_request_package');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$requestpackages = new RouterViewConfiguration('requestpackages');
		$this->registerView($requestpackages);
			$requestpackage = new RouterViewConfiguration('requestpackage');
			$requestpackage->setKey('id')->setParent($requestpackages);
			$this->registerView($requestpackage);
			$requestpackageform = new RouterViewConfiguration('requestpackageform');
			$requestpackageform->setKey('id');
			$this->registerView($requestpackageform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('Request_packageRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('Request_packageHelpersRequest_package', __DIR__ . '/helpers/request_package.php');
			$this->attachRule(new Request_packageRulesLegacy($this));
		}
	}


	
		/**
		 * Method to get the segment(s) for an requestpackage
		 *
		 * @param   string  $id     ID of the requestpackage to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getRequestpackageSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an requestpackageform
			 *
			 * @param   string  $id     ID of the requestpackageform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getRequestpackageformSegment($id, $query)
			{
				return $this->getRequestpackageSegment($id, $query);
			}

	
		/**
		 * Method to get the segment(s) for an requestpackage
		 *
		 * @param   string  $segment  Segment of the requestpackage to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getRequestpackageId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an requestpackageform
			 *
			 * @param   string  $segment  Segment of the requestpackageform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getRequestpackageformId($segment, $query)
			{
				return $this->getRequestpackageId($segment, $query);
			}
}
