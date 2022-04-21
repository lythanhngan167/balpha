<?php

use api\model\form\erp\ERPRegisterForm;
use Joomla\CMS\Table\User;

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceRegisterAgency extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'registeragency';

        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new ERPRegisterForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            //Check invite user
            try {
                $db->transactionStart();
                if ($data['invited_id']) {
                    $sql = 'SELECT id FROM #__users WHERE id_biznet = ' . $db->quote($data['invited_id']) . ' and block=0 limit 1';
                    $invited_id = $db->setQuery($sql)->loadResult();
                    $data['invited_id'] = $invited_id;
                    if (!$invited_id) {
                        ApiError::raiseError('102', 'Người giới thiệu không tồn tại.');
                        return false;
                    }
                }

                if ($data['email'] != '') {
                    $sql = 'SELECT id FROM #__users WHERE email = ' . $db->quote($data['email']) . ' and block=0 limit 1';
                    $user_email_id = $db->setQuery($sql)->loadResult();
                    if ($user_email_id) {
                        ApiError::raiseError('105', 'Email đã tồn tại.');
                        return false;
                    }
                }

                //Check exist phone number
                $sql1 = 'SELECT count(*) FROM #__users WHERE username = ' . $db->quote($data['phone']) . ' and block=0';
                $count = $db->setQuery($sql1)->loadResult();
                if ($count > 0) {
                    ApiError::raiseError('103', 'Số điện thoại đã được đăng kí.');
                    return false;
                }
                //Check exist id biznet
                $sql2 = 'SELECT count(*) FROM #__users WHERE id_biznet = ' . $db->quote($data['id_biznet']) . ' and block=0';
                $count = $db->setQuery($sql2)->loadResult();
                if ($count > 0) {
                    ApiError::raiseError('104', 'ID Biznet đã tồn tại.');
                    return false;
                }
                $user = new User($db);
                $plainPassword = $data['password'];
                //Binding
                $user->bind($data);
                //init user
                $user->username = $user->phone;
                $user->password = $plainPassword;
                $user->groups = array(3);
                $user->block = 0;
                $user->level = 1;
                $user->level_tree = 1;
                $user->registerDate = date("Y-m-d H:i:s");
                // Store the data.
                $user->store();

                $group = isset($data['group']) ? $data['group'] : 2;
                $params = JComponentHelper::getParams('com_users');
                $defaultGroup = $params->get('new_usertype', 2);
                $group = $group == 3 ? $group : $defaultGroup;
                $userInfo = array(
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => strpos($user->email, 'biznet_') === false ? $user->email : '',
                    'phone' => $user->username,
                    'group' => $group,
                    'role' => $group == 10 ? 'stock' : 'customer',
                );
                $this->_registerEshopCustomer($userInfo);

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
