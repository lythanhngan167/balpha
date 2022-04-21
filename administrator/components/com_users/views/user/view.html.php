<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');
jimport( 'joomla.access.access' );

/**
 * User view class.
 *
 * @since  1.5
 */
class UsersViewUser extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $grouplist;

	protected $groups;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 *
	 * @since   1.5
	 */
	public function display($tpl = null)
	{
		$this->form      = $this->get('Form');
		$this->item      = $this->get('Item');
		$this->state     = $this->get('State');
		$this->tfaform   = $this->get('Twofactorform');
		$this->otpConfig = $this->get('otpConfig');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}

		// Prevent user from modifying own group(s)
		$this->user = JFactory::getUser();
		$this->userGroup = JAccess::getGroupsByUser($this->user->id, false);

		if ((int) $this->user->id != (int) $this->item->id || $this->user->authorise('core.admin'))
		{
			$this->grouplist = $this->get('Groups');
			$this->groups    = $this->get('AssignedGroups');
		}

		$this->form->setValue('password', null);
		$this->form->setValue('password2', null);

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user      = JFactory::getUser();
		$userGroup = JAccess::getGroupsByUser($user->id, false);
		$canDo     = JHelperContent::getActions('com_users');
		$isNew     = ($this->item->id == 0);
		$isProfile = $this->item->id == $user->id;

		JToolbarHelper::title(
			JText::_(
				$isNew ? 'COM_USERS_VIEW_NEW_USER_TITLE' : ($isProfile ? 'COM_USERS_VIEW_EDIT_PROFILE_TITLE' : 'COM_USERS_VIEW_EDIT_USER_TITLE')
			),
			'user ' . ($isNew ? 'user-add' : ($isProfile ? 'user-profile' : 'user-edit'))
		);

		if ($canDo->get('core.edit') || $canDo->get('core.create'))
		{
			JToolbarHelper::apply('user.apply');
			JToolbarHelper::save('user.save');
		}
		//&& (int)$userGroup[0] != 15
		if ($canDo->get('core.create') && $canDo->get('core.manage') )
		{
			JToolbarHelper::save2new('user.save2new');
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('user.cancel');
		}
		else
		{
			JToolbarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_USERS_USER_MANAGER_EDIT');
	}

	public function listChildUserByLevel($userid,$level)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('u.*');
		$query->from('`#__users` AS u');
		$query->join('LEFT', $db->quoteName('#__user_usergroup_map', 'm') . ' ON (' . $db->quoteName('m.user_id') . ' = ' . $db->quoteName('u.id') . ')');
		$query->where('u.`invited_id` = '.$userid);
		$query->where('m.`group_id` = 3');

		if($level > 0){
			$query->where('u.`level` = '.$level);
		}
		$query->order('u.id DESC');
		$db->setQuery($query);
		//echo $query->__toString();
		$results = $db->loadObjectList();
		return $results;
	}

	public function getProvinceName($pro_id)
	{
		if($pro_id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select('ec.*');
			$query->from('`#__eshop_countries` AS ec');
			$query->where('ec.id = '.$pro_id);
			$db->setQuery($query);
			$result = $db->loadObject();
			return $result->country_name;
		}else{
			return '';
		}

	}
}
