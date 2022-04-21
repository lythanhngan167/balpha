<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\CustomerDao;
use api\model\form\StatusForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceStatus extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'status/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/status",
     *     tags={"Customers"},
     *     summary="Update customer status",
     *     description="Update customer status",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/StatusForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/StatusForm"),
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
    public function post()
    {
        $data = $this->getRequestData();
        $form = new StatusForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $dao = new CustomerDao();
            if ($dao->updateStatus($data)) {
                $this->plugin->setResponse('');
                return true;
            } else {
                ApiError::raiseError('301', 'Error save');
                return false;
            }
        } else {
            ApiError::raiseError('101', $form->getFirstError());
            return false;
        }
    }


}
