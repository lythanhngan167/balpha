<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\dao\shop\ShopCustomerDao;


defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');

class UsersApiResourceInfo extends ApiResource
{
    /**
     * @OA\Get(
     *     path="/api/users/info",
     *     tags={"User"},
     *     summary="Get user info",
     *     description="Get userinfo",
     *     operationId="get",
     *     security = { { "bearerAuth": {} } },
     *     @OA\RequestBody(
     *         required=true,
     *         description="Change password",
     *         @OA\JsonContent(ref="#/components/schemas/ChangePasswordForm"),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(ref="#/components/schemas/ChangePasswordForm"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful login",
     *         @OA\Schema(ref="#/components/schemas/ErrorModel"),
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
        if($id){
            $user = JFactory::getUser($id);
        }else{
            $user = JFactory::getUser();
        }

        $groups = JAccess::getGroupsByUser($user->id, false);

        $lang = JFactory::getLanguage();
        $extension = 'com_users';
        $base_dir = JPATH_ADMINISTRATOR;
        $language_tag = 'vi-VN';
        $reload = true;
        $lang->load($extension, $base_dir, $language_tag, $reload);
        $birthday = $user->birthday;
        if($birthday){
            $birthday = date("d/m/Y", strtotime($birthday));
        }
        $province = '';
        if($user->province){
            $db = JFactory::getDbo();
            $sql = 'SELECT country_name FROM #__eshop_countries WHERE id = '.(int)$user->province;
            $province = $db->setQuery($sql)->loadResult();
        }
        // $is_partner = in_array(2, $groups) || in_array(3, $groups) || in_array(13, $groups);
        $data = array(
            'name' => $user->name,
            'sex' => $user->job ? JTEXT::_('COM_USERS_USER_SEX_OPTION_'.$user->sex) : '',
            'card_id' => $user->card_id,
            'birthday' => $birthday,
            'm_id' => $user->id_biznet,
            'province' => $province,
            'level' => (int) $user->level,
            'level_tree' => $user->level_tree,
            'job' => $user->job ? JTEXT::_('COM_USERS_USER_JOB_OPTION_'.$user->job) : '',
            'card_front' => $this->getImage($user->card_front),
            'card_behind' => $this->getImage($user->card_behind),
            'address' => $user->address,
            'phone' => $user->phone,
            'approved' => $user->approved,
            // 'role' => $is_partner ? 'stock' : 'customer'
            'group' => $groups[0],
            'money' => number_format($user->money,0,".","."),
            'is_apple' => $user->is_apple === 1 ? true : false
        );
        $this->plugin->setResponse($data);

        return true;
    }

    public function getImage($img){
        if($img){
            return JURI::base() . 'images/profile/' . $img;
        }
        return null;
    }


}
