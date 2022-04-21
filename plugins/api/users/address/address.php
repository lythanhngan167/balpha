<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\CustomerDao;
use api\model\form\AddressForm;


defined('_JEXEC') or die('Restricted access');

class UsersApiResourceAddress extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'address/';

        return $routes;
    }

    public function delete()
    {
        $this->plugin->setResponse('in delete');
    }


    /**
     * @OA\Post(
     *     path="/api/users/address",
     *     tags={"Customers"},
     *     summary="Update address status",
     *     description="Update address status",
     *     operationId="post",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Form data",
     *         @OA\JsonContent(ref="#/components/schemas/AddressForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/AddressForm"),
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
        $form = new AddressForm();
        $form->setAttributes($data);
        if ($form->validate()) {
            $data = $form->toArray();
            $dao = new CustomerDao();
            $params = array(
                'set' => array(
                    'place = '.$dao->db->quote($data['address'])
                ),
                'where' => array(
                    'id ='.(int)$data['customer_id']
                )

            );
            if ($dao->update($params)) {
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
