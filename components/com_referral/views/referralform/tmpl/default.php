<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Referral
 * @author     Truyền Đặng Minh <minhtruyen.ut@gmail.com>
 * @copyright  2021 Truyền Đặng Minh
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
HTMLHelper::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = Factory::getLanguage();
$lang->load('com_referral', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_referral/js/form.js');

$user    = Factory::getUser();
$canEdit = ReferralHelpersReferral::canUserEdit($this->item, $user);

// if ($user->id && !isset($_GET['referral_code'])) {
// 	if (!$user->referral_code) {
// 		$db = JFactory::getDbo();
// 		$useru = new stdClass();
// 		$useru->id = $user->id;
// 		$useru->referral_code = Factory::gen_uuid();
// 		$result = $db->updateObject('#__users', $useru, 'id');
// 		$base_url = JURI::getInstance() . '?referral_code=' . $useru->referral_code;
// 	} else {
// 		$base_url = JURI::getInstance() . '?referral_code=' . $user->referral_code;
// 	}
// 	header('Location:' . $base_url);
// }
$ip = $this->get_client_ip();
$user_agent = $_SERVER['HTTP_USER_AGENT'];
?>

<style type="text/css">
@media only screen and (min-width: 400px) {
    .top_download {
        height: 22.4vw;
        padding-left: 4.26vw;
        padding-right: 4.26vw;
        position: fixed;
        z-index: 9999999;
        width: 100%;
        background-color: white;
        display: flex;
        align-items: center;
        flex-direction: row;
        box-shadow: #4e4e4e;
    }
    .top_download span {
        font-size: 4.26vw;
        color: #141ed2;
        font-family: AvenirNextBold;
    }
    .top_download button {
        background-color: #141ed2;
        width: 32.8vw;
        height: 11.73vw;
        border-radius: 1.06vw;
        border-style: none;
        text-align: center;
        color: white;
        font-size: 3.73vw;
        margin-left: 15.26vw;
    }
    .padding_topdownload {
        display: block;
        height: 22.4vw;
        width: 100%;
        background-color: white;
    }
    .common_margin_bottom {
        margin-bottom: 4.266vw;
    }
    .modalbg .panel {
        background-color: white;
        border-radius: 2vw;
        margin-left: 4.266vw;
        margin-right: 4.266vw;
        height: 83.733vw;
        margin-top: 30vh;
        box-sizing: border-box;
    }
    .modalbg .panel .title_wrap {
        background-color: #141ed2;
        height: 12.266vw;
        border-top-left-radius: 2vw;
        border-top-right-radius: 2vw;
        position: relative;
    }
    .modalbg .panel .title_wrap span {
        color: white;
        text-align: center;
        line-height: 12.266vw;
        font-size: 5.333vw;
    }
    .modalbg .panel .title_wrap img {
        width: 6.4vw;
        height: 6.4vw;
        line-height: 12.266vw;
        position: absolute;
        right: 4vw;
        top: 3vw;
    }
    .modalbg .panel .content {
        padding-left: 4.266vw;
        padding-right: 4.266vw;
        padding-top: 8vw;
        padding-bottom: 8vw;
        color: black;
        font-size: 4.266vw;
        text-align: justify;
        line-height: 5.866vw;
    }
    .modalbg .guide_container {
        width: 91.466vw;
        height: 162.4vw;
        background-color: white;
        border-radius: 1.333vw;
        align-self: center;
        transform: scale(0.9, 0.9);
    }
    .modalbg .guide_container .guide_header {
        width: 91.466vw;
        height: 11.73vw;
        background-color: #141ed2;
        border-top-left-radius: 1.333vw;
        border-top-right-radius: 1.33vw;
        position: relative;
    }
    .modalbg .guide_container .guide_header span {
        color: white;
        text-align: center;
        line-height: 11.73vw;
        font-size: 4.266vw;
    }
    .modalbg .guide_container .guide_header img {
        width: 6.4vw;
        height: 6.4vw;
        line-height: 12.266vw;
        position: absolute;
        right: 3vw;
        top: 3vw;
    }
    .modalbg .guide_container .guide_img {
        padding: 4.266vw;
        display: flex;
    }
    .modalbg .guide_container .guide_img img {
        width: 82.93vw;
        height: 80.266vw;
    }
    .modalbg .guide_container .guide_des {
        padding-left: 4.266vw;
        padding-right: 4.26vw;
    }
    .modalbg .guide_container .guide_des p {
        font-size: 3.733vw;
        color: #787878;
        line-height: 4.8vw;
        text-align: left;
    }
    .modalbg .guide_container .guide_term {
        padding-left: 4.26vw;
        padding-right: 4.26vw;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
    }
    .modalbg .guide_container .guide_term img {
        width: 6.4vw;
        height: 6.4vw;
        margin-right: 1vw;
    }
    .modalbg .guide_container .guide_term span {
        font-size: 3.733vw;
        color: #787878;
        line-height: 4.8vw;
        text-align: left;
    }
    .modalbg .guide_container button {
        border-style: none;
        background-color: #C5CEEA;
        width: 82.93vw;
        height: 11.73vw;
        color: white;
        font-size: 3.733vw;
        line-height: 11.73vw;
        text-align: center;
        margin-top: 4.266vw;
        border-radius: 1.333vw;
    }
    .header-new {
        /* background-color: #141ed2; */
        color: white;
        height: 98vw;
        margin: 0 0 0 0;
        padding-left: 4.9vw;
        padding-top: 6.4vw;
        position: relative;
        background-image: url('<?php echo Uri::base() ?>images/web_referred.png');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top;
        /* z-index: -2; */
    }
    .header-new .logomb {
        width: 20.8vw;
        height: auto;
        margin-bottom: 0.1vw;
    }
    .header-new .logo_mobile {
        position: absolute;
        width: 100vw;
        height: auto;
        right: 0;
        bottom: 0;
        display: block;
        z-index: 1;
    }
    .header-new .logo_web {
        position: absolute;
        width: 100vw;
        height: auto;
        right: 0;
        bottom: 0;
        display: none;
    }
    .header-new .title {
        font-size: 4.8vw;
        line-height: 5.86vw;
        width: 100%;
        margin-bottom: 1.5vw;
        font-family: AvenirNextBold;
        letter-spacing: 0.03vw;
        margin-top: 2vw;
        color: #141ed2;
    }
    .header-new .des_mobile {
        font-size: 3.2vw;
        line-height: 4.26vw;
        width: 75%;
        display: none;
    }
    .header-new .des_web {
        display: none;
    }
    .header-new .download {
        font-size: 2.666vw;
        color: white;
        margin-top: 6.93vw;
        width: 40%;
    }
    .header-new button {
        width: 48.8vw;
        height: 13.33vw;
		background-color: #ee7d30;
		border-radius: 1.5vw;
		text-align: center;
		color: #ffffff;
        font-size: 4.26vw;
        font-family: AvenirNextBold;
        margin-top: 56.53vw;
        align-self: center;
        margin-left: 21.4vw;
        z-index: 33333;
        position: relative;
        /* border-style: none; */
    }
    .detail_pack {
        display: block;
        background-color: #edeaf7;
        padding: 6.4vw 4.26vw 6.4vw 4.26vw;
        height: 79.46vw;
        box-sizing: border-box;
    }
    .detail_pack .row_des {
        display: flex;
        flex-direction: row;
        margin-bottom: 4.26vw;
    }
    .detail_pack .row_des img {
        width: 7.4vw;
        height: 7.4vw;
        margin-right: 4.26vw;
    }
    .detail_pack .row_des span {
        flex: 1;
        font-size: 3.733vw;
        line-height: 5.33vw;
        color: #141ed2;
        font-family: AvenirNextBold;
    }
    .block1 {
        background-color: #e9e9e9;
        padding-left: 4.2vw;
        padding-right: 4.2vw;
        padding-top: 10.66vw;
        padding-bottom: 4.66vw;
    }
    .block1 .title {
        color: #141ed2;
        font-size: 4.8vw;
        line-height: 8vw;
        font-family: AvenirNextBold;
        margin-bottom: 4.2vw;
        letter-spacing: 0.15vw;
    }
    .block1 .panel-wrap {
        display: flex;
        flex-direction: column;
    }
    .block1 .panel-wrap .panel {
        width: auto;
        background-color: white;
        /*padding: 4.2vw;*/
        border-radius: 1.333vw;
        font-size: 4.266vw;
        line-height: 5.866vw;
        letter-spacing: 0.133vw;
        margin-bottom: 4.2vw;
        padding-right: 4.26vw;
        padding-left: 4.26vw;
        padding-top: 4.26vw;
        padding-bottom: 4.26vw;
        box-sizing: border-box;
        margin-right: 0;
    }
    .block1 .panel-wrap .panel img {
        width: 10.66vw;
        height: 10.66vw;
        margin-right: 4.26vw;
    }
    .block1 .panel-wrap .panel .money {
        color: #141ed2;
        font-size: 5.33vw;
        letter-spacing: 0.048vw;
        font-family: AvenirNextBold;
    }
    .block1 .panel-wrap .panel p {
        margin-left: 14.93vw;
        font-size: 3.73vw;
        color: black;
        margin-top: 2vw;
    }
    .block1 .note {
        width: auto;
        background-color: white;
        /*padding: 4.2vw;*/
        border-radius: 1.333vw;
        margin-bottom: 4.26vw;
        height: 54.4vw;
        padding-right: 8.4vw;
        padding-left: 2.2vw;
        padding-top: 3vw;
        box-sizing: border-box;
    }
    .block1 .note .title_wrap {
        display: flex;
        flex-direction: row;
        margin-bottom: 3vw;
    }
    .block1 .note .title_wrap img {
        width: 6.4vw;
        height: 6.4vw;
        margin-right: 3vw;
    }
    .block1 .note .title_wrap span {
        font-size: 4.266vw;
        color: #141ed2;
        line-height: 6.4vw;
        font-family: AvenirNextBold;
    }
    .block1 .note ul {
        display: block;
        list-style-type: disc;
        margin-block-start: 0;
        margin-block-end: 0;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 50px;
    }
    .block1 .note li {
        font-size: 3.733vw;
        color: #4e4e4e;
        line-height: 4.3vw;
        margin-bottom: 7vw;
        font-family: AvertaStdCYRegular;
    }
    .block1 .note-detail {
        color: black;
        font-size: 3.733vw;
        line-height: 6.4vw;
    }
    .block1 .note-detail a {
        color: #141ed2;
        font-size: 3.73vw;
        line-height: 5.86vw;
        font-family: AvenirNextBold;
        text-decoration: underline;
        margin-bottom: 10.66vw;
    }
    .block2 {
        width: auto;
        height: 194vw;
        background-color: #141ed2;
        padding: 10.66vw 4.266vw 5.866vw 4.266vw;
        position: relative;
        background-image: url('../img/MB_3D_Background_App.png');
        background-repeat: no-repeat;
        background-size: contain;
        background-position: top;
    }
    .block2 .title {
        line-height: 6vw;
        color: white;
        font-size: 4.8vw;
        font-family: AvenirNextBold;
        letter-spacing: 0.13vw;
        z-index: 1;
    }
    .block2 .logo {
        width: 100%;
        height: auto;
        position: absolute;
        top: 0;
        right: 0;
        z-index: 0;
    }
    .block2 .panel-wrap {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 7vw;
        width: auto;
        z-index: 1;
    }
    .block2 .panel-wrap .panel {
        width: 27.733vw;
        height: 27.733vw;
        border-radius: 0.64vw;
        background-color: #f0f5fc;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5);
        margin-bottom: 4.266vw;
        text-align: center;
    }
    .block2 .panel-wrap .panel2 {
        width: 42.66vw;
        height: 27.733vw;
        border-radius: 0.64vw;
        background-color: #f0f5fc;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.5);
        margin-bottom: 4.266vw;
        text-align: center;
        position: relative;
    }
    .block2 .panel-wrap .panel2 .hot {
        width: 16vw;
        height: 16vw;
        position: absolute;
        right: -0.3vw;
        top: -2.91vw;
        z-index: 99999;
    }
    .block2 .panel-wrap .panel2 img {
        width: 8.64vw;
        height: auto;
        margin-bottom: 0vw;
        margin-top: 2.66vw;
    }
    .block2 .panel-wrap .panel2 .des {
        font-size: 2.66vw;
        color: #26417f;
        /* font-family: AvenirNextBold; */
        margin-left: 2.66vw;
        margin-right: 2.66vw;
        text-align: center;
    }
    .block2 .panel-wrap .panel img {
        width: 8.64vw;
        height: auto;
        margin-bottom: 0vw;
        margin-top: 2.66vw;
    }
    .block2 .panel-wrap .panel .des {
        font-size: 2.66vw;
        color: #26417f;
        /* font-family: AvenirNextBold; */
        margin-left: 2.66vw;
        margin-right: 2.66vw;
        text-align: center;
    }
    .block2 .family_container {
        border-radius: 0.64vw;
        width: 91.466vw;
        height: 72.53vw;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        box-sizing: border-box;
        z-index: 1;
    }
    .block2 .family_container .banner {
        border-top-right-radius: 0.64vw;
        border-top-left-radius: 0.64vw;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
        width: 100%;
        height: 37.33vw;
        background-color: white;
        position: relative;
        background-image: url(../img/webfamily.png);
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
    }
    .block2 .family_container .banner .img1 {
        position: absolute;
        width: 24vw;
        height: 25vw;
        top: 0;
        left: 4.266vw
    }
    .block2 .family_container .des {
        border-top-right-radius: 0;
        border-top-left-radius: 0;
        border-bottom-right-radius: 0.64vw;
        border-bottom-left-radius: 0.64vw;
        background-color: #e2efff;
        height: 35.73vw;
        padding-top: 4.26vw;
        padding-left: 4.26vw;
        padding-right: 4.26vw;
        display: flex;
        flex-direction: column;
        width: auto;
        box-sizing: border-box;
        text-align: center;
    }
    .block2 .family_container .des span {
        font-size: 4.266vw;
        letter-spacing: 0.133vw;
        color: #141ed2;
        font-family: AvenirNextBold;
        margin-left: 0vw;
        margin-right: 0vw;
    }
    .block2 .family_container .des button {
        background-color: white;
        width: 42.133vw;
        height: 8vw;
        border-radius: 3.6vw;
        border: none;
        align-self: center;
        color: #1724db;
        font-size: 3.2vw;
        text-align: center;
        margin-right: auto;
        margin-left: 0vw;
        margin-top: 8vw;
        align-self: center;
    }
    .address {
        width: 100%;
        color: #4e4e4e;
        font-size: 3.2vw;
        line-height: 4.266vw;
        letter-spacing: 0.1013vw;
        height: 40vw;
        background-color: white;
        padding: 5.866vw 4.266vw 5.866vw 4.266vw;
        margin-bottom: 4.266vw;
        box-sizing: border-box;
    }
    .address .text1 {
        display: inline;
    }
    .address .text1 .logo1 {
        width: 10.54vw;
        height: auto;
        margin-right: 4.266vw;
    }
    .address .text2 {}
}

@media only screen and (min-width: 992px) {
    .padding_topdownload {
        display: none;
    }
    .top_download {
        display: none;
    }
    .modalbg .panel {
        background-color: white;
        border-radius: 0.555vw;
        margin-left: 31.11vw;
        margin-right: 31.11vw;
        height: 18.055vw;
        margin-top: 30vh;
        box-sizing: border-box;
    }
    .modalbg .panel .title_wrap {
        background-color: #141ed2;
        height: 3.1944vw;
        border-top-left-radius: 0.555vw;
        border-top-right-radius: 0.555vw;
        position: relative;
    }
    .modalbg .panel .title_wrap span {
        color: white;
        text-align: center;
        line-height: 3.1944vw;
        font-size: 1.3888vw;
    }
    .modalbg .panel .title_wrap img {
        width: 1.666vw;
        height: 1.666vw;
        line-height: 3.1944vw;
        position: absolute;
        right: 1.5vw;
        top: 0.8vw;
    }
    .modalbg .panel .content {
        padding-left: 2.22vw;
        padding-right: 2.22vw;
        padding-top: 2.08vw;
        padding-bottom: 2.08vw;
        color: black;
        font-size: 1.1111vw;
        text-align: center;
        line-height: 1.527;
    }
    .modalbg .guide_container {
        width: 40.27vw;
        height: 40.27vw;
        background-color: white;
        border-radius: 0.3472vw;
        align-self: center;
        transform: scale(1, 1);
    }
    .modalbg .guide_container .guide_header {
        width: 40.27vw;
        height: 3.33vw;
        background-color: #141ed2;
        border-top-left-radius: 0.3472vw;
        border-top-right-radius: 0.3472vw;
        position: relative;
    }
    .modalbg .guide_container .guide_header span {
        color: white;
        text-align: center;
        line-height: 3.33vw;
        font-size: 1.388vw;
    }
    .modalbg .guide_container .guide_header img {
        width: 1.66vw;
        height: 1.66vw;
        line-height: 12.266vw;
        position: absolute;
        right: 2.22vw;
        top: 1vw;
    }
    .modalbg .guide_container .guide_img {
        padding: 1.111vw;
        display: flex;
        justify-content: center;
    }
    .modalbg .guide_container .guide_img img {
        width: 21.597vw;
        height: 21.597vw;
    }
    .modalbg .guide_container .guide_des {
        padding-left: 2.22vw;
        padding-right: 2.22vw;
    }
    .modalbg .guide_container .guide_des p {
        font-size: 0.9722vw;
        color: #787878;
        line-height: 1.25vw;
        text-align: left;
    }
    .modalbg .guide_container .guide_term {
        padding-left: 2.22vw;
        padding-right: 2.22vw;
        display: flex;
        flex-direction: row;
        align-items: center;
    }
    .modalbg .guide_container .guide_term img {
        width: 1.66vw;
        height: 1.66vw;
        margin-right: 0.2vw;
    }
    .modalbg .guide_container .guide_term span {
        font-size: 0.9722vw;
        color: #787878;
        line-height: 1.25vw;
        text-align: left;
    }
    .modalbg .guide_container button {
        border-style: none;
        background-color: #C5CEEA;
        width: 36.944vw;
        height: 3.05vw;
        color: white;
        font-size: 0.9722vw;
        line-height: 3.05vw;
        text-align: center;
        margin-top: 1.111vw;
        border-radius: 0.3472vw;
    }
    .header-new {
        /* background-color: #141ed2; */
        color: white;
        height: 37.5vw;
        margin: 0;
        padding-left: 15.069vw;
        padding-top: 1.305vw;
        position: relative;
        background-image: url('<?php echo Uri::base() ?>images/web_referred.png');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top;
        /* z-index: -2; */
    }
    .header-new .logomb {
        width: 9.722vw;
        height: auto;
        margin-bottom: 1vw;
    }
    .header-new .logo_mobile {
        position: absolute;
        width: 100vw;
        height: auto;
        right: 0;
        bottom: 0;
        display: none;
        z-index: 1;
    }
    .header-new .logo_web {
        position: absolute;
        width: 53.61vw;
        height: auto;
        right: 0;
        bottom: 0;
        display: block;
        z-index: 1;
    }
    .header-new .title {
        font-size: 2.5vw;
        line-height: 2.777vw;
        width: 30%;
        margin-bottom: 2vw;
        letter-spacing: 0.008vw;
        margin-top: 0;
        color: #141ed2
    }
    .header-new .des_mobile {
        font-size: 1.11vw;
        line-height: 1.2vw;
        width: 37%;
        font-family: AvertaStdCYRegular;
        margin-top: 0;
        display: none;
    }
    .header-new .des_web {
        display: flex;
        flex-direction: row;
        /*justify-content: ""*/
        width: 58%;
        margin-bottom: 1.111vw;
        align-items: center;
    }
    .header-new .des_web .icon_left {
        width: 2.77vw;
        height: 2.77vw;
        margin-right: 1.666vw;
    }
    .header-new .des_web .content {
        font-size: 1.11vw;
        line-height: 1.388vw;
        width: 60%;
        color: #141ed2;
        font-family: AvenirNextSemibold;
    }
    .header-new .download {
        font-size: 1.111vw;
        color: white;
        margin-top: 2vw;
        letter-spacing: 0.03vw;
        width: 20%;
    }
	.header-new .input-register{
		height: 45px;
		border-radius: 15px;
		padding: 10px;
	}
    .header-new button {
        width: 21.7vw;
        height: 3.05vw;
		background-color: #ee7d30;
		border-radius: 1.5vw;
		text-align: center;
		color: #ffffff;
        font-size: 1.11vw;
        font-family: AvenirNextBold;
        margin-top: 0.33vw;
        border: none;
        margin-left: 0;
        z-index: 2;
    }
    .detail_pack {
        display: none;
        background-color: rgba(220, 237, 253, 0.3);
        padding: 4.266vw;
    }
    .block1 {
        background-color: white;
        padding: 4.2vw;
        padding-left: 15.069vw;
        padding-right: 14.58vw;
        padding-top: 4.16vw;
        padding-bottom: 4vw;
    }
    .block1 .title {
        color: #141ed2;
        font-size: 1.944vw;
        line-height: 2.77vw;
        font-family: AvenirNextBold;
        margin-bottom: 3.88vw;
        letter-spacing: 0.078vw;
    }
    .block1 .panel-wrap {
        display: flex;
        flex-direction: row;
        /* justify-content: space-between; */
    }
    .block1 .panel-wrap .panel {
        width: 30.97vw;
        background-color: white;
        border-radius: 1.333vw;
        font-size: 1.111vw;
        line-height: 1.5277vw;
        letter-spacing: 0.034vw;
        margin-bottom: 0vw;
        height: 15.13vw;
        padding-right: 0vw;
        padding-left: 0vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        box-sizing: border-box;
        margin-right: 3vw;
    }
    .block1 .panel-wrap .panel img {
        width: 4.166vw;
        height: auto;
        margin-right: 1.11vw;
    }
    .block1 .panel-wrap .panel .money {
        color: #141ed2;
        font-size: 1.666vw;
        letter-spacing: 0.0125vw;
        font-family: AvertaStdCYRegular;
    }
    .block1 .panel-wrap .panel p {
        margin-left: 0;
        font-size: 1.11vw;
        color: black;
        margin-top: 1.11vw;
    }
    .block1 .note {
        height: 5.416vw;
        background-color: white;
        /*padding: 4.2vw;*/
        border-radius: 0;
        margin-bottom: 1vw;
        padding-right: 0vw;
        padding-left: 0vw;
        padding-top: 0vw;
        box-sizing: border-box;
    }
    .block1 .note .title_wrap {
        display: flex;
        flex-direction: row;
        margin-bottom: 0.5vw;
    }
    .block1 .note .title_wrap img {
        width: 1.66vw;
        height: 1.66vw;
        margin-right: 1vw;
    }
    .block1 .note .title_wrap span {
        font-size: 1.1111vw;
        color: #141ed2;
        line-height: 1.66vw;
    }
    .block1 .note ul {
        display: block;
        list-style-type: none;
        margin-block-start: 0;
        margin-block-end: 0;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
        padding-inline-start: 0px;
    }
    .block1 .note li {
        font-size: 1.111vw;
        line-height: 1.527vw;
        color: #141ed2;
        margin-bottom: 0vw;
        font-family: AvenirNextBold;
    }
    .block1 .note-detail {
        color: black;
        font-size: 1.111vw;
        line-height: 1.527vw;
    }
    .block1 .note-detail a {
        color: #141ed2;
        font-size: 1.111vw;
        line-height: 1.527vw;
        font-family: AvenirNextBold;
    }
    .block2 {
        width: auto;
        height: 56.26vw;
        background-color: #141ed2;
        padding: 4.16vw 14.583vw 3.402vw 14.583vw;
        position: relative;
        background-image: url('../img/MB_3D_Background_App.png');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: top;
    }
    .block2 .title {
        line-height: 2.77vw;
        color: white;
        width: 100%;
        font-size: 1.94vw;
        font-family: AvenirNextBold;
        letter-spacing: 0.078vw;
    }
    .block2 .logo {
        width: 31.736vw;
        height: auto;
        position: absolute;
        top: 0;
        right: 0;
    }
    .block2 .panel-wrap {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 4.4444vw;
        width: 69.861vw;
    }
    .block2 .panel-wrap .panel {
        background-color: white;
        box-sizing: border-box;
        width: 15.27vw;
        height: 15.27vw;
        border-radius: 0.277vw;
        margin-bottom: 2.522vw;
        padding: 0.833vw;
    }
    .block2 .panel-wrap .panel2 {
        background-color: white;
        box-sizing: border-box;
        width: 15.27vw;
        height: 15.27vw;
        border-radius: 0.277vw;
        margin-bottom: 2.522vw;
        padding: 0.833vw;
        position: relative;
    }
    .block2 .panel-wrap .panel2 .hot {
        width: 5.416vw;
        height: 5.416vw;
        position: absolute;
        right: -0.3vw;
        top: -2.2vw;
        z-index: 99999;
    }
    .block2 .panel-wrap .panel2 img {
        width: 4.16vw;
        height: 4.16vw;
        margin-bottom: 1vw;
        margin-top: 1.9vw;
    }
    .block2 .panel-wrap .panel2 .des {
        font-size: 1.111vw;
        font-family: AvertaStdCYRegular;
        color: #26417f;
        margin-top: 0vw;
        margin-left: 0vw;
        margin-right: 0vw;
    }
    .block2 .panel-wrap .panel img {
        width: 4.16vw;
        height: 4.16vw;
        margin-bottom: 1vw;
        margin-top: 1.9vw;
    }
    .block2 .panel-wrap .panel .des {
        font-size: 1.111vw;
        font-family: AvertaStdCYRegular;
        color: #26417f;
        margin-top: 0vw;
        margin-left: 0vw;
        margin-right: 0vw;
    }
    .block2 .family_container {
        border-radius: 0.64vw;
        width: 69.861vw;
        height: 11.666vw;
        box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: row;
        /*align-content: center;*/
    }
    .block2 .family_container .banner {
        border-top-right-radius: 0;
        border-top-left-radius: 0.64vw;
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0.64vw;
        width: 25.76vw;
        height: 100%;
        background-color: white;
        position: relative;
        background-image: url(../img/webfamily.png);
        background-repeat: no-repeat;
        background-size: contain;
        background-position: center;
    }
    .block2 .family_container .banner .img1 {
        position: absolute;
        width: 24vw;
        height: 25vw;
        top: 0;
        left: 4.266vw
    }
    .block2 .family_container .des {
        border-top-right-radius: 0.64vw;
        border-top-left-radius: 0;
        border-bottom-right-radius: 0.64vw;
        border-bottom-left-radius: 0;
        background-color: #e2efff;
        height: 100%;
        width: 44.101vw;
        padding-top: 1.701vw;
        padding-left: 6.6319vw;
        padding-right: 6.6319vw;
        display: flex;
        flex-direction: column;
        text-align: center;
        box-sizing: border-box;
    }
    .block2 .family_container .des span {
        font-size: 1.25vw;
        letter-spacing: 0.038vw;
        color: #141ed2;
        font-family: AvenirNextBold;
        margin-left: 3vw;
        margin-right: 3vw;
    }
    .block2 .family_container .des button {
        background-color: #141ed2;
        width: 12.63vw;
        height: 2.22vw;
        border-radius: 0.9375vw;
        border: none;
        align-self: center;
        color: white;
        font-size: 0.833vw;
        text-align: center;
        margin-right: auto;
        margin-left: auto;
        margin-top: 3.055vw;
    }
    .address {
        color: #4e4e4e;
        font-size: 1.25vw;
        line-height: 1.80vw;
        letter-spacing: 0.038vw;
        height: 13vw;
        margin-top: 0;
        margin-bottom: 0vw;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        padding: 4.16vw 15.486vw 0 15.486vw;
        box-sizing: border-box;
        background-color: white;
    }
    .address .text1 {
        display: inline-flex;
    }
    .address .text1 .logo1 {
        width: auto;
        height: 2.5vw;
        margin: 0;
        margin-right: 1.736vw;
    }
    .address .text1 p {
        margin: 0;
    }
    .address .text2 {}
}
</style>

    <div class="header-new">
        <img class="logomb" src="<?php echo Uri::base() ?>images/logo-default.png">
        <div class="title">
            Đăng ký xong ngay Tiền về liền tay
        </div>
        <div class="des_mobile">
            Nhận ngay 30.000 VND khi đăng ký thành công và cơ hội
            <font style="color:#f8e000;">nhận thêm 10.000.000 VND++</font>khi giới thiệu bạn bè, người thân sử dụng App MBBank
        </div>
        <div class="des_web">
            <img class="icon_left" src="<?php echo Uri::base() ?>images/ic1.png">
            <div class="content" style="color: #ff0500">
                Miễn phí chọn số tài khoản trùng số điện thoại
            </div>

        </div>
        <div class="des_web">
            <img class="icon_left" src="<?php echo Uri::base() ?>images/ic2.png">
            <div class="content">
                Nhận ngay 30.000 VND khi đăng ký thành công
            </div>

        </div>

        <div class="des_web">
            <img class="icon_left" src="<?php echo Uri::base() ?>images/ic3.png">
            <div class="content">
                Cơ hội
                <font style="color: #ff0500;"> nhận thêm 10.000.000 VND++ </font>
                <br>khi giới thiệu bạn bè, người thân sử dụng App MBBank
            </div>

        </div>
        <div class="des_web">
            <img class="icon_left" src="<?php echo Uri::base() ?>images/ic4.png">
            <div class="content">
                App MBBank - miễn phí chuyển khoản liên ngân hàng trọn đời và lựa chọn tài khoản Số đẹp miễn phí
            </div>

        </div>
		
		<form id="form-referral" action="<?php echo Route::_('index.php?option=com_referral&task=referralform.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
		<!-- <div class="input-r"> -->
		<input type="text" name="jform[name]" id="jform_name" value="" class="required input-register" placeholder="Nhập tên" required="" aria-required="true">
		<input type="text" name="jform[phone]" id="jform_phone" value="" class="required input-register" placeholder="Nhập số điện thoại" required="" aria-required="true">
		<!-- </div> -->
		<br>
		<input type="hidden" name="jform[ip]" id="jform_ip" value="<?php echo $ip; ?>">
		<input type="hidden" name="jform[http_user_agent]" id="jform_http_user_agent" value="<?php echo $user_agent; ?>">
		<input type="hidden" name="jform[referral_code]" id="jform_referral_code" value="<?php if(isset($_GET['referral_code'])){ echo $_GET['referral_code']; } ?>" />
		<input type="hidden" name="option" value="com_referral" />
		<input type="hidden" name="task" value="referralform.save" />
		<?php echo HTMLHelper::_('form.token'); ?>
        <button type="button" onclick="regNowOnclick()" class="btn-primary">ĐĂNG KÝ NGAY</button>
		</form>
    </div>
    <div class="detail_pack">
        <div class="row_des">
            <img src="<?php echo Uri::base() ?>images/ic1.png">
            <span style="color: #ff0500;">Miễn phí đăng ký số tài khoản trùng số điện thoại</span>
        </div>
        <div class="row_des">
            <img src="<?php echo Uri::base() ?>images/ic2.png">
            <span>Nhận ngay 30.000 VND khi đăng ký
                thành công</span>
        </div>
        <div class="row_des">
            <img src="<?php echo Uri::base() ?>images/ic3.png">
            <span>Cơ hội <font style="color: #ff0500; font-family: AvenirNextBold;">nhận thêm 10.000.000 VND++</font>
                khi giới thiệu bạn bè, người thân sử dụng App MBBank
            </span>
        </div>
        <div class="row_des" style="margin-bottom: 0;">
            <img src="<?php echo Uri::base() ?>images/ic4.png">
            <span>App MBBank - miễn phí chuyển khoản liên ngân hàng trọn đời và lựa chọn tài khoản Số đẹp miễn
                phí</span>
        </div>
    </div>
    <div class="block1">
        <!-- <button onclick="regNowOnclick()">ĐĂNG KÝ NGAY</button> -->
        <div class="title">
            Nhận ngay khoản thưởng
        </div>
        <div class="panel-wrap">
            <div class="panel">
                <div style="display: flex;flex-direction: row;align-items: center;">
                    <img src="<?php echo Uri::base() ?>images/referralNhNTiN20.png">
                    <div class="money">+ 30.000 VND</div>
                </div>
                <p>
                    <font style="font-family: AvenirNextBold;">Nhận 30,000 VND</font> khi bạn click vào Link được giới thiệu, tải App từ chợ ứng dụng và đăng ký thành công.
                </p>

            </div>
            <div class="panel">
                <div style="display: flex;flex-direction: row;align-items: center;">
                    <img src="<?php echo Uri::base() ?>images/referralNhNTiN20.png">
                    <div class="money">Nhận thêm tiền thưởng</div>
                </div>
                <p>
                    <font style="font-family: AvenirNextBold;">Nhận thêm XX triệu đồng</font> khi tiếp tục giới thiệu bạn bè, người thân tải và đăng ký thành công App MBBank.
                    <br>
                    Truy cập Mục MB++/
                    <font style="font-family: AvenirNextBold;">Giới thiệu nhiều - Nhận tiền triệu</font> để biết thêm thông tin và lấy đường Link giới thiệu.
                </p>
            </div>

        </div>
        <div class="note">
            <div class="title_wrap">
                <img src="<?php echo Uri::base() ?>images/referralNote.png">
                <span>Lưu ý</span>
            </div>
            <ul>
                <li>
                    Bạn của bạn (người giới thiệu) cũng sẽ nhận được tiền thưởng khi bạn đăng ký thành công.
                </li>
                <li style="margin-bottom: 0;">
                    Chỉ áp dụng cho những khách hàng mới chưa đăng ký App MBBank và nhận được Link giới thiệu.
                </li>
            </ul>
        </div>
        <div class="note-detail">
            Xem thêm chi tiết thể lệ <a href="https://mobile.mbbank.com.vn/referral/term.html" target="_system">TẠI ĐÂY</a>
        </div>
    </div>
    <div class="block2">
        <!-- <img class="logo" src="./res/img/MB_3D_Background_App.png"></img> -->
        <div class="title">
            Hãy trải nghiệm những tiện ích vượt trội của App MBBank
        </div>

        <div class="panel-wrap">
            <div class="panel2">
                <img src="<?php echo Uri::base() ?>images/icon2.png">
                <div class="des">Miễn phí chọn số tài khoản trùng số điện thoại, số tài khoản tứ quý, thần tài, năm sinh</div>
                <img class="hot" src="<?php echo Uri::base() ?>images/hot.png">
            </div>
            <div class="panel2">
                <img src="<?php echo Uri::base() ?>images/icon2.png">
                <div class="des">Hoàn tiền tới 10% khi mua sắm Shopee, Lazada, Tiki, Sendo...</div>
            </div>
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon3.png">
                <div class="des">Miễn phí chuyển khoản trọn đời</div>
            </div>
            <!-- </div>
        <div class="container"> -->
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon4.png">
                <div class="des">Mở tài khoản chứng khoán MBS, giao dịch ngay</div>
            </div>
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon5.png">
                <div class="des">Rút tiền mặt không cần thẻ tại ATM MB trên toàn quốc</div>
            </div>
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon6.png">
                <div class="des">Thanh toán miễn phí điện, nước, internet, truyền hình,...</div>
            </div>
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon7.png">
                <div class="des">Vay nhanh trên App MBBank với thủ tục nhanh chóng</div>
            </div>
            <div class="panel">
                <img src="<?php echo Uri::base() ?>images/icon5.png">
                <div class="des">Tặng 1000++ điểm thưởng đổi voucher Viettel, Vietlott, Grab</div>
            </div>
        </div>
        <div class="family_container">
            <div class="banner">
                <!-- <img class="img1" src=""/> -->
            </div>
            <div class="des">
                <span>Tặng 1 triệu cho mỗi con khi tham gia gói Family banking</span>
                <a href="https://mbfamily.vn/?utm_source=referree&amp;utm_medium=Conversion&amp;utm_campaign=Longtv01"><button>Tìm
                        hiểu thêm</button></a>
            </div>
        </div>

    </div>
    <div class="address">
        <div class="text1">
            <img class="logo1" src="<?php echo Uri::base() ?>images/logo-default.png">
            <p>Địa chỉ văn phòng<br>
                Toà nhà MB Tower, 18 Lê Văn Lương, Trung Hoà, Cầu Giấy, Hà Nội<br>
                Liên hệ online: 1900 54 54 26<br>
                Hoặc email:
                <font style="color: #141ed1;">mb247@mbbank.com.vn</font>
            </p>
        </div>
    </div>
    <div class="loading" id="loading">
        <img src="<?php echo Uri::base() ?>images/starttt.gif">
    </div>
</div>
<script type="text/javascript">
function onlyDigits(s) {
  for (let i = s.length - 1; i >= 0; i--) {
    const d = s.charCodeAt(i);
    if (d < 48 || d > 57) return false
  }
  return true
}
function regNowOnclick(){
	var phone = jQuery('#jform_phone').val();
	var name = jQuery('#jform_name').val();
	if(name == ''){
		alert("Vui lòng nhập Tên!");
		return false;
	}
	if(phone == '' || phone.length != 10){
		alert("Vui lòng nhập Số điện thoại 10 số!");
		return false;
	}else{
		if(onlyDigits(phone)){
      		jQuery('#form-referral').submit();
			return true;
		}else{
			alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			return false;
		}
	}

}
</script>
