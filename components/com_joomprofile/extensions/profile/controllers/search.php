<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileControllerSearch extends JoomprofileController
{
	public $_name = 'search';

	public function display()
	{
		$conditions = $this->input->get('joomprofile-searchfield', array(), 'array');

		// slashes cause errors, <> get stripped anyway later on. # causes problems.
		$badchars = array('#', '>', '<', '\\');
		$searchword = trim(str_replace($badchars, '', $this->input->getString('searchword', null, 'post')));
		// if searchword enclosed in double quotes, strip quotes and do exact match
		if (substr($searchword, 0, 1) == '"' && substr($searchword, -1) == '"')
		{
			$searchword = substr($searchword, 1, -1);
		}
		else
		{
			$searchword = $searchword;
		}

		$session = JoomprofileHelperJoomla::getSession();
		$prev_search_word = $session->get('search_word', '', 'JOOMPROFILE');
		if($prev_search_word !== $searchword){
			//reset all condition
			$session->set('search_conditions', array(), 'JOOMPROFILE');
			$session->set('search_word', $searchword, 'JOOMPROFILE');
		}
		else{
			$searchword = $session->get('search_word', '', 'JOOMPROFILE');
		}

		if(!empty($conditions)){
			//reset all condition
			$session->set('search_conditions', array(), 'JOOMPROFILE');

			$fields = JoomprofileProfileHelper::getFields();

			$allowedFields = array();
			$user = JoomprofileHelperJoomla::getUserObject();
			$user = $this->app->getObject('user', $this->getPrefix(), $user->id);

			list($allowedFields, $allowedMapping) 	= $user->getSearchableFieldsAndMapping();

			foreach($conditions as $id => $value){
				if(isset($fields[$id]) && $fields[$id]->published && isset($allowedFields[$id])){
					JoomprofileProfileHelper::updateSearchField($id, $value);
				}
				else{
					//@TODO : throw error
				}
			}

			$this->redirect_url = 'index.php?option=com_joomprofile&view=profile&task=search.display';
			return false;
		}

		return true;
	}

	public function update()
	{
		$conditions = $this->input->get('joomprofile-searchfield', array(), 'array');

		$fields = JoomprofileProfileHelper::getFields();

		$allowedFields = array();
		$user = JoomprofileHelperJoomla::getUserObject();
		$user = $this->app->getObject('user', $this->getPrefix(), $user->id);

		list($allowedFields, $allowedMapping) = $user->getSearchableFieldsAndMapping();
		//echo json_encode($conditions);die;
		foreach($conditions as $condition){
			if(isset($fields[$condition['fieldid']]) && $fields[$condition['fieldid']]->published && isset($allowedFields[$condition['fieldid']])){
				JoomprofileProfileHelper::updateSearchField($condition['fieldid'], $condition['value']);
			}
			else{
				//@TODO : throw error
			}
		}

		$orderby = $this->input->getWord('sortby', 'name');
		$orderin = $this->input->getWord('sortin', 'asc');

		$view = $this->get_view();
		list($results, $total) = JoomprofileProfileHelper::getSearchResults(1, $orderby, $orderin);
		$view->results = $results;
		$view->total = $total;
		return true;
	}

	public function loadMore()
	{
		$orderby = $this->input->getWord('sortby', 'name');
		$orderin = $this->input->getWord('sortin', 'asc');

		$view = $this->get_view();
		list($results, $total) = JoomprofileProfileHelper::getSearchResults($this->input->getInt('page', 1), $orderby, $orderin);
		$view->results = $results;
		$view->total = $total;
		return true;
	}

	public function sort()
	{
		$orderby = $this->input->getWord('sortby', 'name');
		$orderin = $this->input->getWord('sortin', 'asc');

		$view = $this->get_view();
		list($results, $total) = JoomprofileProfileHelper::getSearchResults(1, $orderby, $orderin);
		$view->results = $results;
		$view->total = $total;
		return true;
	}
}
