<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\RequestDao;
use api\model\form\QuestionForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceAddRequest extends ApiResource
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
     *         @OA\JsonContent(ref="#/components/schemas/QuestionForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/QuestionForm"),
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
        $form = new QuestionForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $dao = new RequestDao();
            $user = JFactory::getUser();
            $form->request_id = (int)$user->id;
            $form->created_by = $form->request_id;
            $form->status_id = 'new';
            $form->state = 1;
            $form->created_date = date('Y-m-d H:i:s');
            if ($dao->insert($form)) {
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
