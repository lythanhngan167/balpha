<?php
/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Global definitions
$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

// Defines.
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
define('JPATH_THEMES',        JPATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define('JPATH_CACHE',         JPATH_BASE . DIRECTORY_SEPARATOR . 'cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'manifests');



// Custom
define('LIFE_INSURANCE',9);
define('HEALTH_INSURANCE',10);
define('TRAVEL_INSURANCE',11);
define('CAR_INSURANCE',12);
define('HOME_INSURANCE',13);
define('CRITICAL_ILLNESS_INSURANCE',14);

define('PREFERRED', 105); // uu dai
define('KNOWLEGDE', 2); // kien thuc
define('NEWS', 104); // tin tuc

//landingpage
define('TECH_INSURACNE',272);
define('FOUNDER_STORY',273);
define('FOUR_ZERO_INSURACNE',422);
define('AGENT',423);

define('SITE_NAME','Biznet');

define('PAGE_REGISTRATION_CUSTOMER',275);
define('PAGE_REGISTRATION_AGENT',300);


define('LIFE_INSURANCE_ITEM',103);
define('LIFE_HEALTH_INSURANCE_ITEM',162);
define('LIFE_TRAVEL_INSURANCE_ITEM',163);
define('LIFE_CAR_INSURANCE_ITEM',164);
define('LIFE_HOME_INSURANCE_ITEM',165);
define('LIFE_CRITICAL_ILLNESS_INSURANCE_ITEM',166);

define('DATA_NEW',          151);
define('DATA_RETURN',          150);
define('RETURN_AMOUNT',          20000);

define('PAGE_CART',401);

define('BIZ_XU','BizXu');

define('PROJECT_REQUEST_PACKAGE', 29);

define('PAGE_LOGIN', 130);

define('ERROR_SMS', 1); // Send SMS Web

define('PROJECT_PAGE', 279);

define('REST_API_KEY','NDA0MWY3ZDYtZGQxMS00YTY3LWFmZDItMjZiZWQ1MzcyMDEy');
define('APP_ID','c3f2fc0b-bc7a-4910-b061-5d75c796d91e');

define('TRANSFER_BCA', 1);

define('NOTI_ALL_GROUP', 172);
define('NOTI_CUSTOMER_GROUP', 173);
define('NOTI_AGENT_GROUP', 174);
define('NOTI_TESTER_GROUP', 175);

define('ACCOUNT_PROFILE_PAGE', 276);
define('ACCOUNT_PROJECT_PAGE', 279);

// User logs
define('TRANSFER_AGENT', 176);
define('LEVEL_UPDATE', 177);

//Personal Project
define('PERSONAL_PROJECT_MONTH', 1);
define('PERSONAL_PROJECT_TIME', 1);

define('AT_PROJECT', 32);


define('TRANFER_BIZNET', 1);

define('BIZNET_WEB',  'https://biznet.com.vn/');

define('HOTLINE_BCAVIETNAM',  '02866708870');
define('EMAIL_BCAVIETNAM',  'bcavietnam.insurance@gmail.com');
define('ADDRESS_BCAVIETNAM',  '55 Trương Quốc Dung, P.10, Q. Phú Nhuận, TP.HCM');

define('LANDINGPAGE_LINK',  'https://insurance.bcavietnam.com/dang-ky-tu-van-san-pham');

define('CATEGORY_VIDEO',  107);
// define('KNOWLEDGE_LIFE_INSURANCE',  4);

define('REFERRAL',500);