<?php
defined('_JEXEC') or die('Restricted access');
?><?php

class CampaignsController extends acymController
{
    var $stepContainerClass = '';

    public function __construct()
    {
        $this->defaulttask = 'campaigns';
        parent::__construct();
        $this->breadcrumb[acym_translation('ACYM_EMAILS')] = acym_completeLink('campaigns');
        $this->loadScripts = [
            'edit' => ['colorpicker', 'datepicker', 'thumbnail', 'foundation-email', 'introjs', 'parse-css', 'vue-applications' => ['code_editor', 'entity_select'], 'vue-prism-editor', 'editor-wysid'],
            'save' => ['colorpicker', 'datepicker', 'thumbnail', 'foundation-email', 'introjs', 'parse-css', 'vue-applications' => ['code_editor', 'entity_select'], 'vue-prism-editor', 'editor-wysid'],
            'duplicate' => ['colorpicker', 'datepicker', 'thumbnail', 'foundation-email', 'parse-css', 'editor-wysid', 'vue-applications' => ['code_editor', 'entity_select'], 'vue-prism-editor',],
            'all' => ['introjs'],
        ];
        acym_setVar('edition', '1');
        if (acym_isAdmin()) $this->stepContainerClass = 'xxlarge-9';
        acym_header('X-XSS-Protection:0');
        $this->storeRedirectListing();
    }

    public function listing()
    {
        $this->storeRedirectListing(true);
    }

    private function storeRedirectListing($fromListing = false)
    {
        $isFrontJoomla = !acym_isAdmin() && ACYM_CMS == 'joomla';
        $variableName = $isFrontJoomla ? 'ctrl_stored_front' : 'ctrl_stored';
        acym_session();
        $taskToStore = [
            '',
            'campaigns',
            'campaigns_auto',
            'welcome',
            'unsubscribe',
        ];
        $currentTask = acym_getVar('string', 'task', '');
        if (!in_array($currentTask, $taskToStore) && !$fromListing) return;

        if ((empty($currentTask) || !in_array($currentTask, $taskToStore)) && !empty($_SESSION[$variableName])) {
            $link = $isFrontJoomla ? acym_frontendLink('frontcampaigns&task='.$_SESSION[$variableName]) : acym_completeLink('campaigns&task='.$_SESSION[$variableName], false, true);
            acym_redirect($link);
        } else {
            if (empty($currentTask) || !in_array($currentTask, $taskToStore)) $currentTask = 'campaigns';
            $_SESSION[$variableName] = $currentTask;
        }
        if ($fromListing && method_exists($this, $currentTask)) $this->$currentTask();
    }

    public function setTaskListing($task)
    {
        if (!in_array($task, ['campaigns', 'campaigns_auto', 'welcome', 'unsubscribe',])) return false;

        $isFrontJoomla = !acym_isAdmin() && ACYM_CMS == 'joomla';
        $variableName = $isFrontJoomla ? 'ctrl_stored_front' : 'ctrl_stored';
        acym_session();
        $_SESSION[$variableName] = $task;

        return true;
    }

    public function campaigns()
    {
        acym_setVar('layout', 'campaigns');

        $data = [
            'campaign_type' => 'campaigns',
            'element_to_display' => lcfirst(acym_translation('ACYM_CAMPAIGNS')),
        ];
        $this->prepareAllCampaignsListing($data);

        parent::display($data);
    }

    public function campaigns_auto()
    {
        if (!acym_level(2)) {
            $this->campaigns();
        }
    }

    public function welcome()
    {
        acym_setVar('layout', 'welcome');
        $data = [
            'email_type' => 'welcome',
            'element_to_display' => lcfirst(acym_translation('ACYM_WELCOME_EMAILS')),
        ];

        $this->prepareWelcomeUnsubListing($data);

        parent::display($data);
    }

    public function unsubscribe()
    {
        acym_setVar('layout', 'unsubscribe');
        $data = [
            'email_type' => 'unsubscribe',
            'element_to_display' => lcfirst(acym_translation('ACYM_UNSUBSCRIBE_EMAILS')),
        ];

        $this->prepareWelcomeUnsubListing($data);

        parent::display($data);
    }

    private function prepareWelcomeUnsubListing(&$data)
    {
        $this->getAllParamsRequest($data);
        $this->prepareEmailsListing($data, $data['email_type'], 'mail');
    }

    private function getAllParamsRequest(&$data)
    {
        $data['search'] = acym_getVar('string', 'campaigns_search', '');
        $data['ordering'] = acym_getVar('string', 'campaigns_ordering', 'id');
        $data['orderingSortOrder'] = acym_getVar('string', 'campaigns_ordering_sort_order', 'desc');
        $data['status'] = acym_getVar('string', 'campaigns_status', '');
        $data['tag'] = acym_getVar('string', 'campaigns_tag', '');
        $data['allTags'] = acym_get('class.tag')->getAllTagsByType('mail');
        $data['pagination'] = acym_get('helper.pagination');
    }

    private function prepareAllCampaignsListing(&$data)
    {
        $this->getAllParamsRequest($data);
        $this->prepareEmailsListing($data, $data['campaign_type']);
        if ($data['campaign_type'] == 'campaigns_auto') {
            $this->getAutoCampaignsFrequency($data);
            $this->getIsPendingGenerated($data);
        }
    }

    private function getAutoCampaignsFrequency(&$data)
    {
        foreach ($data['allCampaigns'] as $key => $campaign) {
            $textToDisplay = new stdClass();
            $textToDisplay->triggers = $campaign->sending_params;
            acym_trigger('onAcymDeclareSummary_triggers', [&$textToDisplay], 'plgAcymTime');
            $textToDisplay = array_values($textToDisplay->triggers);
            $data['allCampaigns'][$key]->sending_params = empty($textToDisplay[0]) ? acym_translation('ACYM_ERROR_WHILE_RECOVERING_TRIGGERS') : $textToDisplay[0];
        }
    }

    private function prepareEmailsListing(&$data, $campaignType = '', $class = '')
    {
        $campaignsPerPage = $data['pagination']->getListLimit();
        $page = acym_getVar('int', 'campaigns_pagination_page', 1);
        $status = $data['status'];

        $campaignClass = acym_get('class.campaign');
        $matchingCampaigns = $this->getMatchingElementsFromData(
            [
                'element_tab' => $campaignType,
                'ordering' => $data['ordering'],
                'search' => $data['search'],
                'elementsPerPage' => $campaignsPerPage,
                'offset' => ($page - 1) * $campaignsPerPage,
                'tag' => $data['tag'],
                'ordering_sort_order' => $data['orderingSortOrder'],
                'status' => $data['status'],
                'creator_id' => acym_isAdmin() ? 0 : acym_currentUserId(),
            ],
            $status,
            $page,
            empty($class) ? '' : $class
        );

        if (empty($class)) {
            foreach ($matchingCampaigns['elements'] as $key => $campaign) {
                $matchingCampaigns['elements'][$key]->scheduled = $campaignClass::SENDING_TYPE_SCHEDULED == $campaign->sending_type;
            }
        }

        if (empty($class)) {
            $data['allStatusFilter'] = $this->getCountStatusFilter($matchingCampaigns['total'], $data['campaign_type']);
            $totalElement = empty($status) ? $data['allStatusFilter']->all : $data['allStatusFilter']->$status;
            $data['statusAuto'] = $campaignClass::SENDING_TYPE_AUTO;
        } else {
            $totalElement = $matchingCampaigns['total'];
        }

        $data['pagination']->setStatus($totalElement, $page, $campaignsPerPage);
        $data['allCampaigns'] = $matchingCampaigns['elements'];
    }

    private function getIsPendingGenerated(&$data)
    {
        $campaignClass = acym_get('class.campaign');
        $campaingsGenerated = $campaignClass->getAllCampaignsGenerated();
        $data['generatedPending'] = !empty($campaingsGenerated);
    }

