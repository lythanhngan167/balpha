<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileViewJsonField extends JoomprofileViewJson
{
	public $_name = 'field';
	public $_path = __DIR__;

	public function config()
	{
		$itemid = $this->getId();

		$model = $this->getModel();
		$item  = $this->getObject($itemid);
		$form  = $model->getForm($item->toArray());

		$fieldname = $this->input->get('field', '');
		$field = $this->app->getField($fieldname);
		$form->loadFile($field->getxmlPath(), false, '//config');
        $form = $field->formatConfigForm($form, $item->getParams());
		$form->bind(array('params' => $item->getParams()));

		$template	= $this->getTemplate();
		$template->set('form', $form);

		$html = $template->render('admin.profile.'.$this->_name.'.config');
		$response = new stdClass();
		$response->success = true;
		$response->html  = $html;
		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}

	public function validate()
	{
		// get argumets
		$value 		= $this->input->getHtml('value');
		$field_id 	= $this->input->getInt('id');
		$user_id 	= $this->input->getInt('user_id');

		if(!$field_id){
			throw new Exception('Invalid field Id', 401);
		}

		// get all field so that caching can be used
		$fields = JoomprofileProfileHelper::getFields();
		if(count($fields) <= 0 || !isset($fields[$field_id]) || !$fields[$field_id]->published){
			throw new Exception('Field not found', 404);
		}
		$field = $this->getObject($field_id, array(), $fields[$field_id]);
		$field = $field->toObject();
		$field_instance = $this->app->getField($field->type);

		// validate field
	 	$errors = $field_instance->validate($field, $value, $user_id);

	 	$response = array();
		$response['value'] 	 = $value;
		$response['valid'] 	 = true;
		$response['message'] = '';
		if(count($errors) > 0){
			$response['valid'] 	 = false;
			$response['message'] = implode(",", $errors);
		}

		// IMP : Do not bind response with #F90JSON#, otherwise validation will not work
		echo json_encode($response);
		exit();
	}

	public function trigger()
	{
		// get argumets
		$data 		= $this->input->get('data', array(), 'array');
		$field_id 	= $this->input->getInt('id');
		$user_id 	= $this->input->getInt('user_id');

		if(!$field_id){
			throw new Exception('Invalid field Id', 401);
		}

		$triggerName = $this->input->get('triggerName');

		// get all field so that caching can be used
		$fields = JoomprofileProfileHelper::getFields();
		if(count($fields) <= 0 || !isset($fields[$field_id]) || !$fields[$field_id]->published){
			throw new Exception('Field not found', 404);
		}
		$field = $this->getObject($field_id, array(), $fields[$field_id]);
		$field = $field->toObject();
		$field_instance = $this->app->getField($field->type);


		$triggerName = 'onJoomProfile'.ucfirst($triggerName);
        $errors = array();
		if (method_exists($field_instance, $triggerName)) {
	 		$errors = $field_instance->$triggerName($field, $user_id, $data);
	 	}

	 	$response = array();
		$response['valid'] 	 = true;
		$response['message'] = '';
		if(count($errors) > 0){
			$response['valid'] 	 = false;
			$response['message'] = implode(",", $errors);
		}

		echo '#F90JSON#'.json_encode($response).'#F90JSON#';
		exit();
	}
}
