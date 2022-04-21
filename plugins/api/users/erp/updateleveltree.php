<?php

use api\model\form\erp\ERPUpdateLevelTreeForm;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceUpdateLevelTree extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'updateleveltree';
        return $routes;
    }
    private $levelList = array('AA', 'FA', 'PUM', 'UM', 'BM', 'BDM', 'BDM2');

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new ERPUpdateLevelTreeForm();
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
                if(!in_array($data['level_tree'],$this->levelList)){
                    ApiError::raiseError('103', 'Level tree phải thuộc là các giá trị sau : AA, FA, PUM, UM, BM, BDM, BDM2.');
                    return false;
                }
                $level = 0;
                switch($data['level_tree']){
                    case "AA":{
                        $level = 1;break;
                    }
                    case "FA":{
                        $level = 2;break;
                    }
                    case "PUM":{
                        $level = 3;break;
                    }
                    case "UM":{
                        $level = 4;break;
                    }
                    case "BM":{
                        $level = 5;break;
                    }
                    case "BDM":{
                        $level = 6;break;
                    }
                    case "BDM2":{
                        $level = 7;break;
                    }
                }
                //Prepare statement
                $sql = 'UPDATE #__users SET level_tree=' . $db->quote($level)
                        . 'WHERE id_biznet = ' . $db->quote($data['id_biznet']);
                //Excute query
                $db->setQuery($sql)->loadResult();
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
