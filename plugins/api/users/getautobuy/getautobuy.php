<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\ProjectDao;
jimport('joomla.user.user');

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceGetautobuy extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'getautobuy/';

        return $routes;
    }

    public function post()
    {
      $dataRequest = $this->getRequestData();
      $user = JFactory::getUser();
      if ($user->id > 0) {
        $sql = 'SELECT autobuy FROM #__users WHERE block = 0 AND id = '.$user->id;
        $db = JFactory::getDbo();
        $result = $db->setQuery($sql)->loadResult();
        if ($result == 1) {
            $data = 1; // co autobuy
        } else {
            $data = 0;
        }
      }
      else {
        $data = 0;
      }
      $this->plugin->setResponse($data);
    }

    public function get()
    {

    }
}
