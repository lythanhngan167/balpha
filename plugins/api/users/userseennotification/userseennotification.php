<?php

jimport('joomla.access.access');
jimport('joomla.user.user');

defined('_JEXEC') or die('Restricted access');

use api\model\form\UserSeenNotificationForm;

class UsersApiResourceUserSeenNotification extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'userseennotification/';

        return $routes;
    }

    public function post()
    {
        $db = JFactory::getDbo();
        $data = $this->getRequestData();
        $form = new UserSeenNotificationForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $query = $db->getQuery(true);
            $user = JFactory::getUser();

            $fields = array(
                $db->quoteName('seen_flag') . ' = ' . $db->quote(1),
                $db->quoteName('seen_at') . ' =  now()'
            );

            $conditions = array(
                $db->quoteName('id') . ' = ' . $db->quote($data['id']),
                $db->quoteName('user_id') . ' = ' . $db->quote($user->id)
            );

            $query->update($db->quoteName('#__notifications_user'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            return $this->plugin->setResponse($result);
        }
        ApiError::raiseError('101', $form->getFirstError());
        return false;
    }
}
