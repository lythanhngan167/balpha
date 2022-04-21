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
 defined( '_JEXEC' ) or die(); try { JHtml::_( version_compare( JVERSION, '3.3.0', 'lt' ) ? 'behavior.framework' : 'behavior.core' ); } catch ( Exception $e ) { } $i18n = array( 'notifying' => JText::_( 'AIMY_SM_NOTIFYING' ), 'dont_close' => JText::_( 'AIMY_SM_MSG_DONT_CLOSE' ) ); if ( $this->allow_notify ) { AimySitemapCompatHelper::addInlineJavascript( 'jQuery(document).ready(function()' . '{' . 'var cfg  = ' . json_encode( $this->ping_cfg ) . ';' . 'var i18n = ' . json_encode( $i18n ) . ';' . ( ! $this->has_sitemap_xml ? 'jQuery( "#toolbar-tree-2 button" )' . '.prop( "disabled", true );' : '' ) . 'Joomla.submitbutton = function( task )' . '{' . 'if ( task == "notify.ping" )' . '{' . 'jQuery( "#toolbar-tree-2 button" )' . '.prop( "disabled", true );' . 'jQuery( "#notify-hint" ).html(' . 'jQuery( "<h2></h2>" ).text( i18n.notifying )' . ')' . '.append(' . 'jQuery( "<p></p>" ).append(' . 'jQuery( "<strong></strong>" )' . '.text( i18n.dont_close )' . ')' . ');' . 'for ( var se in cfg.ses )' . '{' . 'AimySitemapPing(' . 'se,' . '"' . JSession::getFormToken() . '",' . '"#ping"' . ');' . '}' . 'var checkDone; checkDone = function()' . '{' . 'if ( jQuery( "div.notify-task" ).length == ' . 'jQuery( "div.notify-task[data-done=\'1\']" ).length )' . '{' . 'jQuery( "#notify-hint > p" ).fadeOut( 1200 );' . '}' . 'else' . '{' . 'window.setTimeout( checkDone, 1000 );' . '}' . '};' . 'checkDone();' . 'return false;' . '}' . '};' . '});' ); } ?>

<div id="j-main-container" class="j-main-container aimy-main clearfix">

<?php if ( $this->allow_notify ) : ?>


<form action="<?php
 echo JRoute::_( 'index.php?option=com_aimysitemap&view=notify' ); ?>" method="post" name="adminForm" id="adminForm">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_( 'form.token' ); ?>
</form>


<div id="notify-hint">
    <h1><?php echo JText::_( 'AIMY_SM_NOTIFY_HINT_HEADING' ); ?></h1>
    <p><?php echo JText::_( 'AIMY_SM_NOTIFY_HINT_TEXT' ); ?></p>
</div>


<div id="ping"></div>

<?php endif; ?>

</div>

<?php
 include( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/helpers/footer.php' ); 
