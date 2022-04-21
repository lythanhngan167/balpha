<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewJsonSearch extends JoomprofileViewjson
{
	public $_name = 'search';

	public function update()
	{
		// conditions
		$user = JoomprofileHelperJoomla::getUserObject();
		$user = $this->app->getObject('user', $this->getPrefix(), $user->id);

		list($searchFields, $serachFieldsMapping) 	= $user->getSearchableFieldsAndMapping();
		$searchConditions = JoomprofileProfileHelper::getSearchConditions();

		$template 	= $this->getTemplate();
		$template->set('searchFields', $searchFields)
				->set('searchConditions', $searchConditions);
		$conditions = $template->render('site.'.$this->app->getName().'.'.$this->_name.'.conditions');


		$filters = $template->render('site.'.$this->app->getName().'.'.$this->_name.'.filter');

		// result
		$results = $this->results;

		$template	= $this->getTemplate();
		$tmpl = 'site.'.$this->app->getName().'.'.$this->_name.'.result';

		$template->set('users', $results)
					->set('total', $this->total)
					->set('searchFields', $searchFields)
					->set('serachFieldsMapping', $serachFieldsMapping)
					->set('searchConditions', $searchConditions);

		$response = new stdClass();
		$response->show_button = true;
		$config = $this->app->getConfig();
		$limit = (isset($config['search_result_counter']) && !empty($config['search_result_counter']))
						? $config['search_result_counter'] : JOOMPROFILE_PROFILE_LIMIT ;

		if(count($results) < $limit){
			$response->show_button = false;
		}
		$response->html = $template->render($tmpl);
		$response->conditions = $conditions;
		$response->filters = $filters;

		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function loadMore()
	{
		$results = $this->results;
		$total	 = $this->total;
		// conditions
		$user = JoomprofileHelperJoomla::getUserObject();
		$user = $this->app->getObject('user', $this->getPrefix(), $user->id);

		list($searchFields, $serachFieldsMapping) 	= $user->getSearchableFieldsAndMapping();
		$searchConditions = JoomprofileProfileHelper::getSearchConditions();

		$template	= $this->getTemplate();
		$tmpl = 'site.'.$this->app->getName().'.'.$this->_name.'.result';

		$template->set('users', $results)
					->set('total', $total)
					->set('searchFields', $searchFields)
					->set('serachFieldsMapping', $serachFieldsMapping)
					->set('searchConditions', $searchConditions);

		$response = new stdClass();
		$response->show_button = true;
		$page = $this->input->getInt('page', 1);

		$config = $this->app->getConfig();
		$limit = (isset($config['search_result_counter']) && !empty($config['search_result_counter']))
						? $config['search_result_counter'] : JOOMPROFILE_PROFILE_LIMIT ;

		if($total <= ($limit * $page)){
			$response->show_button = false;
		}

		$response->html = $template->render($tmpl);
		
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function sort()
	{
		return $this->update();
	}
}
