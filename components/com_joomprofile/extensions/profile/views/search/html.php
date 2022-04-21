<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewHtmlSearch extends JoomprofileViewHtml
{
	public $_name = 'search';

	public function display()
	{
		$user = JoomprofileHelperJoomla::getUserObject();
		$user = $this->app->getObject('user', $this->getPrefix(), $user->id);

		list($searchFields, $serachFieldsMapping) 	= $user->getSearchableFieldsAndMapping();

		$assets = '';
		foreach ($searchFields as $field) {
			$instance = $field->getFieldInstance();
			$assets .= $instance->getAssets(JoomprofileLibField::ON_SEARCH, $field);
		}

		$session = JoomprofileHelperJoomla::getSession();
		$searchword = $session->get('search_word', '', 'JOOMPROFILE');

		$config = $this->app->getConfig();
		$keyword_search = true;
		if(!isset($config['allow_keyword_search']) || $config['allow_keyword_search'] == false){
			$keyword_search = false;
		}

		$template 	= $this->getTemplate();
		$template->set('searchFields', $searchFields)
		 		 ->set('assets', $assets)
				 ->set('searchword', $searchword)
				 ->set('keyword_search', $keyword_search);
		return $template->render('site.'.$this->app->getName().'.'.$this->_name.'.display');
	}
}
