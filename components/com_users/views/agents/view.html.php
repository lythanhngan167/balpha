<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Login view class for Users.
 *
 * @since  1.5
 */
class UsersViewAgents extends JViewLegacy
{
	protected $form;

	protected $params;

	protected $state;

	protected $user;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @since   1.5
	 */
	public function display($tpl = null)
	{
		// Get the view data.
		$this->user   = JFactory::getUser();
		$this->state = $this->get('State');
		//$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->params = $this->state->params;

		//$this->sales = $this->getListSale($this->user->id);
		//$this->agents = $this->getListAgents();
		$projectLandingpage = $this->getProjectByID(21); // toan quoc landinpage
		// $this->upgradeAgentLevel1To2(3339);
		$this->newAgents =  $this->getNewAgents($projectLandingpage['price']);
		if($this->newAgents[0]->id > 0){
			$this->agentsWillBuy = $this->newAgents;
		}else{
			$this->agentsWillBuy = $this->getAgentsWillBuy($projectLandingpage['price']);
		}

		//echo count($this->agentsWillBuy);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}
		parent::display($tpl);
	}

	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_NOTE_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}

	/**
	 * Check if state is set
	 *
	 * @param   mixed  $state  State
	 *
	 * @return bool
	 */
	public function getState($state)
	{
		return isset($this->state->{$state}) ? $this->state->{$state} : false;
	}

	public function getListSale($parentId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('us.id, us.name, us.username, us.email, us.level');
		$query->from('#__users AS us');
		//$query->join('INNER', '#__user_profiles AS up ON us.id= up.user_id');
		$query->where('parent_id = '.$parentId);
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getListAgents()
	{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('us.*')
				->from('#__users AS us')
				->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
				->where("ug.group_id = 3")
				->where("us.level IN (1,2,3,4,5)")
				->where("us.block = 0")
				->order('us.id ASC');
				//->setLimit('100');
			$db->setQuery($query);
			$result = $db->loadObjectList();
			return $result;
	}

	public function getListAgentLevel1()
	{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('us.*')
				->from('#__users AS us')
				->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
				->where("ug.group_id = 3")
				->where("us.level IN (1)")
				->where("us.block = 0")
				->order('us.id ASC');
			$db->setQuery($query);
			$result = $db->loadObjectList();
			return $result;
	}

	public function getAllBuy($agent_id)
	{
			//count all buy of agent
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('COUNT(*)')
				->from('#__customers AS cu')
				->where("cu.sale_id = ".(int) $agent_id)
				->where("cu.state = 1")
				->order("cu.id ASC");
			$db->setQuery($query);
			$result = $db->loadResult();
			return $result;
	}

	public function updateAllBuyOfAgent()
	{
			$listAgent = $this->getListAgents();
			foreach ($listAgent as $key => $agent) {
				$object = new stdClass();
				$object->id = $agent->id;
				$object->buyall = $this->getAllBuy($agent->id);
				$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
			}
	}

	public function upgradeAgentLevel1()
	{
			$listAgentLevel1 = $this->getListAgentLevel1();
			foreach ($listAgentLevel1 as $key => $agent) {
				if($agent->buyall >= 20){
					$object = new stdClass();
					$object->id = $agent->id;
					$object->level = 2;
					$object->upgraded_date = date("Y-m-d H:i:s");
					$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
				}
			}
	}

	public function upgradeAgentLevel1To2($user_id)
	{
			$user   = JFactory::getUser($user_id);
			if($user->buyall >= 10){
				$object = new stdClass();
				$object->id = $user_id;
				$object->level = 2;
				$object->upgraded_date = date("Y-m-d H:i:s");
				$result = JFactory::getDbo()->updateObject('#__users', $object, 'id');
			}
	}

	public function getNewAgents($price_data)	{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('us.id,us.name,us.username,us.id_biznet,us.level,us.money,us.autobuy,us.buyall,us.buytoday,us.buydate,us.registerDate')
					->from('#__users AS us')
					->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
					->where("ug.group_id = 3")
					->where("us.level  IN (1)")
					->where("us.block = 0")
					->where("us.autobuy = 1")
					->where("us.money >= ".(int)$price_data)
					->where("us.buyall < 2")
					->order("us.buyall ASC")
					->order("us.id ASC")
					->setLimit(1000);
				$db->setQuery($query);
				//echo $query->__toString();
				$result = $db->loadObjectList();
				return $result;
	}

	// SELECT us.id,us.name,us.username,us.id_biznet,us.level,us.money,us.autobuy,us.buyall,us.buytoday,us.buydate,us.registerDate
	// FROM wmspj_users AS us LEFT JOIN wmspj_user_usergroup_map AS ug ON ug.user_id = us.id
	// WHERE ug.group_id = 3
	// AND us.level IN (1)
	// AND us.block = 0
	// AND us.autobuy = 1
	// AND us.money >= 100000
	// AND us.buyall >= 2
	// AND DATE_FORMAT(buydate,"%Y-%m-%d") < '2020-10-18'
	// ORDER BY us.buydate ASC
	// LIMIT 10;

	public function getAgentsWillBuy($price_data)	{
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('us.*')
					->from('#__users AS us')
					->join('LEFT', '#__user_usergroup_map AS ug ON ug.user_id = us.id')
					->where("ug.group_id = 3")
					->where("us.level  IN (1)")
					->where("us.block = 0")
					->where("us.autobuy = 1")
					->where("us.buyall >= 2")
					->where("us.money >= ".(int)$price_data)
					->where("DATE_FORMAT(us.buydate,'%Y-%m-%d') <= '".date("Y-m-d")."'")
					->order("us.buydate ASC")
					->setLimit(10000);
				$db->setQuery($query);
				//echo $query->__toString();
				$result = $db->loadObjectList();
				return $result;
		}

		public function getProjectByID($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('is_recruitment,price');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }


}
