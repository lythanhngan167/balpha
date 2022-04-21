<?php
jimport( 'joomla.user.helper' );
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_ADMINISTRATOR . '/components/com_users/models/user.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

class  UsersApiResourceUserfield extends ApiResource
{
    private $info = array();
    private $isEdit = 0;
    private $subMember = false;
    private $canApproved = true;

    public function get()
    {
        $user = JFactory::getUser();
        $input = JFactory::getApplication()->input;
        $this->isEdit = $input->get('edit', 0);
        $uid = $input->get('id', 0);
        if ($uid) {
            $childs = SUtil::getChildList($user->id);
            if(!in_array($uid, $childs)){
                ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
                return false;
            }
            $this->subMember = true;
        } else {
            $uid = $user->id;
        }
        $fields = array();
        $lang = \JFactory::getLanguage();
        $extension = 'com_users';
        $base_dir = JPATH_ADMINISTRATOR;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
        $model = new \UsersModelUser();
        $form = $model->getForm();

        // Get user info
        if ($this->isEdit == 1) {
            $db = JFactory::getDbo();
            $select = array(
                'name',
                'sex',
                'card_id',
                'IF(birthday, DATE_FORMAT(birthday, "%Y-%m-%d"), \'\') as birthday',
                'IF(birthday, DATE_FORMAT(birthday, "%d-%m-%Y"), \'\') as birthdayText',
                'province',
                'level_tree as level',
                'job',
                'card_front',
                'card_behind',
                'username',
                'email',
                'address',
                'phone',
                'bank_name',
                'bank_account_name',
                'bank_account_number',
                'approved',
            );
            $sql = 'SELECT ' . implode(',', $select) . ' FROM #__users where id = ' . (int)$uid;
            $this->info = $db->setQuery($sql)->loadAssoc();
            //print_r($this->info);die;
        }

        if ($form) {
            if ($form) {
                $groups = JUserHelper::getUserGroups($uid);
                if(in_array(10, $groups)){
                    $info = array();
                    $info['label'] = 'THÔNG TIN ĐĂNG KÝ';
                    $info['icon'] = 'ios-person-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'username');
                    $fields[] = $info;

                    $info = array();
                    $info['label'] = 'THÔNG TIN CÁ NHÂN';
                    $info['icon'] = 'ios-school-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'sex');
                    $info['fields'][] = $this->_getField($form, 'name');
                    if ($this->info->approved == 0) {
                        $info['fields'][] = $this->_getField($form, 'password');
                    }
                    $info['fields'][] = $this->_getField($form, 'card_id');
                    $info['fields'][] = $this->_getField($form, 'birthday');
                    $info['fields'][] = $this->_getField($form, 'email');
                    $fields[] = $info;

                    $info = array();
                    $info['label'] = 'ĐỊA CHỈ THƯỜNG TRÚ THEO CMND (in hoa có dấu)';
                    $info['icon'] = 'ios-navigate-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'address');
                    $info['fields'][] = $this->_getField($form, 'province');
                    $fields[] = $info;


                    $info = array();
                    $info['label'] = 'THÔNG TIN KHÁC';
                    $info['icon'] = 'ios-heart-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'job');

                    $info['fields'][] = $this->_getField($form, 'card_front');
                    $info['fields'][] = $this->_getField($form, 'card_behind');
                    $info['fields'][] = $this->_getField($form, 'level');
                    $info['fields'][] = $this->_getField($form, 'bank_name');
                    $info['fields'][] = $this->_getField($form, 'bank_account_name');
                    $info['fields'][] = $this->_getField($form, 'bank_account_number');
                    $fields[] = $info;
                }else{
                    $info = array();
                    $info['label'] = 'THÔNG TIN ĐĂNG KÝ';
                    $info['icon'] = 'ios-person-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'username');
                    $fields[] = $info;

                    $info = array();
                    $info['label'] = 'THÔNG TIN CÁ NHÂN';
                    $info['icon'] = 'ios-school-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'sex');
                    $info['fields'][] = $this->_getField($form, 'name');
                    if ($this->info->approved == 0) {
                        $info['fields'][] = $this->_getField($form, 'password');
                    }
                    //$info['fields'][] = $this->_getField($form, 'card_id');
                    $info['fields'][] = $this->_getField($form, 'birthday');
                    $info['fields'][] = $this->_getField($form, 'email');
                    $fields[] = $info;

                    $info = array();
                    $info['label'] = 'ĐỊA CHỈ THƯỜNG TRÚ THEO CMND (in hoa có dấu)';
                    $info['icon'] = 'ios-navigate-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'address');
                    $info['fields'][] = $this->_getField($form, 'province');
                    $fields[] = $info;


                    $info = array();
                    $info['label'] = 'THÔNG TIN KHÁC';
                    $info['icon'] = 'ios-heart-outline';
                    $info['fields'] = array();
                    $info['fields'][] = $this->_getField($form, 'job');

                    //$info['fields'][] = $this->_getField($form, 'card_front');
                    //$info['fields'][] = $this->_getField($form, 'card_behind');
                    //$info['fields'][] = $this->_getField($form, 'level');
                    //$info['fields'][] = $this->_getField($form, 'bank_name');
                    //$info['fields'][] = $this->_getField($form, 'bank_account_name');
                    //$info['fields'][] = $this->_getField($form, 'bank_account_number');
                    $fields[] = $info;
                }


            }
        }
        if ($this->subMember) {
            $user = JFactory::getUser($uid);
            $groups = JAccess::getGroupsByUser($user->id);
            $is_stock = in_array(10, $groups) || in_array(11, $groups);
            $extra = array(
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->username,
                'm_id' => $user->level_tree . str_pad($user->id, 6, "0", STR_PAD_LEFT),
                'm_level' => $user->level_tree,
                'approved' => $user->approved,
                'id' => $user->id,
                'role' => $is_stock ? 'stock' : 'customer',
            );
            if ($this->info['approved'] != '9') {
                $extra['can_approve'] = $this->info['approved'] == '1';
            }
            $this->plugin->setExtra($extra);
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
                        if ($this->subMember) {
                            if (!empty($this->info['card_front'])) {
                                $data['defaultText'] = 'Đã cập nhật.';
                                $data['default'] = '1';
                                $data['class'] = 'updated';
                            }
                        } else {
                            $data['default'] = $this->getImage($this->info['card_front']);
                        }
                        break;

                    case 'card_behind':
                        $data['file'] = $this->info[$data['name']];
                        if ($this->subMember) {
                            if (!empty($this->info['card_behind'])) {
                                $data['defaultText'] = 'Đã cập nhật.';
                                $data['default'] = '1';
                                $data['class'] = 'updated';
                            }
                        } else {
                            $data['default'] = $this->getImage($this->info['card_behind']);
                        }
                        break;


                    default:
                        if ($data['multiple']) {
                            $data['default'] = explode(',', $this->info[$data['name']]);
                        } else {
                            $data['default'] = $this->info[$data['name']];
                        }

                }
            } else {
                switch ($data['name']) {
                    case 'card_front':
                    case 'card_behind':
                        if ($this->subMember) {
                            $data['defaultText'] = 'Chưa cập nhật.';
                            $data['class'] = 'blank';
                        }
                        break;
                }
            }

