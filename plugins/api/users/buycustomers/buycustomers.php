<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\CustomerDao;
use api\model\dao\MaxPickDao;
use api\model\dao\CategoryDao;
use api\model\dao\OrdersDao;
use api\model\form\BuyQueryForm;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceBuyCustomers extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'buycustomers/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    private function _getDefaultMaxPick()
    {
        $app = JFactory::getApplication();
        $cparams = $app->getParams('com_maxpick_level');
        return $cparams->get('maxpickdefault');
    }


    /**
     * @OA\Post(
     *     path="/api/users/buycustomers",
     *     tags={"Projects"},
     *     summary="Buy customers",
     *     description="Buy customers",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Buy customers",
     *         @OA\JsonContent(ref="#/components/schemas/BuyQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/BuyQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProjectBiz")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function post()
    {
        $data = $this->getRequestData();
        $form = new BuyQueryForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $user = JFactory::getUser();
            $dao = new CustomerDao();
            $caring = $dao->getCaringCustomer(array('user_id' => $user->id, 'project_id' => $data['project_id']));

            $app = JFactory::getApplication();
            $cparams = $app->getParams('com_crm_config');
            $maxpick = $cparams->get('maxpick');
            $maxCaring = $maxpick;
            if ($caring >= $maxCaring) {
                ApiError::raiseError('401', 'Over max caring');
                return false;
            }
            $maxpickDao = new MaxPickDao();
            $maxpick = $maxpickDao->getMaxPick(array('cat_id' => $data['cat_id'], 'project_id' => $data['project_id']));

            $maxpick = $maxpick > 0 ? $maxpick : $this->_getDefaultMaxPick();

            $contactedToday = $dao->getCountCustomerDataToday(array('user_id' => $user->id, 'cat_id' => $data['cat_id'], 'project_id' => $data['project_id']));
            $restCustomer = $maxpick - $contactedToday;
            if ($data['quantity'] <= $restCustomer) {
                $availabelContact = $dao->getCountByCat(array('cat_id' => $data['cat_id'], 'project_id' => $data['project_id']));

                if ($data['quantity'] > $availabelContact) {
                    ApiError::raiseError('404', 'Invalid customer number');
                    return false;
                }

                $categoryDao = new CategoryDao();
                // $price = $categoryDao->getPrice(array('cat_id' => $data['cat_id']));
                $projectInfo = $this->getProjectByID($data['project_id']);
                $price = $projectInfo['price'];

                $totalPrice = $projectInfo['price'] * $data['quantity'];

                if ($this->getMoney($user->id) < $totalPrice) {
                    ApiError::raiseError('201', 'Số dư tài khoản không đủ. Vui lòng nạp thêm.');
                    return false;
                }



                $order = new stdClass();
                $order->category_id = $data['cat_id'];
                $order->quantity = $data['quantity'];
                $order->price = $price;
                $order->total = $totalPrice;
                $order->project_id = $data['project_id'];
                $order->state = 1;
                $order->created_by = $user->id;
                $order->modified_by = $user->id;
                $order->create_date = date("Y-m-d H:i:s");
                $order->modified_date = date("Y-m-d H:i:s");

                $orderDao = new OrdersDao();
                $orderDao->insert($order);
                $customers = $dao->getRandomCustomers(array('cat_id' => $data['cat_id'], 'project_id' => $data['project_id'], 'limit' => $data['quantity']));
                if($customers){
                    $dao->update(array(
                        'table' => '#__customers',
                        'set' => array(
                            'sale_id = ' . $user->id,
                            'modified_date = \'' . date("Y-m-d H:i:s") . '\'',
                            'buy_date = \'' . date("Y-m-d H:i:s") . '\''
                        ),
                        'where' => array(
                            'id IN (' . $customers . ')'
                        )
                    ));
                    $dao->update(array(
                        'table' => '#__orders',
                        'set' => array(
                            'list_customer = \'' . $customers.'\'',
                        ),
                        'where' => array(
                            'id =' . $order->id
                        )
                    ));
                    // Add history
                    $obj = new stdClass();
                    $obj->state = 1;
                    $obj->created_by = $user->id;
                    $obj->title = 'Mua dữ liệu #' . $order->id;
                    $obj->amount = 0 - $totalPrice;
                    $obj->created_date = date('Y-m-d H:i:s');
                    $obj->type_transaction = 'buydata';
                    $obj->status = 'completed';
                    $obj->reference_id = $order->id;
                    $db = JFactory::getDbo();
                    $db->insertObject('#__transaction_history', $obj, 'id');

                    // Descrease money
                    $sql = "UPDATE #__users set money = money - " . $totalPrice . ' WHERE id = ' . $user->id;
                    $db->setQuery($sql)->execute();
                }



                $this->plugin->setResponse('OK');
            } else {
                ApiError::raiseError('402', $restCustomer);
                return false;
            }

        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }


    }

    /**
     * @OA\Get(
     *     path="/api/users/buy/{id}",
     *     tags={"Projects"},
     *     summary="Get Project information",
     *     description="Get Project information",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Project id",
     *         required=false,
     *         @OA\Schema(
     *           type="int",
     *           default="null"
     *         ),
     *         style="form"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CategoryBiz")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function get()
    {


        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $params = array();
        $params['id'] = (int)$id;
        $params['level_id'] = $this->_getLevelId();
        $params['max_pick'] = $this->_getDefaultMaxPick();

        $dao = new ProjectDao();
        $result = $dao->getProjectInfo($params);
        $this->plugin->setResponse($result);
    }

    private function _getLevelId()
    {
        $user = JFactory::getUser();
        $level = (int)$user->get('level');
        switch ($level) {
            case 1:
                $id = 167;
                break;
            case 2:
                $id = 168;
                break;
            case 3:
                $id = 169;
                break;
            case 4:
                $id = 170;
                break;
            case 5:
                $id = 171;
                break;
            default:
                $id = 0;
        }
        return $id;
    }
    public function getMoney($uid)
    {

        return JFactory::getDbo()->setQuery('SELECT money FROM #__users WHERE id = ' . $uid)->loadResult();
    }
    public function getProjectByID($project_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('is_recruitment,price');
        $query->from($db->quoteName('#__projects'));
        $query->where($db->quoteName('id') . " = " . $project_id);
        $query->where($db->quoteName('state') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        return $result;
    }

}
