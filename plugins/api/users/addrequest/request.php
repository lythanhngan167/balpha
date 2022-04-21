<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\RequestDao;
use api\model\form\RequestForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceRequest extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'addrequest/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/addrequest",
     *     tags={"Request"},
     *     summary="Add request",
     *     description="Add request",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/RequestForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/RequestForm"),
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
        $form = new RequestForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $dao = new RequestDao();
            $user = JFactory::getUser();
            $data['request_id'] = (int)$user->id;
            if ($dao->insert($data)) {
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