            if ($data['name'] == 'residence' && $data['default'] == '') {
                $data['default'] = 31;
            }
            switch ($data['name']) {
                case 'card_front':
                case 'card_behind':
                    if ($this->subMember) {
                        $data['file'] = '';
                    }
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

            if ($data['required'] && !$data['default']) {
                $this->canApproved = false;
            }
        }

        if ($this->isEdit) {
            //$data['defaultText'] = '';
            if ($data['name'] == 'province' && $data['default'] === '') {
                $data['default'] = 1;

            }
            switch ($data['name']) {
                case 'username':

                case 'email':
                    $data['readonly'] = true;
                    break;
                case 'level':
                    $data['readonly'] = true;
                    $data['required'] = false;
                    break;
                default:
                    $data['readonly'] = false;

            }

            switch ($data['type']) {
                case 'list':
                    foreach ($data['options'] as $item) {
                        if ($data['default'] == $item['value']) {
                            $data['defaultText'] = $item['text'];
                        }
                    }
                    break;
            }

            if ($data['name'] == 'birthday' && $this->info) {
                $data['defaultText'] = $this->info['birthdayText'];

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
            if ($thumbnailWidth && $thumbnailHeight) {
                $image = \EshopHelper::cropsizeImage($image_path, JPATH_ROOT . '/images/profile/', $thumbnailWidth, $thumbnailHeight);

                return \JURI::base() . 'images/profile/resized/' . $image;
            }
            return \JURI::base() . 'images/profile/' . $image_path;

        }

        return null;
    }
}
