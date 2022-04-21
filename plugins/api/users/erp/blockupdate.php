<?php

/**
 * Created by thaint.
 * User: ASUS
 * Date: 2020/11/01
 * Time: 9:51 AM
 */

use api\model\form\erp\ERPUpdateBlockForm;
use JchOptimize\Minify\Js;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceBlockUpdate extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'blockupdate';

        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new ERPUpdateBlockForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            //Check invite user
            try {
                $db->transactionStart();
                //Check exist id biznet
                $sql1 = 'SELECT count(*) FROM #__users WHERE id_biznet = ' . $db->quote($data['id_biznet']);
                $count = $db->setQuery($sql1)->loadResult();
                if ($count == 0 && $data["is_production"] == true) {
                    ApiError::raiseError('102', 'ID Biznet không tồn tại.');
                    return false;
                }
                if (isset($data['block']) && $data['block'] != 0 && $data['block'] != 1) {
                    ApiError::raiseError('103', 'block có giá trị không hợp lệ.');
                    return false;
                } else {
                    $sql2 = 'UPDATE #__users SET block=' . $db->quote($data['block'])
                        . 'WHERE id_biznet = ' . $db->quote($data['id_biznet']);
                    $db->setQuery($sql2)->loadResult();

                    $config = new JConfig();
                    if ($data['is_production'] == true && $config->erp_test == 0) {
                        $db->transactionCommit();
                    }
                    $this->plugin->setResponse(array(
                        'success' => true
                    ));
                    return true;
                }
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
