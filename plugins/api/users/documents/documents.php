<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */

defined('_JEXEC') or die('Restricted access');

use api\model\dao\PhocadownloadDao;

class UsersApiResourceDocuments extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'documents/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/documents",
     *     tags={"Documents"},
     *     summary="Get documents list",
     *     description="Get documents list",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Register user to system",
     *         @OA\JsonContent(ref="#/components/schemas/DocumentQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/DocumentQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PhocadownloadBiz")
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
        $dao = new PhocadownloadDao();
        $data = $this->getRequestData();

        $params = array();
        $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        $catid = array_filter((array)$data['catid']);
        if (!empty($catid)) {
            foreach ($catid as &$item) {
                $item = (int)$item;
            }
            $params['where'][] = 'catid in (' . implode(',', $catid) . ')';
        }
        if ($data['id']) {
            $params['where'][] = 'id = ' . (int)$data['id'];
        }

        $phrase = 'exact';
        $ordering = 'newest';
        if ($data['q'] && !empty($data['q'])) {
            $text = $data['q'];
            switch ($phrase) {
                case 'exact':
                    $text = $dao->db->quote('%' . $dao->db->escape($text, true) . '%', false);
                    $wheres2 = array();
                    $wheres2[] = '`title` LIKE ' . $text;
                    $wheres2[] = '`description` LIKE ' . $text;
                    $wheres2[] = '`features` LIKE ' . $text;                   
                    $params['where'][] = '(' . implode(') OR (', $wheres2) . ')';
                    break;

                case 'all':
                case 'any':
                default:
                    $words = explode(' ', $text);
                    $wheres = array();

                    foreach ($words as $word) {
                        $word = $dao->db->quote('%' . $dao->db->escape($word, true) . '%', false);
                        $wheres2 = array();
                        $wheres2[] = 'LOWER(`title`) LIKE LOWER(' . $word . ')';
                       // $wheres2[] = 'LOWER(`description`) LIKE LOWER(' . $word . ')';
                       // $wheres2[] = 'LOWER(`features`) LIKE LOWER(' . $word . ')';                      
                        $wheres[] = implode(' OR ', $wheres2);
                    }

                    $params['where'][] = '(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                    break;
            }
            switch ($ordering) {
                case 'oldest':
                    $params['order'] = 'date ASC';
                    break;               

                case 'newest':
                default:
                    $params['order'] = 'date DESC';
                    break;
            }
        }

        $result = $dao->getDocuments($params);
        $this->plugin->setResponse($result);
    }

    /**
     * @OA\Get(
     *     path="/api/users/contents/{id}",
     *     tags={"Content"},
     *     summary="Get content by id",
     *     description="Get content by id",
     *     operationId="post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Content id",
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
        $input = JFactory::getApplication()->input;
        $id = $input->get('id', 0);
        $params = array();
        if ($id) {
            $params['where'][] = 'id = ' . (int)$id;
        }
        $dao = new ContentDao();
        $result = $dao->getContentInfo($params);
        $this->plugin->setResponse($result);
    }


}
