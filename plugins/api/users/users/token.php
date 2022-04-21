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

class UsersApiResourceToken extends ApiResource
{
    public function post()
    {
        $this->plugin->setResponse("unsupported method,please use post method");
    }

    /**
     * @OA\Post(
     *     path="/api/users/renewtoken",
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
    public function get()
    {
        $this->plugin->setResponse($this->renewToken());
    }

    public function renewToken()
    {
        $user = JFactory::getUser();
        if($user->id){

            $token = SUtil::generateUserToken($user->id);
            return $token;

        }
        ApiError::raiseError('101', 'Invalid request');
        return false;

    }
}
