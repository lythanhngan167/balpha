<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\dao\ContentDao;

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE . '/components/com_recharge/models/rechargeform.php');
class UsersApiResourceForm extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'form/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/home",
     *     tags={"User"},
     *     summary="Get home page",
     *     description="Get home page",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {
        $fields = array();
        $lang = JFactory::getLanguage();
        $extension = 'com_recharge';
        $base_dir = JPATH_SITE;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
        $model = new RechargeModelRechargeForm();

        $form = $model->getForm(array(), false);
        $result = array();

        $result['banks'] = $this->_getField($form, 'bank_name');
        $this->plugin->setResponse($result);

    }

    private function _getField($form, $name)
    {
        $data = array();
        $field = $form->getField($name);

        if ($field) {
            $data['name'] = $field->getAttribute('name');
            $data['default'] = $field->getAttribute('default');
            $data['multiple'] = $field->getAttribute('multiple');
            $data['type'] = $field->getAttribute('type');
            $data['required'] = $field->getAttribute('required') === 'true';
            $data['label'] = \JText::_($field->getAttribute('label'));
            $data['description'] = \JText::_($field->getAttribute('description'));
            $data['hint'] = \JText::_($field->getAttribute('hint'));
            $options = $field->__get('options');
            $data['options'] = $this->_getOptions($options);
        }

        return $data;
    }

    private function _getOptions($options)
    {
        $list = array();
        if ($options) {
            foreach ($options as $op) {
                $list[] = array(
                    'value' => $op->value,
                    'text' => $op->text
                );
            }
        }

        return $list;
    }


}
