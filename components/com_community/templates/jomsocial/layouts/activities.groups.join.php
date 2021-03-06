<?php
/**
 * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
 * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author iJoomla.com <webmaster@ijoomla.com>
 * @url https://www.jomsocial.com/license-agreement
 * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
 * More info at https://www.jomsocial.com/license-agreement
 */

defined('_JEXEC') or die();

$date = JDate::getInstance($act->created);

if ($config->get('activitydateformat') == "lapse") {
    $createdTime = CTimeHelper::timeLapse($date);
} else {
    $createdTime = $date->format($config->get('profileDateFormat'));
}

$format = ($config->get('eventshowampm')) ? JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_12H') : JText::_('COM_COMMUNITY_DATE_FORMAT_LC2_24H');

// Setup group table
$group = JTable::getInstance('Group', 'CTable');

if (isset($act->groupid) && !empty($act->groupid)) {
    $group->load($act->groupid);
} else {
    $group->load($act->cid);
}

$this->set('group', $group);
$truncateVal = 60;
$user = CFactory::getUser($this->act->actor);
$users = explode(',', $this->actors);

// We want the last guy joining to be the first mentioned
$users = array_reverse($users);
$avatar = CFactory::getUser($users[0]);
$userCount = count($users);

if ($userCount != $group->getMembersCount()) {
    $userCount = CActivitiesHelper::updateGroupJoinMembers($act->id, $group);
}

$slice = 2;

if ($userCount > 2) {
    $slice = 1;
}

$users = array_slice($users, 0, $slice);

$actorsHTML = array();
?>

<div class="joms-stream__header">
    <div class= "joms-avatar--stream <?php echo CUserHelper::onlineIndicator($avatar); ?>">
        <?php if (count($users) > 1 && false) { // added false for now because we have to show the last user avatar ?>
            <svg class="joms-icon" viewBox="0 0 16 16">
                <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-users"></use>
            </svg>
        <?php } else { ?>
            <a href="<?php echo CUrlHelper::userLink($avatar->id); ?>">
                <img data-author="<?php echo $avatar->id; ?>" src="<?php echo $avatar->getThumbAvatar(); ?>" alt="<?php echo $avatar->getDisplayName(); ?>">
            </a>
        <?php } ?>
    </div>
    <div class="joms-stream__meta">
        <?php
            foreach ($users as $actor) {
                $user = CFactory::getUser($actor);
                $actorsHTML[] = '<a class="cStream-Author" href="' . CUrlHelper::userLink($user->id) . '">' . $user->getDisplayName(false, true) . '</a>';
            }

            $others = '';

            if ($userCount > 2) {
                $others = JText::sprintf('COM_COMMUNITY_STREAM_OTHERS_JOIN_GROUP', $userCount-1, 'onclick="joms.api.streamShowOthers(' . $act->id . ');return false;"');
            }

            echo implode(' ' . JText::_('COM_COMMUNITY_AND') . ' ', $actorsHTML) . $others;
            echo JText::sprintf('COM_COMMUNITY_GROUPS_GROUP_JOIN', $this->group->getLink(), $this->group->name);
        ?>
        <span class="joms-stream__time"><small><?php echo $createdTime; ?></small></span>
    </div>
    <?php
        $this->load('activities.stream.options');
    ?>
</div>

<div class="joms-stream__body">
    <div class="joms-media like">
        <a href="<?php echo $this->group->getLink();?>">
            <div class="joms-media__cover">
                <img src="<?php echo $this->group->getCover(); ?>" alt="<?php echo $this->group->name; ?>" />
            </div>
        </a>
        <h4 class="joms-text--title">
            <a href="<?php echo $this->group->getLink();?>">
                <?php echo JHTML::_('string.truncate',$this->group->name , $truncateVal); ?>
            </a>
        </h4>
        <p class="joms-text--desc"><?php echo JHTML::_('string.truncate',strip_tags($group->description) , $config->getInt('streamcontentlength')); ?></p>
    </div>

</div>