    public function newEmail()
    {
        acym_setVar('layout', 'new_email');

        $listClass = acym_get('class.list');
        if (acym_isAdmin()) {
            $returnUrl = urlencode(base64_encode(acym_completeLink('campaigns')));
            $data = [
                'lists' => $listClass->getAllForSelect(),
                'campaign_link' => acym_completeLink('campaigns&task=edit&step=chooseTemplate&campaign_type=now'),
                'campaign_auto_link' => acym_completeLink('campaigns&task=edit&step=chooseTemplate&campaign_type=auto'),
                'campaign_scheduled_link' => acym_completeLink('campaigns&task=edit&step=chooseTemplate&campaign_type=scheduled'),
                'welcome_email_link' => acym_completeLink('mails&task=edit&type=welcome&list_id={listid}&type_editor=acyEditor&return='.$returnUrl),
                'unsubscribe_email_link' => acym_completeLink('mails&task=edit&type=unsubscribe&list_id={listid}&type_editor=acyEditor&return='.$returnUrl),
            ];
        } else {
            $returnUrl = urlencode(base64_encode(acym_frontendLink('frontcampaigns')));
            $data = [
                'lists' => $listClass->getAllForSelect(),
                'campaign_link' => acym_frontendLink('frontcampaigns&task=edit&step=chooseTemplate&campaign_type=now'),
                'campaign_auto_link' => acym_frontendLink('frontcampaigns&task=edit&step=chooseTemplate&campaign_type=auto'),
                'campaign_scheduled_link' => acym_frontendLink('frontcampaigns&task=edit&step=chooseTemplate&campaign_type=scheduled'),
                'welcome_email_link' => acym_frontendLink('frontmails&task=edit&type=welcome&list_id={listid}&type_editor=acyEditor&return='.$returnUrl),
                'unsubscribe_email_link' => acym_frontendLink('frontmails&task=edit&type=unsubscribe&list_id={listid}&type_editor=acyEditor&return='.$returnUrl),
            ];
        }

        parent::display($data);
    }

    public function chooseTemplate()
    {
        acym_setVar('layout', 'choose_email');
        acym_setVar('step', 'chooseTemplate');
        $pagination = acym_get('helper.pagination');

        $campaignId = acym_getVar('int', 'id', 0);
        $campaignClass = acym_get('class.campaign');
        $searchFilter = acym_getVar('string', 'mailchoose_search', '');
        $tagFilter = acym_getVar('string', 'mailchoose_tag', '');
        $ordering = acym_getVar('string', 'mailchoose_ordering', 'creation_date');
        $orderingSortOrder = acym_getVar('string', 'mailchoose_ordering_sort_order', 'DESC');
        $campaign = $campaignClass->getOneByIdWithMail($campaignId);
        $campaignType = acym_getVar('string', 'campaign_type', 'now');

        $this->setTaskListing($campaignType == 'auto' ? 'campaigns_auto' : 'campaigns');

        if (!empty($campaign)) {
            $this->breadcrumb[acym_escape($campaign->name)] = '';
        } else {
            $this->breadcrumb[acym_translation('ACYM_NEW_CAMPAIGN')] = '';
        }

        $mailsPerPage = $pagination->getListLimit();
        $page = acym_getVar('int', 'mailchoose_pagination_page', 1);

        $mailClass = acym_get('class.mail');
        $matchingMails = $mailClass->getMatchingElements(
            [
                'ordering' => $ordering,
                'ordering_sort_order' => $orderingSortOrder,
                'search' => $searchFilter,
                'elementsPerPage' => $mailsPerPage,
                'offset' => ($page - 1) * $mailsPerPage,
                'tag' => $tagFilter,
                'onlyStandard' => true,
                'creator_id' => $this->setFrontEndParamsForTemplateChoose(),
            ]
        );

        $pagination->setStatus($matchingMails['total'], $page, $mailsPerPage);

        $data = [
            'allMails' => $matchingMails['elements'],
            'allTags' => acym_get('class.tag')->getAllTagsByType('mail'),
            'pagination' => $pagination,
            'search' => $searchFilter,
            'tag' => $tagFilter,
            'ordering' => $ordering,
            'campaignID' => $campaignId,
            'campaign_type' => $campaignType,
        ];


        parent::display($data);
    }

    protected function setFrontEndParamsForTemplateChoose()
    {
        return '';
    }

    public function editEmail()
    {
        acym_setVar('layout', 'edit_email');
        acym_setVar('numberattachment', '0');
        acym_setVar('step', 'editEmail');

        $editor = acym_get('helper.editor');
        $mailClass = acym_get('class.mail');

        $mailId = acym_getVar('int', 'from', 0);
        $campaignId = acym_getVar('int', 'id', 0);
        $typeEditor = acym_getVar('string', 'type_editor', '');
        $campaignType = acym_getVar('string', 'campaign_type', 'now');

        $editLink = 'campaigns&task=edit&step=editEmail';

        $checkAutosave = empty($mailId);

        if (empty($campaignId)) {
            $campaign = new stdClass();
            $campaign->id = 0;
            $campaign->name = '';
            $campaign->tags = [];
            $campaign->subject = '';
            $campaign->preheader = '';
            $campaign->body = '';
            $campaign->settings = null;
            $campaign->links_language = '';

            $editLink .= '&from='.$mailId;
        } else {
            $campaignClass = acym_get('class.campaign');
            $campaign = $campaignClass->getOneByIdWithMail($campaignId);
            if (empty($mailId)) {
                $mailId = $campaign->mail_id;
            }
            $editLink .= '&id='.$campaignId;
        }

        if ($mailId == -1 || (empty($campaignId) && empty($mailId))) {
            $campaign->name = '';
            $campaign->tags = [];
            $campaign->subject = '';
            $campaign->preheader = '';
            $campaign->body = '';
            $campaign->settings = null;
            $campaign->attachments = [];
            $campaign->stylesheet = '';
            $campaign->headers = '';
            $typeEditor = 'acyEditor';
        } elseif (!empty($mailId)) {
            $mail = $mailClass->getOneById($mailId);
            $campaign->tags = $mail->tags;
            $campaign->subject = $mail->subject;
            $campaign->preheader = $mail->preheader;
            $campaign->body = $mail->body;
            $campaign->settings = $mail->settings;
            $campaign->stylesheet = $mail->stylesheet;
            $campaign->headers = $mail->headers;
            $campaign->attachments = empty($mail->attachments) ? [] : json_decode($mail->attachments);
            $campaign->links_language = $mail->links_language;

            if ($checkAutosave) {
                $campaign->autosave = $mail->autosave;
            }
        }

        $pluginHelper = acym_get('helper.plugin');
        $pluginHelper->cleanHtml($campaign->body);
        $editor->content = $campaign->body;
        $editor->autoSave = !empty($campaign->autosave) ? $campaign->autosave : '';
        if (!empty($campaign->settings)) {
            $editor->settings = $campaign->settings;
        }

        if (acym_bytes(ini_get('upload_max_filesize')) > acym_bytes(ini_get('post_max_size'))) {
            $maxupload = ini_get('post_max_size');
        } else {
            $maxupload = ini_get('upload_max_filesize');
        }

        if (!empty($campaign->stylesheet)) {
            $editor->stylesheet = $campaign->stylesheet;
        }

        if (empty($typeEditor) && strpos($editor->content, 'acym__wysid__template') !== false) {
            $typeEditor = 'acyEditor';
        }

        $editor->editor = $typeEditor;
        if ($editor->editor != 'acyEditor' || empty($editor->editor)) {
            if (!isset($campaign->stylesheet)) $campaign->stylesheet = '';
            $needDisplayStylesheet = '<input type="hidden" name="editor_stylesheet" value="'.acym_escape($campaign->stylesheet).'">';
        } else {
            $needDisplayStylesheet = '';
        }

        $editor->mailId = empty($mailId) ? 0 : $mailId;

        $editLink .= '&type_editor='.$typeEditor;
        $this->breadcrumb[acym_escape(empty($campaign->name) ? acym_translation('ACYM_NEW_CAMPAIGN') : $campaign->name)] = acym_completeLink($editLink);

        $data = [
            'campaignID' => $campaign->id,
            'mailInformation' => $campaign,
            'allTags' => acym_get('class.tag')->getAllTagsByType('mail'),
            'editor' => $editor,
            'maxupload' => $maxupload,
            'needDisplayStylesheet' => $needDisplayStylesheet,
            'social_icons' => $this->config->get('social_icons', '{}'),
            'containerClass' => $this->stepContainerClass,
            'campaign_type' => $campaignType,
        ];

        parent::display($data);
    }

