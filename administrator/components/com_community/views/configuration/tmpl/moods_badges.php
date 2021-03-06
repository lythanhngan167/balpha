<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>

<div class="widget-box">
    <div class="widget-header widget-header-flat">
        <h5><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_BACKGROUNDS' ); ?></h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <table>
                <tbody>
                    <tr>
                        <td width="200" class="key">
                            <span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_REACTIONS_TIPS'); ?>">
                            <?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_REACTIONS' ); ?>
                            </span>
                        </td>
                        <td valign="top">
                            <select name="enablereaction">
                                <option <?php echo ( $this->config->get('enablereaction') == '0' ) ? 'selected="true"' : ''; ?> value="0"><?php echo JText::_('COM_COMMUNITY_REACTION_OPTION_LIKES');?></option>
                                <option <?php echo ( $this->config->get('enablereaction') == '1' ) ? 'selected="true"' : ''; ?> value="1"><?php echo JText::_('COM_COMMUNITY_REACTION_OPTION_LIKES_REACTIONS');?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" class="key">
                            <span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_BACKGROUNDS_TIPS'); ?>">
                            <?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_BACKGROUNDS' ); ?>
                            </span>
                        </td>
                        <td valign="top">
                            <?php echo CHTMLInput::checkbox('enablebackground' ,'ace-switch ace-switch-5', null , $this->config->get('enablebackground') ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200" class="key">
                            <span class="js-tooltip" title="<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_MOODS_TIPS'); ?>">
                            <?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_MOODS' ); ?>
                            </span>
                        </td>
                        <td valign="top">
                            <?php echo CHTMLInput::checkbox('enablemood' ,'ace-switch ace-switch-5', null , $this->config->get('enablemood') ); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="key">
                            <span class="js-tooltip" title="<?php echo JTexT::_('COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_BADGES_TIPS'); ?>">
                                <?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MOODS_BADGES_ENABLE_BADGES')?>
                            </span>
                        </td>
                        <td>
                            <?php echo CHTMLInput::checkbox('enable_badges', 'ace-switch ace-switch-5', null, $this->config->get('enable_badges'))?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
