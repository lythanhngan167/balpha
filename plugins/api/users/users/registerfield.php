<?php

use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR . '/components/com_users/models/user.php');

class  UsersApiResourceRegisterfield extends ApiResource
{
    private $info = array();


    public function get()
    {
        $user = JFactory::getUser();


        $fields = array();
        $lang = \JFactory::getLanguage();
        $extension = 'com_users';
        $base_dir = JPATH_ADMINISTRATOR;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
        $model = new \UsersModelUser();
        $form = $model->getForm();



        if ($form) {
            if ($form) {
                $info = array();
                $info['label'] = 'THÔNG TIN ĐĂNG KÝ';
                $info['icon'] = 'ios-person-outline';
                $info['fields'] = array();
                $info['fields'][] = $this->_getField($form, 'username');
                $info['fields'][] = $this->_getField($form, 'name');
                $info['fields'][] = $this->_getField($form, 'email');
                $info['fields'][] = $this->_getField($form, 'level');
                // $info['fields'][] = $this->_getField($form, 'bank_name');
                // $info['fields'][] = $this->_getField($form, 'bank_account_name');
                // $info['fields'][] = $this->_getField($form, 'bank_account_number');
                $fields[] = $info;


            }
        }
        $this->plugin->setResponse($fields);
        return true;

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
            $data['required'] = $field->getAttribute('required') === 'true';
            $data['label'] = \JText::_($field->getAttribute('label'));
            $data['description'] = \JText::_($field->getAttribute('description'));
            $data['hint'] = \JText::_($field->getAttribute('hint'));
            $options = $field->__get('options');
            if ($options) {
                if ($data['name'] == 'level') {
                    $data['options'] = $this->_getLevel($options);
                } else {
                    $data['options'] = $this->_getOptions($options);
                }
            } else {
                if ($data['name'] == 'province') {
                    $data['type'] = 'list';
                    $sql = 'SELECT id as `value`, country_name as `text` FROM #__eshop_countries WHERE 	published = 1';
                    $db = JFactory::getDbo();
                    $options = $db->setQuery($sql)->loadObjectList();
                    $data['options'] = $this->_getOptions($options);

                }
            }


            if (isset($this->info[$data['name']]) && $this->info[$data['name']]) {
                switch ($data['name']) {
                    case 'card_front':
                        $data['file'] = $this->info[$data['name']];
                        $data['default'] = $this->getImage($this->info['card_front']);
                        break;

                    case 'card_behind':
                        $data['file'] = $this->info[$data['name']];
                        $data['default'] = $this->getImage($this->info['card_behind']);
                        break;



                    default:
                        if ($data['multiple']) {
                            $data['default'] = explode(',', $this->info[$data['name']]);
                        } else {
                            $data['default'] = $this->info[$data['name']];
                        }

                }
            }
            if ($data['name'] == 'residence' && $data['default'] == '') {
                $data['default'] = 31;
            }
            switch ($data['name']) {
                case 'card_front':
                case 'card_behind':
                    $data['type'] = 'file';
                    break;
                case 'province':
                    $data['type'] = 'list';
                    break;
                case 'email':
                    $data['label'] = 'Email';
                    break;
                case 'username':
                    $data['label'] = 'Số điện thoại';
                    break;
                case 'address':
                    $data['label'] = 'Địa chỉ đầy đủ (Số nhà, Tên đường, Phường/Xã/Thị Trấn, Quận/Huyện/Thị Xã)';
                    break;
            }
        }

        if ($this->isEdit) {
            $data['defaultText'] = '';
            switch ($data['name']) {
                case 'username':
                case 'email':
                    $data['readonly'] = true;
                    break;
                case 'level':
                    $data['readonly'] = true;



                    break;

                default:
                    $data['readonly'] = false;

            }

            switch ($data['type']){
                case 'list':
                    foreach ($data['options'] as $item){
                        if($data['default'] == $item['value']){
                            $data['defaultText'] = $item['text'];
                        }
                    }
                    break;
            }
        }

        if ($data['name'] == 'province' && $data['default'] === '') {
            $data['default'] = 1;
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

    private function _getLevel($options)
    {
        $user = JFactory::getUser();
        $list = array();
        if ($options) {
            $level = $user->level_tree;
            if ($this->isEdit) {
                $level = 0;
            }
            foreach ($options as $op) {
                if ($level < $op->value) {
                    $list[] = array(
                        'value' => $op->value,
                        'text' => $op->text
                    );
                }

            }
        }
        return $list;
    }

    public function getImage($image_path, $thumbnailWidth = 0, $thumbnailHeight = 0)
    {
        if ($image_path && \JFile::exists(JPATH_ROOT . '/images/profile/' . $image_path)) {
            if($thumbnailWidth && $thumbnailHeight){
                $image = \EshopHelper::cropsizeImage($image_path, JPATH_ROOT . '/images/profile/', $thumbnailWidth, $thumbnailHeight);

                return \JURI::base() . 'images/profile/resized/' . $image;
            }
            return \JURI::base() . 'images/profile/' . $image_path;

        }

        return null;
    }
}