    public function recipients()
    {
        acym_setVar('layout', 'recipients');
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');
        acym_setVar('step', 'recipients');

        if (!empty($campaignId)) {
            $currentCampaign = $campaignClass->getOneByIdWithMail($campaignId);
            $this->breadcrumb[acym_escape($currentCampaign->name)] = acym_completeLink('campaigns&task=edit&step=recipients&id='.$campaignId);
        } else {
            $currentCampaign = new stdClass();
            $this->breadcrumb[acym_translation('ACYM_NEW_CAMPAIGN')] = acym_completeLink('campaigns&task=edit&step=recipients');
        }

        $campaign = [
            'campaignInformation' => $campaignId,
            'currentCampaign' => $currentCampaign,
            'containerClass' => $this->stepContainerClass,
        ];

        if (!empty($currentCampaign->mail_id)) {
            $campaignLists = $mailClass->getAllListsByMailId($currentCampaign->mail_id);
            $campaign['campaignListsId'] = array_keys($campaignLists);
            acym_arrayToInteger($campaign['campaignListsId']);
            $campaign['campaignListsSelected'] = json_encode($campaign['campaignListsId']);
        }

        parent::display($campaign);
    }

    public function sendSettings()
    {
        acym_setVar('layout', 'send_settings');
        acym_setVar('step', 'sendSettings');
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');
        $campaignInformation = empty($campaignId) ? null : $campaignClass->getOneById($campaignId);

        if (is_null($campaignInformation)) {
            acym_enqueueMessage(acym_translation('ACYM_CANT_GET_CAMPAIGN_INFORMATION'), 'error');
            $this->listing();

            return;
        }

        $from = acym_getVar('string', 'from');

        $campaignClass = acym_get('class.campaign');
        $currentCampaign = $campaignClass->getOneByIdWithMail($campaignId);
        $this->breadcrumb[acym_escape($currentCampaign->name)] = acym_completeLink('campaigns&task=edit&step=sendSettings&id='.$campaignId);

        if (!empty($currentCampaign->sent) && empty($currentCampaign->active)) {
            $currentCampaign->sending_date = '';
        }

        $campaign = [];

        $campaign['currentCampaign'] = $currentCampaign;
        $campaign['from'] = $from;
        $campaign['suggestedDate'] = acym_date('1534771620', 'j M Y H:i');
        $campaign['senderInformations'] = new stdClass();
        $campaign['config_values'] = new stdClass();
        $campaign['currentCampaign']->send_now = $currentCampaign->sending_type == $campaignClass::SENDING_TYPE_NOW;
        $campaign['currentCampaign']->send_scheduled = $currentCampaign->sending_type == $campaignClass::SENDING_TYPE_SCHEDULED;
        $campaign['currentCampaign']->send_auto = $currentCampaign->sending_type == $campaignClass::SENDING_TYPE_AUTO;
        $campaign['campaignClass'] = $campaignClass;

        $campaign['senderInformations']->from_name = empty($currentCampaign->from_name) ? '' : $currentCampaign->from_name;
        $campaign['senderInformations']->from_email = empty($currentCampaign->from_email) ? '' : $currentCampaign->from_email;
        $campaign['senderInformations']->reply_to_name = empty($currentCampaign->reply_to_name) ? '' : $currentCampaign->reply_to_name;
        $campaign['senderInformations']->reply_to_email = empty($currentCampaign->reply_to_email) ? '' : $currentCampaign->reply_to_email;

        $campaign['config_values']->from_name = $this->config->get('from_name', '');
        $campaign['config_values']->from_email = $this->config->get('from_email', '');
        $campaign['config_values']->reply_to_name = $this->config->get('replyto_name', '');
        $campaign['config_values']->reply_to_email = $this->config->get('replyto_email', '');

        $triggers = [];

        acym_trigger('onAcymDeclareTriggers', [&$triggers, &$currentCampaign->sending_params], 'plgAcymTime');
        $triggers = $triggers['classic'];

        $campaign['triggers_select'] = [];
        $campaign['triggers_display'] = [];

        foreach ($triggers as $key => $trigger) {
            $campaign['triggers_select'][$key] = $trigger->name;
            $campaign['triggers_display'][$key] = $trigger->option;
        }

        $campaign['containerClass'] = $this->stepContainerClass;
        $campaign['langChoice'] = acym_languageOption($campaign['currentCampaign']->links_language, 'senderInformation[links_language]');

        return parent::display($campaign);
    }

    public function saveEditEmail($ajax = false)
    {
        acym_checkToken();

        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');
        $formData = acym_getVar('array', 'mail', []);
        $allowedFields = acym_getColumns('mail');
        $campaignId = acym_getVar('int', 'id', 0);
        $campaignType = acym_getVar('string', 'campaign_type', 'now');

        $types = [
            'now' => $campaignClass::SENDING_TYPE_NOW,
            'auto' => $campaignClass::SENDING_TYPE_AUTO,
            'scheduled' => $campaignClass::SENDING_TYPE_SCHEDULED,
        ];

        if (empty($campaignId)) {
            $mail = new stdClass();
            $mail->creation_date = acym_date('now', 'Y-m-d H:i:s', false);
            $mail->type = 'standard';
            $mail->template = 0;
            $mail->library = 0;

            $campaign = new stdClass();
            $campaign->draft = 1;
            $campaign->active = 0;
            $campaign->sending_type = $types[$campaignType];
            $campaign->sent = 0;
            $campaign->sending_params = [];
        } else {
            $campaign = $campaignClass->getOneById($campaignId);
            $mail = $mailClass->getOneById($campaign->mail_id);
        }

        foreach ($formData as $name => $data) {
            if (!in_array($name, $allowedFields)) {
                continue;
            }
            $mail->{acym_secureDBColumn($name)} = $data;
        }

        if (empty($mail->name)) {
            $mail->name = $mail->subject;
        }

        $mail->body = acym_getVar('string', 'editor_content', '', 'REQUEST', ACYM_ALLOWRAW);
        $mail->settings = acym_getVar('string', 'editor_settings', '', 'REQUEST', ACYM_ALLOWRAW);
        $mail->stylesheet = acym_getVar('string', 'editor_stylesheet', '', 'REQUEST', ACYM_ALLOWRAW);
        $mail->headers = acym_getVar('string', 'editor_headers', '', 'REQUEST', ACYM_ALLOWRAW);
        $mail->drag_editor = strpos($mail->body, 'acym__wysid__template') === false ? 0 : 1;

        $mail->tags = acym_getVar('array', 'template_tags', []);

        $newAttachments = [];
        $attachments = acym_getVar('array', 'attachments', []);
        if (!empty($attachments)) {
            foreach ($attachments as $id => $filepath) {
                if (empty($filepath)) {
                    continue;
                }
                $attachment = new stdClass();
                $attachment->filename = $filepath;
                $attachment->size = filesize(ACYM_ROOT.$filepath);
                $extension = substr($attachment->filename, strrpos($attachment->filename, '.'));

                if (preg_match('#\.(php.?|.?htm.?|pl|py|jsp|asp|sh|cgi)#Ui', $attachment->filename)) {
                    acym_enqueueMessage(
                        acym_translation_sprintf(
                            'ACYM_ACCEPTED_TYPE',
                            substr($attachment->filename, strrpos($attachment->filename, '.') + 1),
                            $this->config->get('allowed_files')
                        ),
                        'notice'
                    );
                    continue;
                }
                $attachment->filename = str_replace(['.', ' '], '_', substr($attachment->filename, 0, strpos($attachment->filename, $extension))).$extension;

                $newAttachments[] = $attachment;
            }
            if (!empty($mail->attachments) && is_array(json_decode($mail->attachments))) {
                $newAttachments = array_merge(json_decode($mail->attachments), $newAttachments);
            }
            $mail->attachments = $newAttachments;
        }

        if (empty($mail->attachments)) {
            unset($mail->attachments);
        }
        if (!empty($mail->attachments) && !is_string($mail->attachments)) {
            $mail->attachments = json_encode($mail->attachments);
        }

        if ($mailID = $mailClass->save($mail)) {
            if (acym_getVar('string', 'nextstep', '') == 'listing') {
                acym_enqueueMessage(acym_translation('ACYM_SUCCESSFULLY_SAVED'), 'success');
            }
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
            if (!empty($mailClass->errors)) {
                acym_enqueueMessage($mailClass->errors, 'error');
            }

            if (!$ajax) {
                $this->listing();

                return;
            } else {
                return false;
            }
        }

        $campaign->mail_id = $mailID;
        $campaign->id = $campaignClass->save($campaign);

        if ($ajax) {
            return $campaign->id;
        }

        acym_setVar('id', $campaign->id);

        $this->edit();
    }

