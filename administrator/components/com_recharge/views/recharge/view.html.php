<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Recharge
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2019 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
jimport( 'joomla.access.access' );

/**
 * View to edit
 *
 * @since  1.6
 */
class RechargeViewRecharge extends \Joomla\CMS\MVC\View\HtmlView
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->item  = $this->get('Item');
		$this->form  = $this->get('Form');
		$this->user = JFactory::getUser();
		$this->userGroup = JAccess::getGroupsByUser($this->user->id, false);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);

		$user  = Factory::getUser();
		$isNew = ($this->item->id == 0);

		if (isset($this->item->checked_out))
		{
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		}
		else
		{
			$checkedOut = false;
		}

		$canDo = RechargeHelper::getActions();

		JToolBarHelper::title(Text::_('COM_RECHARGE_TITLE_RECHARGE'), 'recharge.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || ($canDo->get('core.create'))))
		{
			JToolBarHelper::apply('recharge.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('recharge.save', 'JTOOLBAR_SAVE');
		}

		//Comment để ẩn nút lưu và thêm mới ở com_recharge
		if (!$checkedOut && ($canDo->get('core.create')))
		{
			JToolBarHelper::custom('recharge.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		}

		// ẩn nút Lưu và thêm mới theo user group
		// if (!$checkedOut && ($canDo->get('core.create')))
		// {
		// 	$userGroup = JAccess::getGroupsByUser($user->id, false);
		// 	if($userGroup[0] && (int)$userGroup[0] !== 15) {
		// 		JToolBarHelper::custom('recharge.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		// 	}
		// }

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create'))
		{
			JToolBarHelper::custom('recharge.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}

		// Button for version control
		if ($this->state->params->get('save_history', 1) && $user->authorise('core.edit')) {
			JToolbarHelper::versions('com_recharge.recharge', $this->item->id);
		}

		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('recharge.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('recharge.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	public function generateRandomString($length = 10) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function getListSale($group = 3)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('us.*');
		$query->from('`#__users` AS us');
		$query->join("INNER", "#__user_usergroup_map AS ug ON ug.user_id = us.id");
		$query->where('ug.group_id = '.$group);
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
}
