<?php

/**
 * @package API plugins
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link http://www.techjoomla.com
 */


use api\model\dao\UserDao;
use api\model\form\LoginForm;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('joomla.html.html');
jimport('joomla.application.component.controller');
jimport('joomla.application.component.model');
jimport('joomla.user.helper');
jimport('joomla.user.user');
jimport('joomla.application.component.helper');

JModelLegacy::addIncludePath(JPATH_SITE . 'components/com_api/models');
require_once JPATH_SITE . '/components/com_api/libraries/authentication/user.php';
require_once JPATH_SITE . '/components/com_api/libraries/authentication/login.php';
require_once JPATH_SITE . '/components/com_api/models/key.php';
require_once JPATH_SITE . '/components/com_api/models/keys.php';

class UsersApiResourceLogin extends ApiResource
{
    public function get()
    {
        $this->plugin->setResponse("unsupported method,please use post method");
    }

    /**
     * @OA\Post(
     *     path="/api/users/login",
     *     tags={"User"},
     *     summary="Login user to system by username and password",
     *     description="Login user to system by username and password",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Login to system",
     *         @OA\JsonContent(ref="#/components/schemas/LoginForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/LoginForm"),
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
    public function post()
    {
        $this->plugin->setResponse($this->login());
    }

    public function login()
    {
        // System configuration.
        $config = new JConfig;
        $data = $this->getRequestData();
        $form = new LoginForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $credentials = $form->toArray();

            if (strlen($credentials['username']) == 7) {
                $codeTV = $credentials['username'];
                $level = (int)$codeTV[0];
                $idUser = (int)substr($codeTV, 1);
                $dao = new UserDao();
                $userInfo = $dao->loadUser($idUser);
                if ($userInfo->username != '') {
                    $credentials['username'] = $userInfo->username;
                }
            }

            // Perform the log in.
            try {
                // Get the global JAuthentication object.
                $authenticate = JAuthentication::getInstance();
                $response = $authenticate->authenticate($credentials, array('silent' => true));
                if ($response->status === JAuthentication::STATUS_SUCCESS) {
                    $dao = new UserDao();
                    $user = $dao->getUserInfo($response->username);
                    //Insert  device id
                    if ($data['device']) {
                        $sql = 'SELECT device_id FROM #__user_devices WHERE user_id = ' . (int)$user->id
                            . ' AND device_id = ' . $dao->db->quote($data['device']);
                        $device = $dao->db->setQuery($sql)->loadResult();
                        if (!$device) {
                            $obj = new stdClass();
                            $obj->user_id = $user->id;
                            $obj->device_id = $data['device'];
                            $obj->created_date = date('Y-m-d H:i:s');
                            $dao->db->insertObject('#__user_devices', $obj);
                        }
                    }
                    $token = SUtil::generateUserToken($user->id);
                    //Check sms enable
                    if ($config->sms_enable == "1") {
                        //Check old user
                        if (!preg_match('/^[0-9]*$/', $user->username)) {
                            return array(
                                'isOldUser' => true,
                                'id' => $user->id,
                                'token' => $token
                            );
                        }
                    }
                    return $token;
                }
                ApiError::raiseError('200', 'Đăng nhập thất bại, vui lòng thử lại..');
                return false;
            } catch (Exception $e) {
                ApiError::raiseError('200', 'Đăng nhập thất bại, vui lòng thử lại...');
            }
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;
    }

    public function keygen()
    {
        //init variable
        $obj = new stdclass;
        $umodel = new JUser;
        $user = $umodel->getInstance();

        if (!$user->id) {
            $user = JFactory::getUser($this->plugin->get('user')->id);
        }

        $kmodel = new ApiModelKey;
        $model = new ApiModelKeys;
        $key = null;
        // Get login user hash
        $kmodel->setState('user_id', $user->id);
        $log_hash = $kmodel->getList();
        $log_hash = $log_hash[count($log_hash) - count($log_hash)];
        if ($log_hash->hash) {
            $key = $log_hash->hash;
        } elseif ($key == null || empty($key)) {
            // Create new key for user
            $data = array(
                'userid' => $user->id,
                'domain' => '',
                'state' => 1,
                'id' => '',
                'task' => 'save',
                'c' => 'key',
                'ret' => 'index.php?option=com_api&view=keys',
                'option' => 'com_api',
                JSession::getFormToken() => 1,
            );

            $result = $kmodel->save($data);
            $key = $result->hash;

            //add new key in easysocial table
            $easyblog = JPATH_ROOT . '/administrator/components/com_easyblog/easyblog.php';
            if (JFile::exists($easyblog) && JComponentHelper::isEnabled('com_easysocial', true)) {
                $this->updateEauth($user, $key);
            }
        }

        if (!empty($key)) {
            $obj->auth = $key;
            $obj->code = '200';
            $obj->id = $user->id;
        } else {
            $obj->code = 403;
            $obj->message = 'Bad request';
        }
        return ($obj);
    }

    /*
     * function to update Easyblog auth keys
     */
    public function updateEauth($user = null, $key = null)
    {
        require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';
        $model = FD::model('Users');
        $id = $model->getUserId('username', $user->username);
        $user = FD::user($id);
        $user->alias = $user->username;
        $user->auth = $key;
        $user->store();
        return $id;
    }
}