    public function saveRecipients()
    {
        $allLists = json_decode(acym_getVar('string', 'acym__entity_select__selected'));
        $allListsUnselected = json_decode(acym_getVar('string', 'acym__entity_select__unselected'));
        $campaignId = acym_getVar('int', 'id');

        $campaignClass = acym_get('class.campaign');
        $currentCampaign = $campaignClass->getOneByIdWithMail($campaignId);


        if ($currentCampaign->sent && !$currentCampaign->active) {
            $mailStatClass = acym_get('class.mailstat');
            $listClass = acym_get('class.list');
            $mailStat = $mailStatClass->getOneRowByMailId($currentCampaign->mail_id);
            $mailStat->total_subscribers = $listClass->getTotalSubCount($allLists);
            $mailStatClass->save($mailStat);
        } elseif (!empty($currentCampaign->mail_id)) {
            $campaignClass->manageListsToCampaign($allLists, $currentCampaign->mail_id, $allListsUnselected);
            if (acym_getVar('string', 'nextstep', '') == 'listing') {
                acym_enqueueMessage(acym_translation_sprintf('ACYM_LIST_IS_SAVED', $currentCampaign->name), 'success');
            }
        }

        $this->edit();
    }

    public function saveSendSettings()
    {
        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');
        $campaignId = acym_getVar('int', 'id');
        $senderInformation = acym_getVar('', 'senderInformation');
        $sendingDate = acym_getVar('string', 'sendingDate');

        $sendingType = acym_getVar('string', 'sending_type', $campaignClass::SENDING_TYPE_NOW);
        $sendingParams = [];

        $isScheduled = $campaignClass::SENDING_TYPE_SCHEDULED == $sendingType;

        $campaignInformation = $campaignClass->getOneById($campaignId);

        if (empty($campaignInformation)) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_DOESNT_EXISTS'), 'error');

            $this->listing();

            return;
        }

        if ($campaignClass::SENDING_TYPE_AUTO == $sendingType) {
            $triggerType = acym_getVar('string', 'acym_triggers', '');
            if (empty($triggerType)) {
                acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
                $this->listing();

                return false;
            }

            $needConfirmToSend = acym_getVar('int', 'need_confirm', 0);

            $sendingParams = acym_getVar('array', $triggerType, '');
            $sendingParams = [$triggerType => $sendingParams, 'need_confirm_to_send' => $needConfirmToSend];

            if (!empty($campaignInformation->sending_params['number_generated'])) $sendingParams['number_generated'] = $campaignInformation->sending_params['number_generated'];
        }

        $currentCampaign = $campaignClass->getOneById($campaignId);
        empty($currentCampaign->mail_id) ? : $currentMail = $mailClass->getOneById($currentCampaign->mail_id);

        $currentCampaign->sending_type = $sendingType;
        $currentCampaign->sending_params = $sendingParams;
        $currentCampaign->sending_date = null;

        if (empty($currentMail) || empty($senderInformation)) {
            $this->listing();

            return;
        }

        $currentMail->from_name = $senderInformation['from_name'];
        $currentMail->from_email = $senderInformation['from_email'];
        $currentMail->reply_to_name = $senderInformation['reply_to_name'];
        $currentMail->reply_to_email = $senderInformation['reply_to_email'];
        $currentMail->bcc = $senderInformation['bcc'];
        $currentMail->tracking = $senderInformation['tracking'];
        if (isset($senderInformation['links_language'])) $currentMail->links_language = $senderInformation['links_language'];

        $mailClass->save($currentMail);

        if (empty($currentCampaign->sent) && $isScheduled && !empty($sendingDate)) {
            $currentCampaign->sending_date = acym_date(acym_getTime($sendingDate), 'Y-m-d H:i:s', false);
            if ($currentCampaign->sending_date < acym_date('now', 'Y-m-d H:i:s', false)) acym_enqueueMessage(acym_translation('ACYM_BE_CAREFUL_SENDING_DATE_IN_PAST'), 'warning');
        }

        if ($campaignClass->save($currentCampaign)) {
            if (acym_getVar('string', 'nextstep', '') == 'listing') {
                acym_enqueueMessage(acym_translation('ACYM_SUCCESSFULLY_SAVED'), 'success');
            }
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
            if (!empty($campaignClass->errors)) {
                acym_enqueueMessage($campaignClass->errors, 'error');
            }

            $this->listing();

            return;
        }

