<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');

use api\model\dao\UserDao;
use api\model\dao\ProjectDao;
use api\model\SUtil;

class UsersApiResourceMemberslevel extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'memberslevel/';

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
    public function get()
    {
        $user = JFactory::getUser();
        $childs = SUtil::getChildList($user->id);
        $sql = 'SELECT COUNT(*) as num, `level_tree` FROM #__users where id <> ' . (int)$user->id . ' AND id IN(' . implode(',', $childs) . ') GROUP BY `level_tree`';

        $dao = new UserDao();
        $result = $dao->db->setQuery($sql)->loadAssocList();

        $level_key_name = array(
            1=>"AA",
            2=>"FA",
            3=>"PUM",
            4=>"UM",
            5=>"BM",
            6=>"BDM",
            7=>"BDM2"
        );

        if ($result) {
            $total = 0;
            foreach ($result as &$item) {
                $total += $item['num'];
                $item['text'] = 'ĐL cấp ' . $level_key_name[$item['level_tree']];
                $item['level'] = $item['level_tree'];
            }
        }
        $totalItem = array(
            'text' => 'Tất cả Đại lý',
            'num' => $total,
            'level' => 0
        );
        array_unshift($result, $totalItem);
        $this->plugin->setResponse($result);
    }




}
