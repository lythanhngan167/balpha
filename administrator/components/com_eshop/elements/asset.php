<?php
/**
 * @version		3.1.0
 * @package		Joomla
 * @subpackage	EShop
 * @author		Giang Dinh Truong
 * @copyright	Copyright (C) 2010 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();
jimport('joomla.form.formfield');

class JFormFieldAsset extends JFormField {
    protected $type = 'Asset';
    protected function getInput() {
        $doc = JFactory::getDocument();
        $doc->addScript(JURI::root().$this->element['path'].'script.js');
        $doc->addStyleSheet(JURI::root().$this->element['path'].'style.css');        
        return null;
    }
}