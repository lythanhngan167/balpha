<?php
/**
 * @version		1.0
 * @package		Joomla
 * @subpackage	OSFramework
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined( '_JEXEC' ) or die();

class EShopViewList extends JViewLegacy
{
    public $lang_prefix = ESHOP_LANG_PREFIX;
    protected static $dropDownList = array();
    protected $showReport = false;
    public function display($tpl = null, $is_product_list = false)
    {
    	// Check access first
    	$mainframe = JFactory::getApplication();
    	$accessViews = array('orders', 'reviews', 'payments', 'shippings', 'themes', 'customers', 'customergroups', 'coupons', 'taxclasses', 'taxrates');
    	foreach ($accessViews as $view)
    	{
    		if ($view == $this->getName() && !JFactory::getUser()->authorise('eshop.'.$view, 'com_eshop'))
    		{
    			$mainframe->enqueueMessage(JText::_('ESHOP_ACCESS_NOT_ALLOW'), 'error');
				$mainframe->redirect('index.php?option=com_eshop&view=dashboard');
    		}
    	}
        $state = $this->get('State');
        $items		= $this->get( 'Data');
        $pagination = $this->get( 'Pagination' );

        $this->state = $state;

        $lists = array();
        $lists['order_Dir'] = $state->filter_order_Dir;
        $lists['order'] = $state->filter_order;
        $lists['filter_state'] = JHtml::_('grid.state', $state->filter_state, JText::_('ESHOP_PUBLISHED'), JText::_('ESHOP_UNPUBLISHED'));
        $this->_buildListArray($lists, $state);

        $this->lists        = $lists;
        $this->items        = $items;
        $this->pagination   = $pagination;

        if($is_product_list){
            $this->setExtraValue();
        }

        if($this->showReport){
            $this->reports = $this->get('Report');
        }

        $this->_buildToolbar();

        parent::display($tpl);
    }

    /**
     * Build all the lists items used in the form and store it into the array
     * @param  $lists
     * @return boolean
     */
    public function _buildListArray(&$lists, $state)
    {
        return true;
    }
    /**
     * Build the toolbar for view list
     */
    public function _buildToolbar()
    {
        $viewName = $this->getName();
        $controller = EShopInflector::singularize($this->getName());
        JToolBarHelper::title(JText::_($this->lang_prefix.'_'.strtoupper($viewName)));

        $canDo	= EshopHelper::getActions($viewName);

        if ($canDo->get('core.manage'))
        {
            JToolBarHelper::deleteList(JText::_($this->lang_prefix.'_DELETE_'.strtoupper($this->getName()).'_CONFIRM') , $controller.'.remove');
            JToolBarHelper::editList($controller.'.edit');
            JToolBarHelper::addNew($controller.'.add');
            JToolBarHelper::custom( $controller.'.copy', 'copy.png', 'copy_f2.png', JText::_('ESHOP_COPY'), true );
            JToolBarHelper::publishList($controller.'.publish');
            JToolBarHelper::unpublishList($controller.'.unpublish');
        }
    }

	public static function renderDropdownList($item = '')
	{
		$html = array();
		$html[] = '<button data-toggle="dropdown" class="dropdown-toggle btn btn-micro">';
		$html[] = '<span class="caret"></span>';
		if ($item)
		{
			$html[] = '<span class="element-invisible">' . JText::sprintf('JACTIONS', $item) . '</span>';
		}
		$html[] = '</button>';
		$html[] = '<ul class="dropdown-menu">';
		$html[] = implode('', self::$dropDownList);
		$html[] = '</ul>';
		self::$dropDownList = null;
		return implode('', $html);
	}

	public static function addDropdownList($label, $icon = '', $i = '', $task = '')
	{
		self::$dropDownList[] = '<li>'
				. '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $task . '\')">'
					. ($icon ? '<span class="icon-' . $icon . '"></span> ' : '')
						. $label
				. '</a>'
			. '</li>';
	}
}
