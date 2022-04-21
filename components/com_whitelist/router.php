<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Whitelist
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
 * Class WhitelistRouter
 *
 */
class WhitelistRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_whitelist');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$whitelists = new RouterViewConfiguration('whitelists');
		$this->registerView($whitelists);
			$whitelist = new RouterViewConfiguration('whitelist');
			$whitelist->setKey('id')->setParent($whitelists);
			$this->registerView($whitelist);
			$whitelistform = new RouterViewConfiguration('whitelistform');
			$whitelistform->setKey('id');
			$this->registerView($whitelistform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('WhitelistRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('WhitelistHelpersWhitelist', __DIR__ . '/helpers/whitelist.php');
			$this->attachRule(new WhitelistRulesLegacy($this));
		}
	}


	
		/**
		 * Method to get the segment(s) for an whitelist
		 *
		 * @param   string  $id     ID of the whitelist to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getWhitelistSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an whitelistform
			 *
			 * @param   string  $id     ID of the whitelistform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getWhitelistformSegment($id, $query)
			{
				return $this->getWhitelistSegment($id, $query);
			}

	
		/**
		 * Method to get the segment(s) for an whitelist
		 *
		 * @param   string  $segment  Segment of the whitelist to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getWhitelistId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an whitelistform
			 *
			 * @param   string  $segment  Segment of the whitelistform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getWhitelistformId($segment, $query)
			{
				return $this->getWhitelistId($segment, $query);
			}
}
