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
?>

<div class="joms-stream__body no-head">
    <p>
        <svg class="joms-icon" viewBox="0 0 16 16">
            <use xlink:href="<?php echo CRoute::getURI(); ?>#joms-icon-users"></use>
        </svg>
        <a href="<?php echo CUrlHelper::userLink($user->id); ?>"><?php echo $user->getDisplayName(false, true); ?></a> -
        <?php echo JText::sprintf('COM_COMMUNITY_PAGES_PAGE_UPDATED', $this->page->getLink(), $this->page->name); ?>
    </p>
    <?php
        $my = CFactory::getUser();
        $this->load('activities.stream.options');
    ?>
</div>
