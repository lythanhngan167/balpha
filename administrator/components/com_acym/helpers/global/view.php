<?php
defined('_JEXEC') or die('Restricted access');
?><?php

function acym_getView($ctrl, $view, $forceBackend = false)
{
    $override = acym_getPageOverride($ctrl, $view, $forceBackend);

    if (!empty($override) && file_exists($override)) {
        return $override;
    } else {
        $viewsFolder = ($forceBackend || acym_isAdmin()) ? ACYM_VIEW : ACYM_VIEW_FRONT;

        return $viewsFolder.$ctrl.DS.'tmpl'.DS.$view.'.php';
    }
}

function acym_loadAssets($scope, $ctrl, $task)
{
    acym_loadCmsScripts();

    acym_addScript(
        true,
        'var AJAX_URL_UPDATEME = "'.ACYM_UPDATEMEURL.'";
        var MEDIA_URL_ACYM = "'.ACYM_MEDIA_URL.'";
        var CMS_ACYM = "'.ACYM_CMS.'";
        var FOUNDATION_FOR_EMAIL = "'.ACYM_CSS.'libraries/foundation_email.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.'libraries'.DS.'foundation_email.min.css').'";
        var ACYM_FIXES_FOR_EMAIL = "'.str_replace('"', '\"', acym_getEmailCssFixes()).'";
        var ACYM_REGEX_EMAIL = /^'.acym_getEmailRegex(true).'$/i;
        var ACYM_JS_TXT = '.acym_getJSMessages().';'
    );

    acym_addScript(false, ACYM_JS.'libraries/foundation.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'foundation.min.js'));

    if ('back' === $scope) {
        acym_addScript(false, ACYM_JS.'libraries/select2.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'select2.min.js'));
    }

    acym_addScript(false, ACYM_JS.'helpers.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'helpers.min.js'), 'text/javascript', true);
    if ('back' == $scope) acym_addScript(false, ACYM_JS.$scope.'_helpers.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'back_helpers.min.js'));
    acym_addScript(false, ACYM_JS.'global.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'global.min.js'));
    acym_addScript(false, ACYM_JS.$scope.'_global.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.$scope.'_global.min.js'));

    if (file_exists(ACYM_MEDIA.'js'.DS.$scope.DS.$ctrl.'.min.js')) {
        acym_addScript(false, ACYM_JS.$scope.'/'.$ctrl.'.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.$scope.DS.$ctrl.'.min.js'), 'text/javascript', true);
    }

    acym_addStyle(false, ACYM_CSS.'global.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.'global.min.css'));

    if (!acym_isExcludedFrontView($ctrl, $task)) {
        acym_addStyle(false, ACYM_CSS.$scope.'_global.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.$scope.'_global.min.css'));
    }

    if (file_exists(ACYM_MEDIA.'css'.DS.$scope.DS.$ctrl.'.min.css')) {
        acym_addStyle(false, ACYM_CSS.$scope.'/'.$ctrl.'.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.$scope.DS.$ctrl.'.min.css'));
    }
}

function acym_getJSMessages()
{
    $msg = "{";
    $msg .= '"email": "'.acym_translation('ACYM_VALID_EMAIL', true).'",';
    $msg .= '"number": "'.acym_translation('ACYM_VALID_NUMBER', true).'",';
    $msg .= '"requiredMsg": "'.acym_translation('ACYM_REQUIRED_FIELD', true).'",';
    $msg .= '"defaultMsg": "'.acym_translation('ACYM_DEFAULT_VALIDATION_ERROR', true).'"';

    $keysToLoad = [
        'ACYM_ARE_YOU_SURE',
        'ACYM_INSERT_IMG_BAD_NAME',
        'ACYM_NON_VALID_URL',
        'ACYM_DYNAMIC_TEXT',
        'ACYM_ARE_YOU_SURE_DELETE',
        'ACYM_ARE_YOU_SURE_ACTIVE',
        'ACYM_ARE_YOU_SURE_INACTIVE',
        'ACYM_SEARCH',
        'ACYM_CANCEL',
        'ACYM_CONFIRM',
        'ACYM_TEMPLATE_CHANGED_CLICK_ON_SAVE',
        'ACYM_SURE_SEND_TRANSALTION',
        'ACYM_TESTS_SPAM_SENT',
        'ACYM_CONFIRMATION_CANCEL_CAMPAIGN_QUEUE',
        'ACYM_EXPORT_SELECT_LIST',
        'ACYM_YES',
        'ACYM_NO',
        'ACYM_NEXT',
        'ACYM_BACK',
        'ACYM_SKIP',
        'ACYM_INTRO_ADD_DTEXT',
        'ACYM_INTRO_TEMPLATE',
        'ACYM_INTRO_DRAG_BLOCKS',
        'ACYM_INTRO_DRAG_CONTENT',
        'ACYM_INTRO_SETTINGS',
        'ACYM_INTRO_CUSTOMIZE_FONT',
        'ACYM_INTRO_IMPORT_CSS',
        'ACYM_INTRO_SAFE_CHECK',
        'ACYM_INTRO_MAIL_SETTINGS',
        'ACYM_INTRO_ADVANCED',
        'ACYM_INTRO_DKIM',
        'ACYM_INTRO_CRON',
        'ACYM_INTRO_SUBSCRIPTION',
        'ACYM_INTRO_CHECK_DATABASE',
        'ACYM_SEND_TEST_SUCCESS',
        'ACYM_SEND_TEST_ERROR',
        'ACYM_COPY_DEFAULT_TRANSLATIONS_CONFIRM',
        'ACYM_BECARFUL_BACKGROUND_IMG',
        'ACYM_CANT_DELETE_AND_SAVE',
        'ACYM_AND',
        'ACYM_OR',
        'ACYM_ERROR',
        'ACYM_EDIT_MAIL',
        'ACYM_CREATE_MAIL',
        'ACYM_NO_RAND_FOR_MULTQUEUE',
        'ACYM_DELETE_MY_DATA_CONFIRM',
        'ACYM_CHOOSE_COLUMN',
        'ACYM_AUTOSAVE_USE',
        'ACYM_SELECT_NEW_ICON',
        'ACYM_SESSION_IS_GOING_TO_END',
        'ACYM_CLICKS_OUT_OF',
        'ACYM_OF_CLICKS',
        'ACYM_ARE_SURE_DUPLICATE_TEMPLATE',
        'ACYM_NOT_FOUND',
        'ACYM_EMAIL',
        'ACYM_CAMPAIGN_NAME',
        'ACYM_EMAIL_SUBJECT',
        'ACYM_TEMPLATE_NAME',
        'ACYM_ERROR_SAVING',
        'ACYM_LOADING_ERROR',
        'ACYM_AT_LEAST_ONE_USER',
        'ACYM_ERROR_SAVING',
        'ACYM_NO_DCONTENT_TEXT',
        'ACYM_PREVIEW',
        'ACYM_PREVIEW_DESC',
        'ACYM_CONTENT_TYPE',
        'ACYM_TEMPLATE_EMPTY',
        'ACYM_DRAG_BLOCK_AND_DROP_HERE',
        'ACYM_WELL_DONE_DROP_HERE',
        'ACYM_REPLACE_CONFIRM',
        'ACYM_STATS_START_DATE_LOWER',
        'ACYM_ARE_YOU_SURE_DELETE_ADD_ON',
        'ACYM_COULD_NOT_SUBMIT_FORM_CONTACT_ADMIN_WEBSITE',
        'ACYM_TEMPLATE_CREATED',
        'ACYM_UNSUBSCRIBE',
        'ACYM_BUTTON',
        'ACYM_SPACE_BETWEEN_BLOCK',
        'ACYM_X1_AND_X2',
        'ACYM_COULD_NOT_SAVE_THUMBNAIL_ERROR_X',
        'ACYM_REQUEST_FAILED_TIMEOUT',
        'ACYM_INSERT_DYNAMIC_TEXT',
        'ACYM_PLEASE_SET_A_LICENSE_KEY',
        'ACYM_COULD_NOT_UPLOAD_CSV_FILE',
        'ACYM_RESET_VIEW_CONFIRM',
    ];

    foreach ($keysToLoad as $oneKey) {
        $msg .= ',"'.$oneKey.'": "'.acym_translation($oneKey, true).'"';
    }

    $msg .= "}";

    return $msg;
}

function acym_isExcludedFrontView($ctrl, $task)
{
    if ('archive' === $ctrl && in_array($task, ['view', 'listing'])) return true;
    if ('frontusers' === $ctrl && 'profile' === $task) return true;

    return false;
}

function acym_listingActions($actions, $deleteMessage = '', $ctrl = '')
{
    $defaultAction = new stdClass();
    $defaultAction->value = 0;
    $defaultAction->text = acym_translation('ACYM_CHOOSE_ACTION');
    $defaultAction->disable = true;

    array_unshift($actions, $defaultAction);

    $completeMessage = '<input id="acym__listing__action__delete-message" value="'.(empty($deleteMessage) ? '' : acym_escape($deleteMessage)).'" type="hidden">';

    $attrCtrl = '';
    if (!empty($ctrl)) {
        $attrCtrl = 'data-ctrl="'.$ctrl.'"';
        $completeMessage .= ' <input type="hidden" name="return_listing">';
    }

    return acym_select($actions, '', null, 'class="medium-shrink cell margin-right-1" '.$attrCtrl, 'value', 'text', 'listing_actions').$completeMessage;
}

function acym_backToListing($listingName = null)
{
    if (empty($listingName)) $listingName = acym_getVar('cmd', 'ctrl');

    $returnLink = '<p class="acym__back_to_listing">';
    $returnLink .= '<a href="'.acym_completeLink($listingName).'" class="acym_vcenter">';
    $returnLink .= '<i class="acymicon-chevron-left"></i> '.acym_translation('ACYM_BACK_TO_LISTING');
    $returnLink .= '</a>';
    $returnLink .= '</p>';

    return $returnLink;
}

