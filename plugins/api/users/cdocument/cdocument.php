<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\PhocadownloadCategoriesDao;
use api\model\biz\PhocadownloadCategoriesBiz;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceCdocument extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'cdocument/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }

    //index.php?option=com_api&app=users&resource=cdocument&format=raw

    /**
     * @OA\Get(
     *     path="/api/users/cdocument",
     *     tags={"Documents"},
     *     summary="Get document category",
     *     description="Get document category",
     *     operationId="get",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
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
        $dao = new PhocadownloadCategoriesDao();
        $result = $dao->getCategories($id);
        $this->plugin->setResponse($result);

    }

    /**
     * @OA\Post(
     *     path="/api/users/cdocument",
     *     tags={"Documents"},
     *     summary="Get document category",
     *     description="Get document category",
     *     operationId="post",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request",
     *     )
     * )
     */
    public function post()
    {
        $dao = new PhocadownloadCategoriesDao();
        $result = $dao->getCategories();
        $data = $this->getRequestData();
        if (@$data['include_child'] == 1) {
            $ids = array();
            foreach ($result as $item) {
                $ids[] = $item->id;
            }
            $data = $dao->getContent($ids, 4);
            foreach ($result as $item) {
                if (isset($data[$item->id])) {
                    $item->list = $data[$item->id];
                }
            }
        }
        $this->plugin->setResponse($result);

    }


}
