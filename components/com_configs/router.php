<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Configs
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
 * Class ConfigsRouter
 *
 */
class ConfigsRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_configs');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$configs = new RouterViewConfiguration('configs');
		$this->registerView($configs);
			$config = new RouterViewConfiguration('config');
			$config->setKey('id')->setParent($configs);
			$this->registerView($config);
			$configform = new RouterViewConfiguration('configform');
			$configform->setKey('id');
			$this->registerView($configform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('ConfigsRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('ConfigsHelpersConfigs', __DIR__ . '/helpers/configs.php');
			$this->attachRule(new ConfigsRulesLegacy($this));
		}
	}


	
		/**
		 * Method to get the segment(s) for an config
		 *
		 * @param   string  $id     ID of the config to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getConfigSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an configform
			 *
			 * @param   string  $id     ID of the configform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getConfigformSegment($id, $query)
			{
				return $this->getConfigSegment($id, $query);
			}

	
		/**
		 * Method to get the segment(s) for an config
		 *
		 * @param   string  $segment  Segment of the config to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getConfigId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an configform
			 *
			 * @param   string  $segment  Segment of the configform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getConfigformId($segment, $query)
			{
				return $this->getConfigId($segment, $query);
			}
}
