<?php
/**
 * @version        1.0
 * @package        Joomla
 * @subpackage    OSFramework
 * @author    Giang Dinh Truong
 * @copyright    Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopViewForm extends JViewLegacy
{

    /**
     * Language prefix
     * @var string
     */
    public $lang_prefix = ESHOP_LANG_PREFIX;

    public function display($tpl = null)
    {
        // Check access first
        $mainframe = JFactory::getApplication();
        $accessViews = array('orders', 'reviews', 'payments', 'shippings', 'themes', 'customers', 'customergroups', 'coupons', 'taxclasses', 'taxrates', 'import');
        foreach ($accessViews as $view) {
            if (EShopInflector::singularize($view) == $this->getName() && !JFactory::getUser()->authorise('eshop.' . $view, 'com_eshop')) {
                $mainframe->enqueueMessage(JText::_('ESHOP_ACCESS_NOT_ALLOW'), 'error');
                $mainframe->redirect('index.php?option=com_eshop&view=dashboard');
            }
        }
        $db = JFactory::getDbo();
        $item = $this->get('Data');
        $lists = array();
        if ($item && property_exists($item, 'published'))
            //$lists['published'] = JHtml::_('select.booleanlist', 'published', ' class="inputbox" ', isset($item->published) ? $item->published : 1);
            $lists['published'] = EshopHtmlHelper::getBooleanInput('published', isset($item->published) ? $item->published : 1);

        if (isset($item->access)) {
            if (version_compare(JVERSION, '1.6.0', 'ge')) {
                $lists['access'] = JHtml::_('access.level', 'access', $item->access, 'class="inputbox"', false);
            } else {
                $sql = 'SELECT id AS value, name AS text' . ' FROM #__groups' . ' ORDER BY id';
                $db->setQuery($sql);
                $groups = $db->loadObjectList();
                $lists['access'] = JHtml::_('select.genericlist', $groups, 'access', 'class="inputbox" ', 'value', 'text', $item->access);
            }
        }
        if ($this->get('translatable')) {
            if (JLanguageMultilang::isEnabled()) {
                $this->languages = EshopHelper::getLanguages();
                $this->languageData = EshopHelper::getLanguageData();
            }
            $this->translatable = true;
        } else {
            $this->translatable = false;
        }

        $this->_buildListArray($lists, $item);

        $this->item = $item;
        $this->lists = $lists;

        $this->_buildToolbar();

        parent::display($tpl);
    }

    /**
     * Build all the lists items used in the form and store it into the array
     * @param  $lists
     * @return boolean
     */
    public function _buildListArray(&$lists, $item)
    {

        return true;
    }

    /**
     * Build the toolbar for view list
     */
    public function _buildToolbar()
    {
        $input = JFactory::getApplication()->input;
        $viewName = $this->getName();
        $canDo = EshopHelper::getActions($viewName);
        $edit = $input->get('edit');
        $text = $edit ? JText::_($this->lang_prefix . '_EDIT') : JText::_($this->lang_prefix . '_NEW');
        JToolBarHelper::title(JText::_($this->lang_prefix . '_' . $viewName) . ': <small><small>[ ' . $text . ' ]</small></small>');
        if ($canDo->get('core.edit') || $canDo->get('core.create')) {
            JToolBarHelper::apply($viewName . '.apply');
            JToolBarHelper::save($viewName . '.save');
            JToolBarHelper::save2new($viewName . '.save2new');
        }
        if ($edit)
            JToolBarHelper::cancel($viewName . '.cancel', 'JTOOLBAR_CLOSE');
        else
            JToolBarHelper::cancel($viewName . '.cancel');
    }
}
