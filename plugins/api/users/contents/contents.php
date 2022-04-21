<?php
/**
 * @package Com_api
 * @copyright Copyright (C) 2009 2014 Techjoomla, Tekdi Technologies Pvt. Ltd. All rights reserved.
 * @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     http://www.techjoomla.com
 */
ini_set("allow_url_fopen", 1);
defined('_JEXEC') or die('Restricted access');

use api\model\dao\ContentDao;

class UsersApiResourceContents extends ApiResource
{

    static public function routes()
    {
        $routes[] = 'contents/';

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
        // $dao = new ContentDao();
        // $data = $this->getRequestData();

        // $params = array();
        // $params['offset'] = isset($data['offset']) ? (int)$data['offset'] : 0;
        // $params['limit'] = isset($data['limit']) ? (int)$data['limit'] : 20;
        // $catid = array_filter((array)$data['catid']);
        // if (!empty($catid)) {
        //     foreach ($catid as &$item) {
        //         $item = (int)$item;
        //     }
        //     $params['where'][] = 'catid in (' . implode(',', $catid) . ')';
        // }
        // if ($data['id']) {
        //     $params['where'][] = 'id = ' . (int)$data['id'];
        // }

        // $phrase = 'any';
        // $ordering = 'newest';
        // if ($data['q'] && !empty($data['q'])) {
        //     $text = $data['q'];
        //     switch ($phrase) {
        //         case 'exact':
        //             $text = $dao->db->quote('%' . $dao->db->escape($text, true) . '%', false);
        //             $wheres2 = array();
        //             $wheres2[] = '`title` LIKE ' . $text;
        //             $wheres2[] = '`introtext` LIKE ' . $text;
        //             $wheres2[] = '`fulltext` LIKE ' . $text;
        //             $wheres2[] = '`metakey` LIKE ' . $text;
        //             $wheres2[] = '`metadesc` LIKE ' . $text;
        //             $params['where'][] = '(' . implode(') OR (', $wheres2) . ')';
        //             break;

        //         case 'all':
        //         case 'any':
        //         default:
        //             $words = explode(' ', $text);
        //             $wheres = array();

        //             foreach ($words as $word) {
        //                 $word = $dao->db->quote('%' . $dao->db->escape($word, true) . '%', false);
        //                 $wheres2 = array();
        //                 $wheres2[] = 'LOWER(`title`) LIKE LOWER(' . $word . ')';
        //                 $wheres2[] = 'LOWER(`introtext`) LIKE LOWER(' . $word . ')';
        //                 $wheres2[] = 'LOWER(`fulltext`) LIKE LOWER(' . $word . ')';
        //                 $wheres2[] = 'LOWER(`metakey`) LIKE LOWER(' . $word . ')';
        //                 $wheres2[] = 'LOWER(`metadesc`) LIKE LOWER(' . $word . ')';
        //                 $wheres[] = implode(' OR ', $wheres2);
        //             }

        //             $params['where'][] = '(' . implode(($phrase === 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
        //             break;
        //     }

        // }

        // switch ($ordering) {
        //     case 'oldest':
        //         $params['order'] = 'created ASC';
        //         break;

        //         case 'popular':
        //             $params['order'] = 'hits DESC';
        //             break;

        //         case 'alpha':
        //             $params['order'] = 'title ASC';
        //             break;

        //         case 'category':
        //             $params['order'] = 'title ASC';
        //             break;

        //     case 'newest':
        //     default:
        //         $params['order'] = 'created DESC';
        //         break;
        // }

        // $result = $dao->getContent($params);
        // $this->plugin->setResponse($result);

        $data = $this->getRequestData();
        if(isset($data['id'])){
            $host = JURI::base();
            $limit = $data['limit'] ? (int)$data['limit'] : 12;
            $limitstart = $data['offset'] ? (int)$data['offset'] : 0;
            $path = "index.php?option=com_k2&view=itemlist&layout=category&task=category&id=".$data['id']."&limitstart=".$limitstart."&limit=".$limit."&format=json";

            //  Initiate curl
            $ch = curl_init();
            // Will return the response, if false it print the response
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // Set the url
            curl_setopt($ch, CURLOPT_URL,$host.$path);
            // Execute
            $json = curl_exec($ch);
            // Closing
            curl_close($ch);

            $result = json_decode($json);

            foreach($result->items as $item) {
                $item->image = $result->site->url.$item->image;
            }

            $this->plugin->setResponse($result);
        } else {
            ApiError::raiseError('400', 'Invalid request');
            return false;
        }

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