        $this->edit();
    }

    public function duplicate()
    {
        $campaignsSelected = acym_getVar('int', 'elements_checked');

        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');
        $campaignId = 0;

        foreach ($campaignsSelected as $campaignSelected) {

            $campaign = $campaignClass->getOneById($campaignSelected);

            unset($campaign->id);
            unset($campaign->sending_date);
            $campaign->draft = 1;
            $campaign->sent = 0;

            $mail = $mailClass->getOneById($campaign->mail_id);
            $oldMailId = $mail->id;
            unset($mail->id);
            $mail->creation_date = acym_date('now', 'Y-m-d H:i:s', false);
            $mail->name .= '_copy';
            $idNewMail = $mailClass->save($mail);

            $campaign->mail_id = $idNewMail;
            $campaignId = $campaignClass->save($campaign);

            $allLists = $campaignClass->getListsForCampaign($oldMailId);

            $campaignClass->manageListsToCampaign($allLists, $idNewMail);
        }

        acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_DUPLICATED_SUCCESS'), 'success');

        if (count($campaignsSelected) == 1 && acym_getVar('string', 'step', '') == 'summary') {
            acym_setVar('id', $campaignId);
            $this->editEmail();
        } else {
            $this->listing();
        }

        return;
    }

    public function saveSummary()
    {
        $this->edit();
    }

    public function summary()
    {
        acym_setVar('step', 'summary');
        acym_setVar('layout', 'summary');
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');

        $campaign = empty($campaignId) ? null : $campaignClass->getOneByIdWithMail($campaignId);

        if (is_null($campaign)) {
            acym_enqueueMessage(acym_translation('ACYM_CANT_GET_CAMPAIGN_INFORMATION'), 'error');
            $this->listing();

            return;
        }

        $campaign->isAuto = $campaign->sending_type == $campaignClass::SENDING_TYPE_AUTO;

        $userClass = acym_get('class.user');
        $mailClass = acym_get('class.mail');

        $nbSubscribers = 0;
        $campaignLists = $mailClass->getAllListsWithCountSubscribersByMailIds([$campaign->mail_id]);

        if ($campaign->sent) {
            $mailstatClass = acym_get('class.mailstat');
            $nbSubscribers = $mailstatClass->getTotalSubscribersByMailId($campaign->mail_id);
        } else {
            if (!empty($campaignLists)) {
                $listsIds = [];
                foreach ($campaignLists as $oneList) {
                    $listsIds[] = $oneList->list_id;
                }
                $listClass = acym_get('class.list');
                $nbSubscribers = $listClass->getSubscribersCount($listsIds);
            }
        }

        $mailData = $mailClass->getOneById($campaign->mail_id);
        $mailData->from_name = empty($mailData->from_name) ? $this->config->get('from_name') : $mailData->from_name;
        $mailData->from_email = empty($mailData->from_email) ? $this->config->get('from_email') : $mailData->from_email;


        $useFromInReply = $this->config->get('from_as_replyto');
        $replytoName = $this->config->get('replyto_name');
        $replytoEmail = $this->config->get('replyto_email');

        if (!empty($mailData->reply_to_name)) {
            $replytoName = $mailData->reply_to_name;
        } elseif ($useFromInReply != 0 || empty($replytoName)) {
            $replytoName = $this->config->get('from_name');
        }

        if (!empty($mailData->reply_to_email)) {
            $replytoEmail = $mailData->reply_to_email;
        } elseif ($useFromInReply != 0 || empty($replytoEmail)) {
            $replytoEmail = $this->config->get('from_email');
        }

        $mailData->reply_to_name = $replytoName;
        $mailData->reply_to_email = $replytoEmail;


        acym_trigger('replaceContent', [&$mailData, false]);
        $receiver = $userClass->getOneByEmail(acym_currentUserEmail());
        if (empty($receiver)) {
            $receiver = new stdClass();
            $receiver->email = acym_currentUserEmail();
            $newID = $userClass->save($receiver);
            $receiver = $userClass->getOneById($newID);
        }
        acym_trigger('replaceUserInformation', [&$mailData, &$receiver, false]);

        $isAuto = $campaign->sending_type == $campaignClass::SENDING_TYPE_AUTO;

        if ($isAuto) {
            $textToDisplay = new stdClass();
            $textToDisplay->triggers = $campaign->sending_params;
            acym_trigger('onAcymDeclareSummary_triggers', [&$textToDisplay], 'plgAcymTime');
            $textToDisplay = $textToDisplay->triggers;
        }

        $editorHelper = acym_get('helper.editor');
        $mailData->settings = json_decode($mailData->settings, true);
        $mailData->stylesheet .= $editorHelper->getSettingsStyle($mailData->settings);

        $data = [
            'campaignClass' => $campaignClass,
            'campaignInformation' => $campaign,
            'mailInformation' => $mailData,
            'listsReceiver' => $campaignLists,
            'nbSubscribers' => $nbSubscribers,
            'containerClass' => $this->stepContainerClass,
            'automatic' => ['isAuto' => $isAuto, 'text' => empty($textToDisplay) ? '' : acym_translation('ACYM_THIS_WILL_GENERATE_CAMPAIGN_AUTOMATICALLY').' '.strtolower($textToDisplay[key($textToDisplay)])],
        ];

        $this->breadcrumb[acym_escape($campaign->name)] = acym_completeLink('campaigns&task=edit&step=summary&id='.$campaign->id);
        parent::display($data);
    }

    public function unpause_campaign()
    {
        $id = acym_getVar('int', 'id', 0);
        if (empty($id)) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_NOT_FOUND'), 'error');
            $this->listing();

            return;
        }

        acym_redirect(acym_completeLink('queue', false, true).'&task=playPauseSending&acym__queue__play_pause__active__new_value=1&acym__queue__play_pause__campaign_id='.$id);

        return;
    }

    private function _stopAction($action)
    {
        acym_checkToken();

        $campaignID = acym_getVar('int', $action);
        $campaignClass = acym_get('class.campaign');

        if (!empty($campaignID)) {
            $campaign = new stdClass();
            $campaign->id = $campaignID;
            $campaign->active = 0;
            $campaign->draft = 1;

            $campaignId = $campaignClass->save($campaign);
            if (empty($campaignId)) {
                acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_CANT_BE_SAVED'), 'error');
            } else {
                acym_enqueueMessage(acym_translation('ACYM_SUCCESSFULLY_SAVED'), 'success');
            }
        } else {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_CANT_BE_SAVED'), 'error');
        }
        $this->listing();
    }

    public function stopSending()
    {
        $this->_stopAction('stopSendingCampaignId');
    }

    public function stopScheduled()
    {
        $this->_stopAction('stopScheduledCampaignId');
    }

    public function confirmCampaign()
    {
        $campaignId = acym_getVar('int', 'id');
        $campaignSendingDate = acym_getVar('string', 'sending_date');
        $resendTarget = acym_getVar('cmd', 'resend_target', '');
        $campaignClass = acym_get('class.campaign');

        $campaign = new stdClass();
        $campaign->id = $campaignId;
        $campaign->draft = 0;
        $campaign->active = 1;
        $campaign->sent = 0;

        if (!empty($resendTarget)) {
            $currentCampaign = $campaignClass->getOneById($campaignId);
            $currentCampaign->sending_params['resendTarget'] = $resendTarget;
            $campaign->sending_params = $currentCampaign->sending_params;
        }

        $resultSave = $campaignClass->save($campaign);

        if ($resultSave) {
            acym_enqueueMessage(acym_translation_sprintf('ACYM_CONFIRMED_CAMPAIGN', acym_date($campaignSendingDate, 'j F Y H:i')), 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_CANT_CONFIRM_CAMPAIGN').' : '.end($campaignClass->errors), 'error');
        }

        $this->listing();
    }

    public function activeAutoCampaign()
    {
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');

        $campaign = new stdClass();
        $campaign->id = $campaignId;
        $campaign->draft = 0;
        $campaign->active = 1;
        $campaign->sent = 0;

        $resultSave = $campaignClass->save($campaign);

        if ($resultSave) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_IS_ACTIVE'), 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
        }

        $this->listing();
    }

    public function saveAsDraftCampaign()
    {
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');

        $campaign = new stdClass();
        $campaign->id = $campaignId;
        $campaign->draft = 1;
        $campaign->active = 0;

        $resultSave = $campaignClass->save($campaign);

        if ($resultSave) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_SUCCESSFULLY_SAVE_AS_DRAFT'), 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_CANT_BE_SAVED').' : '.end($campaignClass->errors), 'error');
        }

        $this->listing();
    }

    public function toggleActivateColumnCampaign()
    {

        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');

        $campaign = $campaignClass->getOneById($campaignId);
        if (empty($campaign)) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_CANT_BE_SAVED').' : '.end($campaignClass->errors), 'error');
            $this->listing();

            return;
        }

        $campaign->active = empty($campaign->active) ? 1 : 0;

        $resultSave = $campaignClass->save($campaign);

        if ($resultSave) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_SUCCESSFULLY_SAVE_AS_DRAFT'), 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_CANT_BE_SAVED').' : '.end($campaignClass->errors), 'error');
        }

        $this->listing();
    }

    public function getAll()
    {
        $campaignClass = acym_get('class.campaign');
        $listClass = acym_get('class.list');

        $allCampaigns = $campaignClass->getAll();

        foreach ($allCampaigns as $campaign) {
            $campaign->tags = $campaignClass->getAllTagsByCampaignId($campaign->id);
            $lists = $campaignClass->getAllListsByCampaignId($campaign->id)[0]->name;
            if (!empty($lists)) {
                $campaign->lists = $campaignClass->getAllListsByCampaignId($campaign->id);
                $campaign->subscribers = 0;
                foreach ($campaign->lists as $list) {
                    $campaign->subscribers += $listClass->getSubscribersCountByListId($list->id);
                }
            }

            $campaign->trigger = $campaignClass->getAllTriggerByCampaignId($campaign->id);
            if (empty($campaign->trigger->automation_id)) {
                $campaign->trigger = null;
            }

            $campaign->sending = 0;
        }

        return $allCampaigns;
    }

    public function getCountStatusFilter($allCampaigns, $type)
    {
        $campaignClass = acym_get('class.campaign');
        $allCountStatus = new stdClass();

        if ($type == 'campaigns') {
            $this->getCountStatusFilterCampaigns($allCampaigns, $allCountStatus, $campaignClass);
        } else {
            $this->getCountStatusFilterCampaignsAuto($allCampaigns, $allCountStatus, $campaignClass);
        }

        return $allCountStatus;
    }

    private function getCountStatusFilterCampaigns($allCampaigns, &$allCountStatus, &$campaignClass)
    {
        $allCountStatus->all = 0;
        $allCountStatus->scheduled = 0;
        $allCountStatus->sent = 0;
        $allCountStatus->draft = 0;

        foreach ($allCampaigns as $campaign) {
            if (empty($campaign->parent_id)) {
                $allCountStatus->all += 1;
                if ($campaignClass::SENDING_TYPE_SCHEDULED == $campaign->sending_type) $allCountStatus->scheduled += 1;
                $allCountStatus->sent += $campaign->sent;
                $allCountStatus->draft += $campaign->draft;
            }
        }
    }

    private function getCountStatusFilterCampaignsAuto($allCampaigns, &$allCountStatus, &$campaignClass)
    {
        $allCountStatus->all = 0;
        $allCountStatus->generated = 0;

        foreach ($allCampaigns as $campaign) {
            if (empty($campaign->parent_id) && $campaignClass::SENDING_TYPE_AUTO == $campaign->sending_type) {
                $allCountStatus->all += 1;
            } elseif (!empty($campaign->parent_id)) {
                $allCountStatus->generated += 1;
            }
        }
    }

    public function cancelDashboardAndGetCampaignsAjax()
    {
        $campaignId = acym_getVar('int', 'id');
        $campaignClass = acym_get('class.campaign');

        if (!empty($campaignId)) {
            $campaign = new stdClass();
            $campaign->id = $campaignId;
            $campaign->active = 0;
            $campaign->draft = 1;

            $campaignId = $campaignClass->save($campaign);
            if (empty($campaignId)) {
                echo 'error';
                exit;
            }

            $campaigns = $campaignClass->getCampaignForDashboard();

            if (empty($campaigns)) {
                echo '<h1 class="acym__dashboard__active-campaigns__none">'.acym_translation('ACYM_NONE_OF_YOUR_CAMPAIGN_SCHEDULED_GO_SCHEDULE_ONE').'</h1>';
                exit;
            }

            $echo = '';

            foreach ($campaigns as $campaign) {
                $echo .= '<div class="cell grid-x acym__dashboard__active-campaigns__one-campaign">
                        <a class="acym__dashboard__active-campaigns__one-campaign__title medium-4 small-12" href="'.acym_completeLink('campaigns&task=edit&step=editEmail&id=').$campaign->id.'">'.$campaign->name.'</a>
                        <div class="acym__dashboard__active-campaigns__one-campaign__state medium-2 small-12 acym__background-color__blue text-center"><span>'.acym_translation('ACYM_SCHEDULED').' : '.acym_getDate($campaign->sending_date, 'M. j, Y').'</span></div>
                        <div class="medium-6 small-12"><p id="'.$campaign->id.'" class="acym__dashboard__active-campaigns__one-campaign__action acym__color__dark-gray">'.acym_translation('ACYM_CANCEL_SCHEDULING').'</p></div>
                    </div>
                    <hr class="cell small-12">';
            }
            echo $echo;
            exit;
        } else {
            echo 'error';
            exit;
        }
    }

    public function addQueue()
    {
        acym_checkToken();

        $campaignID = acym_getVar('int', 'id', 0);

        if (empty($campaignID)) {
            acym_enqueueMessage(acym_translation('ACYM_CAMPAIGN_NOT_FOUND'), 'error');
        } else {
            $campaignClass = acym_get('class.campaign');
            $campaign = $campaignClass->getOneByIdWithMail($campaignID);

            $resendTarget = acym_getVar('cmd', 'resend_target', '');
            if (!empty($resendTarget)) {
                $currentCampaign = $campaignClass->getOneById($campaignID);
                $currentCampaign->sending_params['resendTarget'] = $resendTarget;
                $campaignClass->save($currentCampaign);
            }

            $status = $campaignClass->send($campaignID);

            if ($status) {
                acym_enqueueMessage(acym_translation_sprintf('ACYM_CAMPAIGN_ADDED_TO_QUEUE', $campaign->name), 'info');
            } else {
                if (empty($campaignClass->errors)) {
                    acym_enqueueMessage(acym_translation_sprintf('ACYM_ERROR_QUEUE_CAMPAIGN', $campaign->name), 'error');
                } else {
                    acym_enqueueMessage($campaignClass->errors, 'error');
                }
            }
        }

        $this->_redirectAfterQueued();
    }

    private function _redirectAfterQueued()
    {
        if (acym_isAdmin() && (!acym_level(1) || $this->config->get('cron_last', 0) < (time() - 43200))) {
            acym_redirect(acym_completeLink('queue&task=campaigns', false, true));
        } else {
            $this->listing();
        }
    }

    public function countNumberOfRecipients()
    {
        $listsSelected = acym_getVar('array', 'listsSelected', []);
        if (empty($listsSelected)) {
            echo 0;
            exit;
        }

        $listClass = acym_get('class.list');
        echo $listClass->getTotalSubCount($listsSelected);
        exit;
    }

    public function deleteAttach()
    {
        $mailid = acym_getVar('int', 'mail', 0);
        $attachid = acym_getVar('int', 'id', 0);

        if (!empty($mailid) && $attachid >= 0) {
            $mailClass = acym_get('class.mail');

            if ($mailClass->deleteOneAttachment($mailid, $attachid)) {
                echo json_encode(['message' => acym_translation('ACYM_ATTACHMENT_WELL_DELETED')]);
                exit;
            }
        }

        echo json_encode(['error' => acym_translation('ACYM_COULD_NOT_DELETE_ATTACHMENT')]);
        exit;
    }

    public function test()
    {
        $result = new stdClass();
        $result->type = 'info';
        $result->timer = 5000;
        $result->message = '';

        $campaignId = acym_getVar('int', 'id', 0);

        $campaignClass = acym_get('class.campaign');
        $campaign = $campaignClass->getOneById($campaignId);

        if (empty($campaign)) {
            $result->type = 'error';
            $result->timer = '';
            $result->message = acym_translation('ACYM_CAMPAIGN_NOT_FOUND');
            exit;
        }

        $mailerHelper = acym_get('helper.mailer');
        $mailerHelper->autoAddUser = true;
        $mailerHelper->checkConfirmField = false;
        $mailerHelper->report = false;


        $report = [];
        $testNote = acym_getVar('string', 'test_note', '');

        $testEmails = explode(',', acym_getVar('string', 'test_emails'));
        foreach ($testEmails as $oneAddress) {
            if (!$mailerHelper->sendOne($campaign->mail_id, $oneAddress, true, $testNote)) {
                $result->type = 'error';
                $result->timer = '';
            }

            if (!empty($mailerHelper->reportMessage)) {
                $report[] = $mailerHelper->reportMessage;
            }
        }

        $result->message = implode('<br/>', $report);
        echo json_encode($result);
        exit;
    }

    public function tests()
    {
        $campaignClass = acym_get('class.campaign');
        acym_setVar('step', 'tests');
        acym_setVar('layout', 'tests');
        $campaignId = acym_getVar('int', 'id', 0);

        $campaign = $campaignClass->getOneByIdWithMail($campaignId);

        if (empty($campaign->id)) {
            acym_enqueueMessage(acym_translation('ACYM_CANT_GET_CAMPAIGN_INFORMATION'), 'error');
            $this->listing();

            return;
        }

        $testEmails = acym_getVar('array', 'test_emails', [acym_currentUserEmail()]);
        foreach ($testEmails as $oneEmail) {
            $defaultEmails[$oneEmail] = $oneEmail;
        }

        $data = [
            'id' => $campaign->id,
            'test_emails' => $defaultEmails,
            'upgrade' => !acym_level(1) ? true : false,
            'version' => 'enterprise',
        ];

        $this->breadcrumb[acym_escape($campaign->name)] = acym_completeLink('campaigns&task=edit&step=tests&id='.$campaign->id);
        parent::display($data);
    }

    public function saveTests()
    {
        $this->edit();
    }

    public function checkContent()
    {
        $campaignId = acym_getVar('int', 'id', 0);
        $campaignClass = acym_get('class.campaign');
        $campaign = $campaignClass->getOneByIdWithMail($campaignId);

        $spamWords = [
            '4U',
            'you are a winner',
            'For instant access',
            'Accept credit cards',
            'Claims you registered with',
            'For just $',
            'Act now!',
            'Don’t hesitate!',
            'Click below',
            'Free',
            'income',
            'Click here',
            'Click to remove',
            'All natural',
            'Amazing',
            'Compare rates',
            'Apply Online',
            'your business',
            'As seen on',
            'all orders',
            'Auto email removal',
            'bankruptcy',
            'debt',
            'Be amazed',
            'Copy accurately',
            'Be your own boss',
            'Being a member',
            'Big bucks',
            'Credit card',
            'Bill',
            'Cures baldness',
            'Billing address',
            'Billion dollars',
            'Dear friend',
            'Brand new pager',
            'Bulk email',
            'Different reply to',
            'Buy direct',
            'Dig up dirt',
            'Full refund',
            'Buying judgments',
            'Direct email',
            'Get It Now',
            'Cable converter',
            'Direct marketing',
            'Get paid',
            'Get started now',
            'Call now',
            'Do it today',
            'Gift certificate',
            'Calling creditors',
            'Don’t delete',
            'Great offer',
            'Can’t live without',
            'Drastically reduced',
            'Guarantee',
            'Cancel at any time',
            'Earn per week',
            'Have you been turned down?',
            'Easy terms',
            'Hidden assets',
            'Eliminate bad credit',
            'Home employment',
            'Cash',
            'Email harvest',
            'Human growth hormone',
            'Casino',
            'Email marketing',
            'Expect to earn',
            'In accordance with laws',
            'Fantastic deal',
            'Increase sales',
            'Viagra',
            'Increase traffic',
            'Insurance',
            'Find out anything',
            'Investment decision',
            'it\'s legal',
            'It\'s effective',
            'Join millions of',
            'No questions asked',
            'Reverses aging',
            'No selling',
            'Risk',
            'Limited time only',
            'No strings attached',
            'Round the world',
            'Not intended',
            'Lose weight',
            'Off shore',
            'Safeguard notice',
            'Lower interest rates',
            'Offer expires',
            'Satisfaction guaranteed',
            'Lower monthly payment',
            'coupon',
            'Save $',
            'Lowest price',
            'Luxury car',
            'Save up to',
            'Once in a lifetime',
            'Score with babes',
            'Marketing solutions',
            'Mass email',
            'guaranteed',
            'See for yourself',
            'Meet singles',
            'One time mailing',
            'Sent in compliance',
            'Member stuff',
            'opportunity',
            'Online pharmacy',
            'Serious only',
            'MLM',
            'Only $',
            'Shopping spree',
            'Social security number',
            'trial offer',
            'Special promotion',
            'More Internet traffic',
            'Stock alert',
            'Outstanding values',
            'Pennies a day',
            'Stock pick',
            'New customers only',
            'money',
            'Stop snoring',
            'New domain extensions',
            'Please read',
            'Strong buy',
            'Potential earnings',
            'Stuff on sale',
            'No age restrictions',
            'Subject to credit',
            'No catch',
            'Supplies are limited',
            'No claim forms',
            'Produced and sent out',
            'Take action now',
            'No cost',
            'Profits',
            'hidden charges',
            'No credit check',
            'Promise you',
            'No disappointment',
            'Pure profit',
            'Real thing',
            'No fees',
            'Refinance home',
            'The best rates',
            'No gimmick',
            'The following form',
            'No inventory',
            'No investment',
            'giving it away',
            'No medical exams',
            'Removes wrinkles',
            'This isn’t junk',
            'No middleman',
            'This isn’t spam',
            'No obligation',
            'initial investment',
            'University diplomas',
            'No purchase necessary',
            'Reserves the right',
            'Unlimited',
            'We honor all',
            'Will not believe your eyes',
            'Urgent',
            'Winner',
            'US dollars',
            'What are you waiting for?',
            'Winning',
            'While supplies last',
            'Work at home',
            'drugs',
            'While you sleep',
            'You have been selected',
            'We hate spam',
            'Why pay more?',
        ];

        $errors = [];
        foreach ($spamWords as $oneWord) {
            if ((bool)preg_match('#'.preg_quote($oneWord, '#').'#Uis', $campaign->subject.$campaign->body)) {
                $errors[] = $oneWord;
            }
        }

        if (count($errors) > 2) {
            echo acym_translation('ACYM_TESTS_CONTENT_DESC');
            echo '<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
        }
        exit;
    }

    public function checkLinks()
    {
        $campaignId = acym_getVar('int', 'id', 0);
        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');
        $campaign = $campaignClass->getOneById($campaignId);
        $mail = $mailClass->getOneById($campaign->mail_id);

        acym_trigger('replaceContent', [&$mail, false]);
        $userClass = acym_get('class.user');
        $receiver = $userClass->getOneByEmail(acym_currentUserEmail());
        if (empty($receiver)) {
            $receiver = new stdClass();
            $receiver->email = acym_currentUserEmail();
            $newID = $userClass->save($receiver);
            $receiver = $userClass->getOneById($newID);
        }
        acym_trigger('replaceUserInformation', [&$mail, &$receiver, false]);

        preg_match_all('# (href|src)="([^"]+)"#Uis', acym_absoluteURL($mail->body), $URLs);

        $errors = [];
        $processed = [];
        foreach ($URLs[2] as $oneURL) {
            if (in_array($oneURL, $processed)) continue;
            if (0 === strpos($oneURL, 'mailto:')) continue;
            if (strlen($oneURL) > 1 && 0 === strpos($oneURL, '#')) continue;

            $processed[] = $oneURL;

            $headers = @get_headers($oneURL);
            $headers = is_array($headers) ? implode("\n ", $headers) : $headers;

            if (empty($headers) || preg_match('#^HTTP/.*\s+[(200|301|302|304)]+\s#i', $headers) !== 1) {
                $errors[] = '<a target="_blank" href="'.$oneURL.'">'.(strlen($oneURL) > 50 ? substr($oneURL, 0, 25).'...'.substr($oneURL, strlen($oneURL) - 20) : $oneURL).'</a>';
            }
        }

        if (!empty($errors)) {
            echo '<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
        }

        exit;
    }

    public function checkSPAM()
    {
        $result = new stdClass();
        $result->type = 'error';
        $result->message = '';

        $campaignId = acym_getVar('int', 'id', 0);
        $campaignClass = acym_get('class.campaign');
        $campaign = $campaignClass->getOneByIdWithMail($campaignId);

        if (empty($campaign->mail_id)) {
            $result->message = acym_translation('ACYM_CAMPAIGN_NOT_FOUND');
        } else {
            ob_start();
            $urlSite = trim(base64_encode(preg_replace('#https?://(www2?\.)?#i', '', ACYM_LIVE)), '=/');
            $url = ACYM_SPAMURL.'spamTestSystem&component=acymailing&level='.strtolower($this->config->get('level', 'starter')).'&urlsite='.$urlSite;
            $spamtestSystem = acym_fileGetContent($url, 30);
            $warnings = ob_get_clean();

            if (empty($spamtestSystem) || !empty($warnings)) {
                $result->message = acym_translation('ACYM_ERROR_LOAD_FROM_ACYBA').(!empty($warnings) && acym_isDebug() ? $warnings : '');
            } else {
                $decodedInformation = json_decode($spamtestSystem, true);
                if (!empty($decodedInformation['messages']) || !empty($decodedInformation['error'])) {
                    $msgError = empty($decodedInformation['messages']) ? '' : $decodedInformation['messages'].'<br />';
                    $msgError .= empty($decodedInformation['error']) ? '' : $decodedInformation['error'];
                    $result->message = $msgError;
                } else {
                    if (empty($decodedInformation['email'])) {
                        $result->message = acym_translation('ACYM_SPAMTEST_MISSING_EMAIL');
                    } else {
                        $mailerHelper = acym_get('helper.mailer');
                        $mailerHelper->checkConfirmField = false;
                        $mailerHelper->checkEnabled = false;
                        $mailerHelper->loadedToSend = true;
                        $mailerHelper->report = false;

                        $receiver = new stdClass();
                        $receiver->id = 0;
                        $receiver->email = $decodedInformation['email'];
                        $receiver->name = $decodedInformation['name'];
                        $receiver->confirmed = 1;
                        $receiver->enabled = 1;

                        if ($mailerHelper->sendOne($campaign->mail_id, $receiver)) {
                            $result->type = 'success';
                            $result->message = 'https://mailtester.acyba.com/'.(substr($decodedInformation['email'], 0, strpos($decodedInformation['email'], '@')));
                            $result->lang = acym_getLanguageTag();
                        } else {
                            $result->message = $mailerHelper->reportMessage;
                        }
                    }
                }
            }
        }

        echo json_encode($result);
        exit;
    }

    public function saveAjax()
    {
        $return = $this->saveEditEmail(true);
        echo json_encode(['error' => !$return ? acym_translation('ACYM_ERROR_SAVING') : '', 'data' => $return]);
        exit;
    }

    public function saveAsTmplAjax()
    {
        $mailController = acym_get('controller.mails');
        $isWellSaved = $mailController->store(true);
        echo json_encode(['error' => $isWellSaved ? '' : acym_translation('ACYM_ERROR_SAVING'), 'data' => $isWellSaved]);
        exit;
    }

    public function searchTestReceivers()
    {
        $search = acym_getVar('string', 'search', '');
        $userClass = acym_get('class.user');
        $users = $userClass->getUsersLikeEmail($search);

        $return = [];
        foreach ($users as $oneUser) {
            $return[] = [$oneUser->id, $oneUser->email];
        }
        echo json_encode($return);
        exit;
    }

    public function summaryGenerated()
    {
        $campaignId = acym_getVar('int', 'id', 0);
        $mailClass = acym_get('class.mail');

        acym_setVar('layout', 'summary_generated');

        $generatedCampaign = $this->_loadCampaignMail($campaignId);

        if (!$generatedCampaign) {
            acym_enqueueMessage(acym_translation('ACYM_COULD_NOT_LOAD_CAMPAIGN'), 'error');
            $this->listing();

            return;
        }

        $campaign = $generatedCampaign['campaign'];
        $mail = $generatedCampaign['mail'];

        $lists = $mailClass->getAllListsByMailId($mail->id);

        if (empty($lists)) {
            $this->listing();

            return;
        }

        $parentCampaign = $this->_loadCampaignMail($campaign->parent_id);
        if (!$parentCampaign) {
            $parentCampaign = ['campaign' => false, 'mail' => false];
        }

        $campaign->waiting_confirmation = false;
        if ($campaign->draft && $campaign->active) {
            $campaign->waiting_confirmation = true;
        }
        $campaign->canceled = false;
        if (!$campaign->draft && !$campaign->active) {
            $campaign->canceled = true;
        }

        $data = [
            'campaign' => $campaign,
            'mail' => $mail,
            'lists' => $lists,
            'parent_campaign' => $parentCampaign['campaign'],
            'parent_mail' => $parentCampaign['mail'],
        ];

        $this->breadcrumb[acym_escape($mail->name)] = acym_completeLink('campaigns&task=summaryGenerated&id='.$campaign->id);
        parent::display($data);
    }

    protected function changeStatusGeneratedCampaign($statusToApply = 'disable')
    {
        $campaignId = acym_getVar('int', 'id', 0);
        $campaignClass = acym_get('class.campaign');

        $campaign = $this->_loadCampaignMail($campaignId);

        if (!$campaign) {
            acym_enqueueMessage(acym_translation('ACYM_COULD_NOT_LOAD_CAMPAIGN'), 'error');
            $this->listing();

            return;
        }

        $campaign = $campaign['campaign'];

        if ('disable' === $statusToApply) {
            $campaign->sent = 0;
            $campaign->active = 0;
            $campaign->draft = 0;
            $successMsg = acym_translation('ACYM_CAMPAIGN_HAS_BEEN_DISABLED');
        } else {
            $campaign->active = 1;
            $campaign->draft = 1;
            $successMsg = acym_translation('ACYM_CAMPAIGN_HAS_BEEN_ENABLED');
        }

        if ($campaignClass->save($campaign)) {
            acym_enqueueMessage($successMsg, 'success');
        } else {
            acym_enqueueMessage(acym_translation('ACYM_ERROR_SAVING'), 'error');
        }

        if ('enable' === $statusToApply) {
            acym_setVar('id', $campaignId);
            $this->summaryGenerated();
        } else {
            acym_setVar('campaigns_status', 'generated');
            $this->listing();

            return;
        }
    }

    public function disableGeneratedCampaign()
    {
        $this->changeStatusGeneratedCampaign('disable');
    }

    public function enableGeneratedCampaign()
    {
        $this->changeStatusGeneratedCampaign('enable');
    }

    private function _loadCampaignMail($campaignId)
    {
        if (empty($campaignId)) return false;

        $campaignClass = acym_get('class.campaign');
        $mailClass = acym_get('class.mail');

        $campaign = $campaignClass->getOneById($campaignId);
        if (empty($campaign)) return false;

        $mail = $mailClass->getOneById($campaign->mail_id);
        if (empty($mail)) return false;


        if (empty($mail->from_name)) $mail->from_name = $this->config->get('from_name');
        if (empty($mail->from_email)) $mail->from_email = $this->config->get('from_email');
        if (empty($mail->reply_to_name)) $mail->reply_to_name = $this->config->get('replyto_name');
        if (empty($mail->reply_to_email)) $mail->reply_to_email = $this->config->get('replyto_email');

        return ['campaign' => $campaign, 'mail' => $mail];
    }
}

