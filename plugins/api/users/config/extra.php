<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\Sconfig;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceExtra extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'extra/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Get(
     *     path="/api/users/config",
     *     tags={"User"},
     *     summary="Get config",
     *     description="Get config",
     *     operationId="post",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/HistoryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/HistoryForm"),
     *         )
     *     ),
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
        $type = $input->get('type', '');
        $id = $input->get('id', 0);
        $result = '';
        switch ($type) {
            case 'category_new':
                $result = $this->_getNewCategory($id);
                break;
            case 'category_product':
                $result = $this->_getProductCategory($id);
                break;

            case 'campaign':
                $result = $this->_getCampaign($id);
                break;

        }
        $this->plugin->setResponse($result);
    }

    private function _getNewCategory($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT id, title FROM #__categories WHERE id =' . (int)$id;
        return $db->setQuery($sql)->loadAssoc();
    }

    private function _getProductCategory($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT id, category_name as title FROM #__eshop_categorydetails WHERE `language` = \'vi-VN\' AND category_id =' . (int)$id;
        return $db->setQuery($sql)->loadAssoc();
    }

    private function _getCampaign($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT id,  title FROM #__eshop_home_group WHERE id =' . (int)$id;
        return $db->setQuery($sql)->loadAssoc();
    }


}
