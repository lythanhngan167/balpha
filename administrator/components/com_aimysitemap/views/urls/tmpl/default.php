<?php
/*
 * Copyright (c) 2017-2020 Aimy Extensions, Netzum Sorglos Software GmbH
 * Copyright (c) 2014-2017 Aimy Extensions, Lingua-Systems Software GmbH
 *
 * https://www.aimy-extensions.com/
 *
 * License: GNU GPLv2, see LICENSE.txt within distribution and/or
 *          https://www.aimy-extensions.com/software-license.html
 */
 defined( '_JEXEC' ) or die(); try { JHtml::_( version_compare( JVERSION, '3.3.0', 'lt' ) ? 'behavior.framework' : 'behavior.core' ); JHtml::_( 'formbehavior.chosen', 'select' ); } catch ( Exception $e ) { } require_once( JPATH_COMPONENT . '/helpers/rights.php' ); $list_order = $this->escape( $this->state->get( 'list.ordering' ) ); $list_dir = $this->escape( $this->state->get( 'list.direction' ) ); $genericlist_opts = array( 'list.attr' => array( 'class' => 'inputbox' ) ); if ( ! $this->allow_edit ) { $genericlist_opts[ 'list.attr' ][ 'disabled' ] = 'disabled'; } AimySitemapCompatHelper::addInlineJavascript( 'jQuery( document ).ready(function()' . '{' . 'Joomla.submitbutton = function( task )' . '{' . 'if ( task == "urls.reset_index" )' . '{' . 'var submit = confirm( ' . json_encode( JText::_( 'AIMY_SM_MSG_CONFIRM_RESET_INDEX' ) ) . ');' . 'if ( ! submit )' . '{' . 'return false;' . '}' . '}' . 'if ( task == "urls.write" && window.aimysitemap.isHttpsOnly )' . '{' . 'var ca = jQuery( "#urls_list .state .aimy-icon-ok" )' . '.length + window.aimysitemap.hiddenActiveCount;' . 'if ( ca > 50 )' . '{' . 'var msg = ' . json_encode( JText::_( 'AIMY_SM_MSG_TOO_MANY_ACTIVE_ENTRIES' ) ) . '.replace( /@@CURRENT_ENTRIES@@/, ca )' . '.replace( /@@MAX_ENTRIES@@/, "50" );' . 'if ( ! confirm( msg ) )' . '{' . 'return false;' . '}' . '}' . '}' . 'Joomla.submitform( task );' . '};' . 'AimySitemapAjaxInit( "' . JSession::getFormToken() .'" );' . '});' ); if ( empty( $this->items ) ) { AimySitemapCompatHelper::addInlineJavascript( 'jQuery( document ).ready(function()' . '{' . 'jQuery( "#toolbar-file button, #toolbar-trash button" )' . '.each(function()' . '{' . 'jQuery( this ).prop( "disabled", "disabled" );' . '});' . '});' ); } ?>

<div id="j-main-container" class="j-main-container aimy-main clearfix">

<form action="<?php
 echo JRoute::_( 'index.php?option=com_aimysitemap&view=urls' ); ?>" method="post" name="adminForm" id="adminForm" class="clearfix">

<div id="view-toolbar" class="btn-toolbar clearfix">
    <div class="btn-group pull-left filter-search">
        <label for="filter_search" class="element-invisible"><?php
 echo JText::_( 'AIMY_SM_FILTER_SEARCH' ); ?></label>
        <input type="text" name="filter_search" id="filter_search"
            placeholder="<?php
 echo htmlspecialchars( JText::_( 'AIMY_SM_FILTER_SEARCH' ) ); ?>" value="<?php
 echo $this->escape( $this->state->get( 'filter.search' ) ); ?>" title="<?php
 echo htmlspecialchars( JText::_( 'AIMY_SM_FILTER_SEARCH' ) ); ?>" />
    </div>
    <div class="btn-group pull-left">
        <button class="btn hasTooltip" type="submit" title="<?php
 echo JText::_( 'JSEARCH_FILTER_SUBMIT' ); ?>"><i class="aimy-icon-search"></i></button>
        <button class="btn hasTooltip" type="button" title="<?php
 echo JText::_( 'JSEARCH_FILTER_CLEAR' ); ?>" onclick="<?php
 echo "jQuery( '#filter_search' ).val( '' );", "jQuery( '#adminForm' ).submit();"; ?>"><i class="aimy-icon-cancel"></i></button>
    </div>


    <div class="btn-group pull-left">
        <label for="filter_state" class="element-invisible"><?php
 echo JText::_( 'AIMY_SM_FILTER_SELECT_STATE' ); ?></label>
        <?php
 echo JHtml::_( 'select.genericlist', $this->get_state_options( true ), 'filter_state', array( 'onchange' => 'this.form.submit();' ), 'value', 'text', $this->state->get( 'filter.state' ) ); ?>
    </div>


    <div class="btn-group pull-right hidden-phone">
        <label for="limit" class="element-invisible"><?php
 echo JText::_( 'JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC' ); ?></label>
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
</div>

