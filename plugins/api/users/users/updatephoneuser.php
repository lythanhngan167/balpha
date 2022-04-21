<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceUpdatePhoneUser extends ApiResource
{
    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();

        if ($data['code_token'] && $data['code']) {
            $sql = 'SELECT COUNT(*) FROM #__social WHERE `token` = ' . $db->quote($data['code_token'])
                . ' AND verify_code = ' . $db->quote($data['code'])
                . ' AND field_value = ' . $db->quote($data['phone']);
            $count = $db->setQuery($sql)->loadResult();
            if ($count == 0) {
                ApiError::raiseError('100', 'Mã xác nhận không chính xác.');
                return false;
            } else {
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);

                $fields = array(
                    $db->quoteName('username') . ' = ' . $db->quote($data['phone'])
                );

                $conditions = array(
                    $db->quoteName('id') . ' = ' . $db->quote($data['id'])
                );

                $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();
                return $result;
            }
        } else {
            ApiError::raiseError('100', 'Mã xác nhận không chính xác.');
            return false;
        }
        return false;
    }
}
