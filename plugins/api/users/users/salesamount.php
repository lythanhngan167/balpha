<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

use api\model\dao\shop\ShopOrderDao;
use api\model\dao\UserDao;
use api\model\SUtil;

class UsersApiResourceSalesamount extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'salesamount/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/contents",
     *     tags={"Content"},
     *     summary="Get content list",
     *     description="Get content list",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register user to system",
     *         @OA\JsonContent(ref="#/components/schemas/ContentQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ContentQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ContentBiz")
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
        $user = JFactory::getUser();
        $result = array();
        $uid = isset($data['uid']) && $data['uid'] > 0;
        if ($uid) {
            $childs = SUtil::getChildList($user->id);
            if (!in_array($uid, $childs)) {
                ApiError::raiseError('101', 'Yêu cầu không hợp lệ.');
                return false;
            }

        } else {
            $uid = $user->id;
        }
        $date = isset($data['from_date']) ? $data['from_date'] : '';
        $month = date('m');
        $year = date('Y');
        if ($date) {
            $tmp = explode('-', $date);
            $month = $tmp[1];
            $year = $tmp[0];
        }

        //revenue
        $result['individual'] = EshopHelper::getRevenueAmount($uid, $month, $year, 'individual');
        $result['group'] = EshopHelper::getRevenueAmount($uid, $month, $year, 'group');

        //commission
        $result['commission'] = EshopHelper::getCommissionAmount($uid, $month, $year, 'group');

        $dao = new ShopOrderDao();
        // Get user info
        $sql = ' SELECT id, `name`, `level_tree` FROM #__users WHERE id = ' . (int)$uid;
        $userInfo = $dao->db->setQuery($sql)->loadAssoc();
        if ($userInfo) {
            $userInfo['m_id'] = $userInfo['level_tree'] . str_pad($userInfo['id'], 6, "0", STR_PAD_LEFT);
        }
        $result['user'] = $userInfo;
        $result['month'] = "{$year}-{$month}-01T00:00:00";

        $this->plugin->setResponse($result);
    }


}
