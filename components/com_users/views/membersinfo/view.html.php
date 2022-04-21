<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Profile view class for Users.
 *
 * @since  1.6
 */
class UsersViewMembersinfo extends JViewLegacy
{
	protected $data;

	protected $form;

	protected $params;

	protected $state;

	/**
	 * An instance of JDatabaseDriver.
	 *
	 * @var    JDatabaseDriver
	 * @since  3.6.3
	 */
	protected $db;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed   A string if successful, otherwise an Error object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		// Get the view data.
        $this->state            = $this->get('State');
        $this->db               = $db = JFactory::getDbo();

		$app  = JFactory::getApplication();
        $user_id = $app->input->get('id');
        
        $this->user = JFactory::getUser($user_id);
        if($this->user->id > 0) {
            $this->user->province = $this->getProvinceName($this->user->province);
            if($this->user->sex) {
                $this->user->sex = (int)$this->user->sex === 1 ? 'Name' : 'Nữ';
            } else {
                $this->user->sex = 'Đang cập nhật';
            }
           
        }

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		return parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$user  = JFactory::getUser();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $user->name));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_USERS_PROFILE'));
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
