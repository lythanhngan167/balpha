<?php

use api\model\form\erp\ERPUpdateInviteIdForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceUpdateInviteId extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'updateinviteid';
        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new ERPUpdateInviteIdForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            try {
                $db->transactionStart();
                //Check exist user by id_biznet
                if ($data['id_biznet']) {
                    $sql = 'SELECT count(*) FROM #__users WHERE id_biznet = ' . $db->quote($data['id_biznet']) . ' and block=0';
                    $cnt = $db->setQuery($sql)->loadResult();
                    if ($cnt == 0) {
                        ApiError::raiseError('102', 'ID Biznet không tồn tại.');
                        return false;
                    }
                }
                //Check exist user by invited_id
                if ($data['invited_id']) {
                    $sql = 'SELECT id FROM #__users WHERE id_biznet = ' . $db->quote($data['invited_id']) . ' and block=0';
                    $invited_id = $db->setQuery($sql)->loadResult();
                    if (!isset($invited_id)) {
                        ApiError::raiseError('103', 'invited_id không tồn tại.');
                        return false;
                    }
                    //Prepare statement
                    $sql = 'UPDATE #__users SET invited_id=' . $db->quote($invited_id)
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
