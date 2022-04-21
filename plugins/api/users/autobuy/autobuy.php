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

class UsersApiResourceAutobuy extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'autobuy/';

        return $routes;
    }

    public function post()
    {
      $dataRequest = $this->getRequestData();
      $user = JFactory::getUser();
      $on_off = (int)$dataRequest['on_off'];
      if ($user->id > 0) {
        $sale = new stdClass();
        $sale->id = $user->id;
        $sale->autobuy = $on_off;
        $result = JFactory::getDbo()->updateObject('#__users', $sale, 'id');
        if ($result) {
            $data = '1'; //thanh cong
        } else {
            $data = '2'; //that bai
        }
      }
      else {
        $data = '3'; //chua dang nhap
      }
      $this->plugin->setResponse($data);
    }

    public function get()
    {

    }
}
