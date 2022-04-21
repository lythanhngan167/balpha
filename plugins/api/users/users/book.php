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

class UsersApiResourceBook extends ApiResource
{
    /**
     * @OA\Get(
     *     path="/api/users/book",
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
        $user = JFactory::getUser();
        $sql = 'SELECT u.name ,r.phone, r.address, r.note, concat(r.hours, \' giờ\') as hours, DATE_FORMAT(r.booking_date, \'%d/%m/%Y\') as booking_date, DATE_FORMAT(r.created_time, \'%d/%m/%Y\') as created_time, r.status_id as status_name 
        FROM #__booking as r 
        LEFT JOIN #__users as u ON r.created_by = u.id
        WHERE (r.created_by = '.$user->id.' ) order by r.id DESC';

        $result = JFactory::getDbo()->setQuery($sql)->loadAssocList();
        if ($result) {
            $lang = \JFactory::getLanguage();
            $extension = 'com_book';
            $base_dir = JPATH_SITE;
            $language_tag = 'vi-VN';
            $reload = true;
            $lang->load($extension, $base_dir, $language_tag, $reload);
            foreach ($result as &$item) {
                $item['address'] = JText::_('COM_BOOK_BOOKINGS_ADDRESS_OPTION_'.$item['address']);
                $item['status_name'] = JText::_('COM_BOOK_BOOKINGS_STATUS_ID_OPTION_'.$item['status_name']);
            }
        }

        $this->plugin->setResponse($result);
        return true;
    }

    public function post()
    {
        $user = JFactory::getUser();
        $data = $this->getRequestData();
        $obj = new stdClass();
        $obj->phone = $user->username;
        $obj->address = $data['address'];
        $obj->booking_date = $data['booking_date'];
        $obj->hours = $data['hours'];
        $obj->note = $data['note'];
        $obj->state = 1;
        $obj->status_id = 1;
        $obj->created_by = $user->id;
        $obj->created_time = JFactory::getDate()->toSql();
        $result = JFactory::getDbo()->insertObject('#__booking', $obj, 'id');
        if ($result) {
            $message = 'Đặt lịch thành công.';
        } else {
            $message ='Vui lòng thử lại.';
        }
        $this->plugin->setResponse($message);

        $mailParams = array(
            'subject' => 'Đặt lịch',
            'booking_date' => $data('d/m/Y', $obj->booking_date),
            'address' => $obj->address,
            'note' => $obj->note

        );
        $sql = 'SELECT 	config_value FROM #__eshop_configs WHERE config_key = \'email\'';
        $db = JFactory::getDbo();
        $emailAdmin = $db->setQuery($sql)->loadResult();
        if($emailAdmin){
            $this->_sendMail('book_notify', $emailAdmin, $mailParams);
        }
        
        return true;
    }

    private function _sendMail($type, $recipient, $params)
    {
        $mailer = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
            $config->get('mailfrom'),
            $config->get('fromname')
        );
        $mailer->setSender($sender);
        $mailer->addRecipient($recipient);
        $mailer->isHtml(true);

        $body = $this->_getTemplate($type, $params);
        $mailer->setSubject($params['subject']);
        $mailer->setBody($body);
        try{
            $mailer->Send();
            return true;
        } catch (Exception $e){
            return false;
        }

    }

    private function _getTemplate($type, $params)
    {
        $message = '';
        switch ($type) {
            case 'book_notify':
                $message = "<p>Ngày hẹn: " . $params['booking_date'] . "</p>";
                $message .= "<p>Thời gian: " . $params['hours'] . "</p>";
                $message .= "<p>Địa chỉ: " . $params['address'] . "</p>";
                $message .= "<p>Ghi chú: " . $params['note'] . "</p>";
                
                break;
        }
        return $message;
    }
}
