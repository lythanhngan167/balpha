<?php

/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 4/27/2019
 * Time: 9:51 AM
 */


use api\model\Sconfig;

defined('_JEXEC') or die('Restricted access');

class UsersApiResourceConfig extends ApiResource
{
    static public function routes()
    {
        $routes[] = 'config/';

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
        $result = '';
        switch ($type) {
            case 'slide_menu':
                $result = $this->_getSlideMenu();
                break;
            case 'quick_icon':
                $result = $this->_getQuickIcon();
                break;

            case 'category_icon':
                $result = $this->_getCategory();
                break;
            case 'site_info':
                $result = $this->_getSiteInfo();
                break;
            case 'config':
                $result = $this->_getConfig();
                break;
            case 'registerForm':
                $result = $this->getConfigRegisterForm();
                break;
        }
        $this->plugin->setResponse($result);
    }

    private function _getSlideMenu()
    {
        #---------------------------------
        $result = array();
        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Trang ch???';
        $menu->component = 'ShopHomePage';
        $menu->icon = 'ios-home-outline';
        $menu->id = 0;
        $result[] = $menu;

        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Danh m???c s???n ph???m';
        $menu->component = 'CategoriesPage';
        $menu->icon = 'apps';
        $menu->id = 0;
        $result[] = $menu;

        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'T??i kho???n';
        $menu->component = 'AccountPage';
        $menu->icon = 'person';
        $menu->id = 0;
        $result[] = $menu;


        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Th??ng b??o';
        $menu->component = 'NotifyPage';
        $menu->icon = 'notifications-outline';
        $menu->id = 14;
        $result[] = $menu;

        $menu = new stdclass();
        $menu->child = array();
        $menu->title = '?????t l???ch';
        $menu->component = 'AddBookPage';
        $menu->icon = 'ios-calendar-outline';
        $menu->id = 0;
        $result[] = $menu;

        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Danh s??ch ?????t l???ch';
        $menu->component = 'BookPage';
        $menu->icon = 'ios-list-box-outline';
        $menu->id = 0;
        $result[] = $menu;
        #---------------------------------
        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Gi???i thi???u';
        $menu->component = 'ContentDetailPage';
        $menu->icon = 'ios-information-circle-outline';
        $menu->id = 17;

        $result[] = $menu;
        #---------------------------------
        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Tin t???c';
        $menu->component = 'ContentPage';
        $menu->icon = 'ios-list-box-outline';
        $menu->id = 9;
        $result[] = $menu;

        #---------------------------------
        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'C??u h???i th?????ng g??p';
        $menu->component = 'ContentPage';
        $menu->icon = 'ios-folder-open-outline';
        $menu->id = 12;
        $result[] = $menu;

        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'H??? tr??? kh??ch h??ng';
        $menu->component = 'ContentPage';
        $menu->icon = 'ios-help-buoy-outline';
        $menu->id = 13;
        $result[] = $menu;

        #---------------------------------
        $menu = new stdclass();
        $menu->child = array();
        $menu->title = 'Li??n h???';
        $menu->component = 'ContentDetailPage';
        $menu->icon = 'ios-text-outline';
        $menu->id = 20;
        $result[] = $menu;
        #---------------------------------


        $obj = new stdclass();
        $obj->menus = $this->_makeFlatMenu($result);
        return $obj;
    }

    private function _getQuickIcon()
    {
        $result = array();
        $result[] = array(
            'component' => 'ContentDetailPage',
            'text' => 'Gi???i thi???u',
            'image' => 'assets/imgs/account.jpg',
            'id' => 133
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'S???n ph???m',
            'image' => 'assets/imgs/dashboard.jpg',
            'id' => 178
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'D???ch v???',
            'image' => 'assets/imgs/web.jpg',
            'id' => 177
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'Tin t???c',
            'image' => 'assets/imgs/news.jpg',
            'id' => 86
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'Video',
            'image' => 'assets/imgs/slide.jpg',
            'id' => 177
        );
        $result[] = array(
            'component' => 'ContentDetailPage',
            'text' => 'C?? h???i KD',
            'image' => 'assets/imgs/slide.jpg',
            'id' => 118
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'FAQs',
            'image' => 'assets/imgs/search.jpg',
            'id' => 183
        );
        $result[] = array(
            'component' => 'ContentPage',
            'text' => 'Li??n h???',
            'image' => 'assets/imgs/noti.jpg',
            'id' => 86
        );
        return $result;
    }

    private function _getSiteInfo()
    {
        // System configuration.
        $jconfig = new JConfig;
        $config = new Sconfig();
        return array(
            'landing_page_link' => $jconfig->landingpage_link,
            'email' => '',
            'phone' => $config->hotline,
            'sitename' => $config->siteName,
            'faq' => 12,
            'support' => 13,
            'login_message' => '????ng nh???p ????? tr???i nghi???m d???ch v??? t???t h??n...',
            'address' => $config->address,
            'hotlines' => array(
                array(
                    'phone' => '0938908432',
                )
            ),
            'viewed_type' => '',
            'productlist_type' => 'list',
            'campaign_type' => 'list',
            'wishlist_type' => $config->wishlist_type,
            'show_wishlist' => true,
            'delivery_time' => array(
                '7 - 8 gi???',
                '8 - 9 gi???',
                '9 - 10 gi???',
                '10 - 11 gi???',
                '11 - 12 gi???',
            ),
            'delivery_date' => date('d/m/Y', strtotime(' +1 day')
            )
        );
    }

    private function _makeFlatMenu($menus)
    {
        $list = array();
        foreach ($menus as $item) {
            $list[] = $item;
            if ($item->child) {
                foreach ($item->child as $sub) {
                    $list[] = $sub;
                }
            }
        }
        foreach ($list as &$item) {
            if ($item->child) {
                $item->child = true;
            }
        }
        return $list;
    }

    private function getPhoneHtml()
    {
        $info = $this->_getSiteInfo();
        $html = array();
        if ($info['hotlines']) {

            foreach ($info['hotlines'] as $item) {
                $html[] = '<a href="tel:' . str_replace('.', '', $item['phone']) . '" class="call_to_phone">' . $item['phone'] . '</a>';
            }
        }
        return implode(' - ', $html);
    }

    private function _getConfig()
    {
        $config = new Sconfig();

        return array(
            'min_cart' => $config->minCartAmount,
            'min_cart_message' => '?????t h??ng online ch??? ??p d???ng cho ????n h??ng t???i thi???u ' . number_format($config->minCartAmount) . ' ??. ',
            'shipping_fee' => array(
                '',
                ''
            ),
            'viewed_type' => 'list',
            'show_wishlist' => true,
            'forgot_password_message' => 'N???u c?? b???t c??? v???n ????? g?? v??? vi???c kh??ng ?????t ???????c ????n h??ng , kh??ng ????ng k?? ???????c t??i kho???n, qu??n m???t kh???u. Vui l??ng li??n h??? tuy???n tr??n ????? ???????c h??? tr???.'
        );
        //$this->getPhoneHtml()
    }

    private function getConfigRegisterForm()
    {
        // System configuration.
        $config = new JConfig;
        return array(
            'showServiceMenu' => $config->enable_service_menu_signup
        );
    }
}
