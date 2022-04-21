<?php

use api\model\form\erp\ERPUpdatePhoneByIdBiznetForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceUpdatePhoneByIdBiznet extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'updatephonebyidbiznet';
        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new ERPUpdatePhoneByIdBiznetForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            try {
                $db->transactionStart();
                if ($data['id_biznet']) {
                    $sql = 'SELECT count(*) FROM #__users WHERE id_biznet = ' . $db->quote($data['id_biznet']) . ' and block=0';
                    $cnt = $db->setQuery($sql)->loadResult();
                    if ($cnt == 0) {
                        ApiError::raiseError('102', 'ID Biznet không tồn tại.');
                        return false;
                    }
                }
                if ($data['phone']) {
                    $sql = 'SELECT count(*) FROM #__users WHERE username = ' . $db->quote($data['phone']) . ' and block=0';
                    $cnt = $db->setQuery($sql)->loadResult();
                    if ($cnt > 0) {
                        ApiError::raiseError('103', 'Số điện thoại đã được đăng ký');
                        return false;
                    }
                    //Prepare statement
                    $sql = 'UPDATE #__users SET username=' . $db->quote($data['phone']) .', phone=' . $db->quote($data['phone'])
                        . 'WHERE id_biznet = ' . $db->quote($data['id_biznet']);
                    //Excute query
                    $db->setQuery($sql)->loadResult();
                }
                $config = new JConfig();
                if ($data['is_production'] == true && $config->erp_test == 0) {
                    $db->transactionCommit();
                }
                $this->plugin->setResponse(array(
                    'success' => true
                ));
                return true;
            } catch (Exception $e) {
                // catch any database errors.
                $db->transactionRollback();
                ApiError::raiseError($e->getCode(), $e->getMessage());
                die();
            }
        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }
    }
}
