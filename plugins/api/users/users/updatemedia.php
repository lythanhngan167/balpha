<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.user.user');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
require_once(JPATH_SITE . '/components/com_eshop/helpers/image.php');
require_once(JPATH_SITE . '/components/com_eshop/helpers/helper.php');

class UsersApiResourceUpdatemedia extends ApiResource
{
    /**
     * @OA\Post(
     *     path="/api/users/profilemedia",
     *     tags={"User"},
     *     summary="Update profile",
     *     description="Update profile",
     *     operationId="post",
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
    public function post()
    {
        $data = $this->getRequestData();
        if ($_FILES) {
            $user = JFactory::getUser();
            $name = $user->id . '_' . $this->_getToken(10) . '_' . md5(time());
            $fileKey = 'ionicfile';
            $fileTemp = $_FILES[$fileKey]['tmp_name'];
            $image = getimagesize($fileTemp);
            switch ($image['mime']) {
                case 'image/png':
                    $name .= '.png';
                    break;
                case 'image/jpeg':
                    $name .= '.jpg';
                    break;
            }
            $folder = 'profile';
            if (@$data['action'] == 'payment') {
                $folder = 'payment';
            }
            $uploadPath = JPATH_ROOT . '/images/' . $folder . '/' . $name;

            if (!JFile::upload($fileTemp, $uploadPath)) {
                ApiError::raiseError('101', 'Tải hình ảnh thất bại.');
                return false;
            } else {


                switch ($data['file']) {
                    case 'image_1':
                        $path = $this->getImage($name, 0, 0, $folder);
                        break;
                    default:
                        $path = $this->getImage($name, 0, 0, $folder);

                }
                if (@$data['action'] == 'payment' && @$data['orderid']) {
                    $db = JFactory::getDbo();
                    $sql = 'UPDATE #__eshop_orders SET payment_status = 1, payment_image = ' . $db->quote($name) . ' WHERE id = ' . (int)$data['orderid'] . ' AND customer_id = ' . (int)$user->id;

                    $db->setQuery($sql)->execute();
                }
                $this->plugin->setResponse(array('url' => $path, 'file' => $name));
                return true;
            }
        }
        ApiError::raiseError('101', 'Yêu cầu không tồn tại.');
        return false;

    }

    private function _getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max - 1)];
        }

        return $token;
    }

    public function getImage($image_path, $thumbnailWidth = 0, $thumbnailHeight = 0, $folder = 'profile')
    {
        if ($image_path && JFile::exists(JPATH_ROOT . '/images/' . $folder . '/' . $image_path)) {
            if ($thumbnailHeight && $thumbnailWidth) {
                $image = EshopHelper::resizeImage($image_path, JPATH_ROOT . '/images/' . $folder . '/', $thumbnailWidth, $thumbnailHeight);
                return JURI::base() . 'images/' . $folder . '/resized/' . $image;
            }
            return JURI::base() . 'images/' . $folder . '/' . $image_path;

        }
        return null;

    }


}