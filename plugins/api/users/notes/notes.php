<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\NotesDao;
use api\model\form\NotesForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceNotes extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'notes/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/notes",
     *     tags={"Customers"},
     *     summary="Update call",
     *     description="Update call",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/NotesForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/NotesForm"),
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
        $form = new NotesForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $dao = new NotesDao();
            $user = JFactory::getUser();
            $data['user_id'] = $user->id;
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

    /**
     * @OA\Get(
     *     path="/api/users/notes/{id}",
     *     tags={"Customers"},
     *     summary="Get history by id",
     *     description="Get history by id",
     *     operationId="post",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer id",
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
     *             @OA\Items(ref="#/components/schemas/NotesBiz")
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
            $params['where'][] = 'custommer_id = ' . (int)$id;
        }
        $dao = new NotesDao();
        $result = $dao->getHistory($params);
        $this->plugin->setResponse($result);
    }



}
