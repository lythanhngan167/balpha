<?php
/**
 * @version    2.10.x
 * @package    K2
 * @author     JoomlaWorks https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2020 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>

<!-- Comments Form -->
<h3><?php echo JText::_('K2_LEAVE_A_COMMENT') ?></h3>


<form action="<?php echo JURI::root(true); ?>/index.php" method="post" id="comment-form" class="form-validate">

  <div class="neme-comment">
  <label class="formName" for="userName"><?php echo JText::_('K2_NAME'); ?> *</label>
  <input class="inputbox" type="text" name="userName" id="userName" value="<?php echo JText::_('K2_ENTER_YOUR_NAME'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('K2_ENTER_YOUR_NAME'); ?>';" onfocus="if(this.value=='<?php echo JText::_('K2_ENTER_YOUR_NAME'); ?>') this.value='';" />
  </div>
    <label class="formComment" for="commentText"><?php echo JText::_('K2_MESSAGE'); ?> *</label>

    <div class="content-comment">

    <div class="text-comment">
    <textarea rows="20" cols="10" class="inputbox" onblur="if(this.value=='') this.value='<?php echo JText::_('K2_ENTER_YOUR_MESSAGE_HERE'); ?>';" onfocus="if(this.value=='<?php echo JText::_('K2_ENTER_YOUR_MESSAGE_HERE'); ?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_('K2_ENTER_YOUR_MESSAGE_HERE'); ?></textarea>
    </div>
    </div>


    <!-- <label class="formEmail" for="phoneNumber"><?php echo JText::_('K2_PHONE'); ?> *</label>
    <input class="inputbox" type="text" name="phone_number" id="phone_number" value="<?php echo JText::_('K2_ENTER_YOUR_PHONE'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('K2_ENTER_YOUR_PHONE'); ?>';" onfocus="if(this.value=='<?php echo JText::_('K2_ENTER_YOUR_PHONE'); ?>') this.value='';" />

    <label class="formEmail" for="commentEmail"><?php echo JText::_('K2_EMAIL'); ?></label>
    <input class="inputbox" type="text" name="commentEmail" id="commentEmail" value="<?php echo JText::_('K2_ENTER_YOUR_EMAIL_ADDRESS'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('K2_ENTER_YOUR_EMAIL_ADDRESS'); ?>';" onfocus="if(this.value=='<?php echo JText::_('K2_ENTER_YOUR_EMAIL_ADDRESS'); ?>') this.value='';" /> -->

    <!-- <label class="formUrl" for="commentURL"><?php echo JText::_('K2_WEBSITE_URL'); ?></label>
    <input class="inputbox" type="text" name="commentURL" id="commentURL" value="<?php echo JText::_('K2_ENTER_YOUR_SITE_URL'); ?>" onblur="if(this.value=='') this.value='<?php echo JText::_('K2_ENTER_YOUR_SITE_URL'); ?>';" onfocus="if(this.value=='<?php echo JText::_('K2_ENTER_YOUR_SITE_URL'); ?>') this.value='';" /> -->

    <?php if($this->params->get('recaptcha') && ($this->user->guest || $this->params->get('recaptchaForRegistered', 1))): ?>
    <?php if(!$this->params->get('recaptchaV2')): ?>
    <label class="formRecaptcha"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
    <?php endif; ?>
    <div id="recaptcha" class="<?php echo $this->recaptchaClass; ?>"></div>
    <?php endif; ?>
    <?php if($this->params->get('commentsFormNotes')): ?>
    <div class="itemCommentsFormNotes">
        <?php if($this->params->get('commentsFormNotesText')): ?>
        <?php echo nl2br($this->params->get('commentsFormNotesText')); ?>
        <?php else: ?>
        <?php echo JText::_('K2_COMMENT_FORM_NOTES') ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="comment-button">
    <input type="submit" class="button" id="submitCommentButton" value="<?php echo JText::_('K2_SUBMIT_COMMENT'); ?>" />
    </div>
    <span id="formLog"></span>

    <input type="hidden" name="option" value="com_k2" />
    <input type="hidden" name="view" value="item" />
    <input type="hidden" name="task" value="comment" />
    <input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
    <?php echo JHTML::_('form.token'); ?>
</form>