<div id="j-main-container" class="clearfix">
    <table class="table table-striped" id="urls_list">
        <thead>
            <tr>
                <th class="hidden-phone" style="width:1%;">
                    <input type="checkbox" name="checkall-toggle" value=""
                        title="<?php
 echo htmlspecialchars( JText::_( 'JGLOBAL_CHECK_ALL' ) ); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="url">
                    <?php
 echo JHtml::_( 'grid.sort', 'AIMY_SM_URL_LBL', 'a.url', $list_dir, $list_order ); ?>
                </th>
                <th class="state">
                    <?php
 echo JHtml::_( 'grid.sort', 'AIMY_SM_STATE_LBL', 'a.state', $list_dir, $list_order ); ?>
                </th>
                <th class="priority">
                    <?php
 echo JHtml::_( 'grid.sort', 'AIMY_SM_PRIO_LBL', 'a.priority', $list_dir, $list_order ); ?>
                </th>
                <th class="mtime hidden-phone">
                    <?php
 echo JHtml::_( 'grid.sort', 'AIMY_SM_MTIME_LBL', 'a.mtime', $list_dir, $list_order ); ?>
                </th>
                <th class="changefreq hidden-phone">
                    <?php
 echo JHtml::_( 'grid.sort', 'AIMY_SM_CHANGEFREQ_LBL', 'a.changefreq', $list_dir, $list_order ); ?>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $this->items as $i => $item ) : ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center hidden-phone">
                    <?php
 echo JHtml::_( 'grid.id', $i, $item->id ); ?>
                </td>
                <td class="url nowrap has-context">
                    <?php
 if ( $this->allow_edit ) { echo '<a href="' . JRoute::_( 'index.php?option=com_aimysitemap&' . 'task=url.edit&' . JSession::getFormToken() . '=1&' . 'id=' . (int) $item->id ) . '">'; } else { echo '<tt>'; } echo htmlspecialchars( $item->url, ENT_COMPAT, 'UTF-8', false ); echo $this->allow_edit ? '</a>' : '</tt>'; ?>
                    <?php if ( ! empty( $item->title ) ) : ?>
                    <div class="title hidden-phone">
                        <small><?php
 echo JText::_( 'AIMY_SM_TITLE_LBL' ), ': "', htmlspecialchars( $item->title, ENT_COMPAT, 'UTF-8', false ), '"'; ?></small>
                    </div>
                    <?php endif; ?>
                </td>


                <td class="state" data-initial-state="<?php
 echo $item->state; ?>">
                    <?php
 if ( $this->allow_edit ) { echo '<a href="', JRoute::_( 'index.php?option=com_aimysitemap&' . 'task=url.toggle_state&' . JSession::getFormToken() . '=1&' . 'id=' . (int) $item->id ), '" ', 'title="', htmlspecialchars( JText::_( $item->state != 1 ? 'AIMY_SM_ACTIVATE_LBL' : 'AIMY_SM_DEACTIVATE_LBL' ) ), '" ', 'data-title-activate="', JText::_( 'AIMY_SM_ACTIVATE_LBL' ), '" ', 'data-title-deactivate="', JText::_( 'AIMY_SM_DEACTIVATE_LBL' ), '"', '>'; } echo '<span class="aimy-icon-', ( $item->state == 1 ? 'ok' : 'cancel' ), ( $this->allow_edit ? ' btn btn-micro' : '' ), '"></span>'; if ( $this->allow_edit ) { echo '</a>'; } ?>
                </td>


                <td class="priority">
                <?php
 echo JHtml::_( 'select.genericlist', $this->get_priority_options(), 'priority', $genericlist_opts, 'value', 'text', number_format( $item->priority, 1 ), "priority-$i" ); ?>
                </td>
                <td class="mtime hidden-phone">
                    <?php
 if ( $item->mtime == 0 ) { echo "&mdash;"; } else { echo date( JText::_( 'AIMY_SM_DATE_FMT' ), $item->mtime ); } ?>
                </td>
                <td class="changefreq hidden-phone">
                <?php
 echo JHtml::_( 'select.genericlist', $this->get_changefreq_options(), 'changefreq', $genericlist_opts, 'value', 'text', $item->changefreq, "changefreq-$i" ); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php
 echo 6; ?>" class="center">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php
 echo $list_order; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php
 echo $list_dir; ?>" />
    <?php echo JHtml::_( 'form.token' ); ?>
</div><!-- /#j-main-container -->
</form>


<?php if ( empty( $this->items ) ) : ?>

<div class="clearfix"></div>
<div class="padded-tb">
    <a class="btn btn-lg btn-primary"
        href="index.php?option=com_aimysitemap&amp;view=crawl"
        title="<?php
 echo htmlspecialchars( JText::_( 'AIMY_SM_CRAWL_DSC' ) ); ?>"><?php
 echo JText::_( 'AIMY_SM_CRAWL_LBL' ); ?></a>
</div>

<?php endif; ?>

</div>

<?php
 include( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/helpers/footer.php' ); 
