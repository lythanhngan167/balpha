<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\form\RegisterForm;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceRegister extends ApiResource
{
    /**
     * @OA\Post(
     *     path="/api/users/register",
     *     tags={"User"},
     *     summary="Register user",
     *     description="Register user",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register user to system",
     *         @OA\JsonContent(ref="#/components/schemas/RegisterForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/RegisterForm"),
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
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new RegisterForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            if ($data['code_token'] && $data['code']) {
                $sql = 'SELECT COUNT(*) FROM #__social WHERE `token` = ' . $db->quote($data['code_token'])
                    . ' AND verify_code = ' . $db->quote($data['code'])
                    . ' AND field_value = ' . $db->quote($data['username']);
                $phone = $db->setQuery($sql)->loadResult();
                if (empty($phone)) {
                    ApiError::raiseError('100', 'Mã xác nhận không chính xác.');
                    return false;
                }
            }else{
                ApiError::raiseError('100', 'Mã xác nhận không chính xác.');
                return false;
            }

            // Bind the data.
            $data = $form->toArray();
            if (@$data['af_phone']) {
                $sql = 'SELECT id FROM #__users WHERE username = ' . $db->quote($data['af_phone']);
                $invited_id = $db->setQuery($sql)->loadResult();
                if (!$invited_id) {
                    ApiError::raiseError('200', 'Số điện thoại người giới thiệu không tồn tại...');
                    return false;
                }
                $sql = 'SELECT block FROM #__users WHERE id = ' . $db->quote($invited_id);
                $isBlock = $db->setQuery($sql)->loadResult();
                if($isBlock == 0){
                    $data['invited_id'] = $invited_id;
                }
            }

            $user = new JUser;

            // Gen email if email is null
            if (!$data['email']) {
                $data['email'] = "biznet_{$data['username']}@biznet.com.com";
            }

            if (!$user->bind($data)) {
                ApiError::raiseError('100', $user->getError());
                return false;
            }
            $group = isset($data['group']) ? $data['group'] : 2;

            $params = JComponentHelper::getParams('com_users');
            $defaultGroup = $params->get('new_usertype', 2);
            $group = $group == 3 ? $group : $defaultGroup;




            $user->set('groups', array($group));
            //$user->set('block',1);
            // Store the data.
            if (!$user->save()) {
                ApiError::raiseError('101', $user->getError());
                return false;
            }
            $userInfo = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => strpos($user->email, 'camilla_') === false ? $user->email : '',
                'phone' => $user->username,
                'group' => $group,
                'role' => $group == 10 ? 'stock' : 'customer',
            );

            //Insert  device id
            if ($data['device']) {

                $sql = 'SELECT device_id FROM #__user_devices WHERE user_id = ' . (int)$user->id
                    . ' AND device_id = ' . $db->quote($data['device']);
                $device = $db->setQuery($sql)->loadResult();
                if (!$device) {
                    $obj = new stdClass();
                    $obj->user_id = $user->id;
                    $obj->device_id = $data['device'];
                    $obj->created_date = date('Y-m-d H:i:s');
                    $db->insertObject('#__user_devices', $obj);
                }
            }

            $this->_registerEshopCustomer($userInfo);
            $token = SUtil::generateUserToken($user->id);
            $this->plugin->setResponse($token);
            return true;
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;
    }

    private function _registerEshopCustomer($user = array())
    {
        // Ensure the user id is really an int
        $userId = (int)$user['id'];

        // If the user id appears invalid then bail out just in case
        if (empty($userId)) {
            return false;
        }
        if (!file_exists(JPATH_ADMINISTRATOR . '/components/com_eshop/eshop.php')) {
            return true;
        }
        require_once JPATH_ROOT . '/components/com_eshop/helpers/helper.php';
        require_once JPATH_ROOT . '/components/com_eshop/helpers/api.php';
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_eshop/tables');
        $db = JFactory::getDbo();
        $data = array();
        $name = $user['name'];
        //Get first name, last name from username
        $pos = strpos($name, ' ');
        if ($pos !== false) {
            $data['firstname'] = substr($name, 0, $pos);
            $data['lastname'] = substr($name, $pos + 1);
        } else {
            $data['firstname'] = $name;
            $data['lastname'] = '';
        }
        $data['email'] = $user['email'];
        if (JPluginHelper::isEnabled('user', 'profile')) {
            $profile = JUserHelper::getProfile($userId);
            $data['address_1'] = $profile->profile['address1'];
            $data['address_2'] = $profile->profile['address2'];
            $data['city'] = $profile->profile['city'];
            $country = $profile->profile['country'];
            if ($country) {
                $query = $db->getQuery(true);
                $query->select('iso_code_3')
                    ->from('#__eshop_countries')
                    ->where('country_name = ' . $db->quote($country));
                $db->setQuery($query);
                $countryCode = $db->loadResult();
                $data['country_code'] = $countryCode;
                if ($countryCode != '') {
                    $region = $profile->profile['region'];
                    if ($region) {
                        $query->clear();
                        $query->select('z.zone_code')
                            ->from('#__eshop_zones AS z')
                            ->innerJoin('#__eshop_countries AS c ON (z.country_id = c.id)')
                            ->where('c.iso_code_3 = ' . $db->quote($countryCode))
                            ->where('z.zone_name = ' . $db->quote($region));
                        $db->setQuery($query);
                        $data['zone_code'] = $db->loadResult();
                    }
                }
            }
            $data['postcode'] = $profile->profile['postal_code'];
            $data['telephone'] = $profile->profile['phone'];
        }
        EshopAPI::addCustomer($userId, $data);
    }
}
