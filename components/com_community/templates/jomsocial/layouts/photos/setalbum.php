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

?>

<form>
    <div class="joms-select--wrapper">
        <select class="joms-select" name="albumid">
            <option value="-1" selected="selected"><?php echo JText::_('COM_COMMUNITY_PHOTOS_SELECT_ALBUM'); ?></option>
            <?php foreach ($albums as $album) { ?>
            <option value="<?php echo $album->id; ?>"><?php echo JHTML::_('string.truncate', $this->escape($album->name), 64); ?></option>
            <?php } ?>
        </select>
    </div>
</form>
