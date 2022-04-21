<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\K2CategoryDao;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceK2Category extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'k2category/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
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
        $dao = new K2CategoryDao();
        $params = array();
        if (isset($data['categoryID'])) {
            $params['categoryID'] = (int)$data['categoryID'];
            $offset = isset($data['offset']) ? $data['offset'] : 0;
            $limit = isset($data['limit']) ? $data['limit'] : 20;
            $params['categoryTree'] = $this->getCategoryTree((int)$data['categoryID'], false, $limit, $offset);
            $categories = $dao->getCategory($params);
            if ($categories) {
                foreach ($categories as $item) {
                    switch ($item->id) {
                        case LIFE_INSURANCE:
                            $item->layout = 'LIFE_INSURANCE';
                            break;
                        case HEALTH_INSURANCE:
                            $item->layout = 'HEALTH_INSURANCE';
                            break;
                        case TRAVEL_INSURANCE:
                            $item->layout = 'TRAVEL_INSURANCE';
                            break;
                        case CAR_INSURANCE:
                            $item->layout = 'CAR_INSURANCE';
                            break;
                        case HOME_INSURANCE:
                            $item->layout = 'HOME_INSURANCE';
                            break;
                        case CRITICAL_ILLNESS_INSURANCE:
                            $item->layout = 'CRITICAL_ILLNESS_INSURANCE';
                            break;
                        default:
                            $item->layout = '';
                            break;
                    }
                }
            }

            $result = array();
            foreach ($categories as $item) {
                if ((int)$item->parent != 0) {
                    array_push($result, $item);
                }
            }

            $this->plugin->setResponse($result);
        } else {
            ApiError::raiseError('404', 'Không tìm thấy trang');
            return false;
        }
    }

    public function getCategoryTree($categories, $associations = false, $limit, $offset)
    {
        $app = JFactory::getApplication();
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $aid = (int)$user->get('aid');
        if (!is_array($categories)) {
            $ignore_cat = $categories;
            $categories = (array)$categories;
        }
        JArrayHelper::toInteger($categories);
        $categories = array_unique($categories);
        sort($categories);
        $key = implode('|', $categories);
        $clientID = $app->getClientId();
        static $K2CategoryTreeInstances = array();
        if (isset($K2CategoryTreeInstances[$clientID]) && array_key_exists($key, $K2CategoryTreeInstances[$clientID])) {
            return $K2CategoryTreeInstances[$clientID][$key];
        }
        $array = $categories;
        while (count($array)) {
            $query = "SELECT id
                        FROM #__k2_categories
                        WHERE parent IN(" . implode(',', $array) . ")
                            AND id NOT IN(" . implode(',', $array) . ")";
            if ($app->isSite()) {
                $query .= " AND published=1 AND trash=0";
                if (K2_JVERSION != '15') {
                    $query .= " AND access IN(" . implode(',', $user->getAuthorisedViewLevels()) . ")";
                    if ($app->getLanguageFilter()) {
                        $query .= " AND language IN(" . $db->Quote(JFactory::getLanguage()->getTag()) . ", " . $db->Quote('*') . ")";
                    }
                } else {
                    $query .= " AND access<={$aid}";
                }
            }
            $db->setQuery($query, $offset, (int)$limit);
            $array = (K2_JVERSION == '30') ? $db->loadColumn() : $db->loadAssocList();

            $categories = array_merge($categories, $array);
            if(count($categories) > 1){
              $categories = array_merge(array_diff($categories, array($ignore_cat)));
            }

        }
        JArrayHelper::toInteger($categories);
        $categories = array_unique($categories);
        $K2CategoryTreeInstances[$clientID][$key] = $categories;

        if(count($categories) == 1 AND $categories[0] == $ignore_cat){
          $arrayCat = array("0" => 0);
          $categories = $arrayCat;
        }
        return $categories;
    }
}
