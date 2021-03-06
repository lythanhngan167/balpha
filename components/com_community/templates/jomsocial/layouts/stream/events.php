<?php
    /**
     * @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
     * @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
     * @author iJoomla.com <webmaster@ijoomla.com>
     * @url https://www.jomsocial.com/license-agreement
     * The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
     * More info at https://www.jomsocial.com/license-agreement
     */

    defined('_JEXEC') or die('Restricted access');

    $user = CFactory::getUser($this->act->actor);

    // Setup event table
    $event = JTable::getInstance('Event', 'CTable');
    $event->load($act->eventid);

    if($event->type == 'group'){
        $group = JTable::getInstance('Group', 'CTable');
        $group->load($event->contentid);
        $this->set('group', $group);
    }

    if($event->type == 'page'){
        $page = JTable::getInstance('Page', 'CTable');
        $page->load($event->contentid);
        $this->set('page', $page);
    }

    $this->set('event', $event);
    $action = '';
    if (is_object($act->params)) {
        $action = $act->params->get('action');
        $actors = $act->params->get('actors');
    } else {
        if (isset($act->action)) {
            $action = $act->action;
        }
        $actors = $act->actors;
    }

    $this->set('actors', $actors);

    $this->load('activities.stream.options');
?>

<?php if ($this->act->app == 'events.wall') { ?>
    <?php $this->load('activities.profile'); ?>
<?php }else if($this->act->app == 'events.update') { ?>
    <?php $this->load('activities.events.update'); ?>
<?php } else {
    if ($action == 'events.create') { ?>
        <?php if (JFactory::getApplication()->input->get('view') != 'events') {
            $this->load('activities/events/create');
        }; //hide this from event page
        ?>
    <?php } else {
        if ($action == 'events.attendence.attend') { ?>
            <?php $this->load('activities.events.attend'); ?>
        <?php } else {
            if ($this->act->app == 'events.featured') { ?>
                <?php $this->load('activities/events/featured'); ?>
            <?php } else { ?>
                <?php
                $table = JTable::getInstance('Activity', 'CTable');
                $table->load($this->act->id);
                if (!$table->delete()) {
                    ?>

                    <div class="joms-stream__header">
                        <div class= "joms-avatar--stream <?php echo CUserHelper::onlineIndicator($user); ?>">
                            <a href="<?php echo CUrlHelper::userLink($user->id); ?>">
                                <img src="<?php echo $user->getThumbAvatar(); ?>"
                                     data-author="<?php echo $user->id; ?>" alt="<?php echo $user->getDisplayName(); ?>">
                            </a>
                        </div>
                        <div class="joms-stream__meta">

                        </div>
                    </div>
                    <div class="joms-stream__body">
                        <?php
                            $html = CGroups::getActivityContentHTML($act);
                            echo $html;
                        ?>
                    </div>

                    <?php $this->load('stream/footer'); ?>

                <?php }
            }
        }
    }
} ?>
