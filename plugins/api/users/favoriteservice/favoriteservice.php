<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceFavoriteservice extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'favoriteservice/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/customers",
     *     tags={"Customers"},
     *     summary="Get customers list",
     *     description="Get customers list",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Get project list",
     *         @OA\JsonContent(ref="#/components/schemas/CustomerQueryForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/CustomerQueryForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/CustomerBiz")
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
        $result = array();
        $service = new stdclass();
        $service->id = "";
        $service->name = "Dịch vụ bạn quan tâm";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "8";
        $service->name = "Bảo hiểm nhân thọ";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "9";
        $service->name = "Bảo hiểm sức khỏe";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "11";
        $service->name = "Bảo hiểm ôtô";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "12";
        $service->name = "Bảo hiểm du lịch";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "13";
        $service->name = "Bảo hiểm nhà";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "14";
        $service->name = "Bảo hiểm bệnh hiểm nghèo";
        $result[] = $service;

        $service = new stdclass();
        $service->id = "15";
        $service->name = "Dịch vụ khác";
        $result[] = $service;

        $this->plugin->setResponse($result);
    }



}
