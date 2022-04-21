<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\Sconfig;


defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');
require_once(JPATH_SITE . '/administrator/components/com_book/models/booking.php');

class UsersApiResourceBookform extends ApiResource
{
    /**
     * @OA\Get(
     *     path="/api/users/bookform",
     *     tags={"User"},
     *     summary="Get user info",
     *     description="Get userinfo",
     *     operationId="get",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/ChangePasswordForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ChangePasswordForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel"),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {
        $formInfo = $this->getForm();
        $this->plugin->setResponse($formInfo);

        return true;
    }

    private function getForm()
    {
        $lang = \JFactory::getLanguage();
        $extension = 'com_book';
        $base_dir = JPATH_SITE;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
        $model = new \BookModelBooking();

        $form = $model->getForm(array(), false);
        $formInfo = array();
        if ($form) {
            $formInfo = array();
            //$formInfo[] = $this->_getField($form, 'phone');
            $formInfo[] = $this->_getField($form, 'address');
            $formInfo[] = $this->_getField($form, 'booking_date');
            $formInfo[] = $this->_getField($form, 'hours');
            $formInfo[] = $this->_getField($form, 'note');


        }
        return $formInfo;
    }

    public function getRequiredFields()
    {
        $model = new \Dating_profileModelDatingprofileForm();
        $form = $model->getForm('com_dating_profile.datingprofile', 'datingprofileform', array(
            'control' => 'jform',
            'load_data' => false
        ));
        $fields = $form->getData()->toArray();
        $required = array();
        foreach ($fields as $key => $val) {
            $field = $form->getField($key);
            if ($field) {
                if ($field->getAttribute('required') === 'true') {
                    $required[] = $key;
                }
            }
        }

        return array('fields' => $fields, 'required' => $required);
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
            $data['type'] = $data['type'] ? $data['type'] : 'text';
            $data['required'] = $field->getAttribute('required') === 'true';
            $data['label'] = \JText::_($field->getAttribute('label'));
            $data['description'] = \JText::_($field->getAttribute('description'));
            $data['hint'] = \JText::_($field->getAttribute('hint'));
            $options = $field->__get('options');
            $data['options'] = $this->_getOptions($options);
            $data['readonly'] = false;
            switch ($data['name']) {

                case 'address':
                  
                    $data['default'] = reset($data['options'])['value'];
                    break;
                case 'booking_date':
                    $data['default'] = date('Y-m-d');
                    break;


            }
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