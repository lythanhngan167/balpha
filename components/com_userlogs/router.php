<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Userlogs
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
 * Class UserlogsRouter
 *
 */
class UserlogsRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = Factory::getApplication()->getParams('com_userlogs');
		$this->noIDs = (bool) $params->get('sef_ids');
		
		

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('UserlogsRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('UserlogsHelpersUserlogs', __DIR__ . '/helpers/userlogs.php');
			$this->attachRule(new UserlogsRulesLegacy($this));
		}
	}


	

	
}
