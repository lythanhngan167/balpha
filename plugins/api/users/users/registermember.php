<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

use api\model\form\RegisterMemberForm;
use api\model\SUtil;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceRegistermember extends ApiResource
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
        $registerUser = JFactory::getUser();
        $data = $this->getRequestData();
        $data['approved'] = 0;
        $data['password'] = '123456';
        $tmpEmail = '';
        $db = JFactory::getDbo();
        /*$data = array(
            'name' => 'abc',
            'email' => 'email11@emial.com',
            'password' => '123456789',
            'username' => 'abf111',
            'mobile' => 'a0999999999'
        );*/
        $form = new RegisterMemberForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $user = new JUser;
            // Bind the data.
            $data = $form->toArray();
            $data['invited_id'] = $registerUser->id;
            $data['phone'] = $data['username'];
//            if(!$data['email']){
//                $tmpEmail = time().'_'.rand(1, 1000).'@gmail.com';
//                $data['email'] = $tmpEmail;
//            }
            if (!$user->bind($data)) {
                ApiError::raiseError('100', $user->getError());
                return false;
            }
            $group = isset($data['group']) ? $data['group'] : 10;

            $params = JComponentHelper::getParams('com_users');
            $defaultGroup = $params->get('new_usertype', 10);
            $group = 10;

            //Insert  device id
            /*if($data['device']){
                $obj = new stdClass();
                $obj->user_id = $user->id;
                $obj->device_id = $data['device'];
                $obj->created_date = date('Y-m-d H:i:s');
                $db = JFactory::getDbo();
                $db->insertObject('#__user_devices', $obj);
            }*/


            $user->set('groups', array($group));
            //$user->set('block',1);
            // Store the data.
            if (!$user->save()) {
                ApiError::raiseError('101', $user->getError());
                return false;
            }
//            if($tmpEmail){
//                $sql = 'UPDATE #__users SET email = null WHERE id = '.$user->id;
//                $db->setQuery($sql)->execute();
//                $user->email = '';
//            }

            $sql = 'UPDATE #__users set password = md5(\'123456\') WHERE id = '.(int)$user->id;
            $db->setQuery($sql)->execute();

            $userInfo = array(
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->username,
                'group' => $group,
                'role' => $group == 10 ? 'stock' : 'customer'
            );

            //$this->_registerEshopCustomer($userInfo);

            //$token = SUtil::generateUserToken($user->id);
            $this->plugin->setResponse('Đăng ký Đại lý thành công.');
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
