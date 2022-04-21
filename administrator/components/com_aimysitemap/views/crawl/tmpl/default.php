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
 defined( '_JEXEC' ) or die(); try { JHtml::_( version_compare( JVERSION, '3.3.0', 'lt' ) ? 'behavior.framework' : 'behavior.core' ); } catch ( Exception $e ) { } $i18n = array( 'pro_only' => JText::_( 'AIMY_SM_MSG_FEATURE_PRO_ONLY' ), 'linkcheck' => JText::_( 'AIMY_SM_MSG_DO_LINKCHECK' ), 'crawling' => JText::_( 'AIMY_SM_CRAWLING' ), 'dont_close' => JText::_( 'AIMY_SM_MSG_DONT_CLOSE' ), 'manage' => JText::_( 'AIMY_SM_MSG_DO_MANAGE' ), 'errors' => JText::_( 'AIMY_SM_MSG_ERRORS' ), 'added' => JText::_( 'AIMY_SM_MSG_CRAWL_ADDED' ), 'deleted' => JText::_( 'AIMY_SM_MSG_CRAWL_DELETED' ), 'updated' => JText::_( 'AIMY_SM_MSG_CRAWL_UPDATED' ), 'no_updates' => JText::_( 'AIMY_SM_MSG_NO_UPDATES' ), 'finished' => JText::_( 'AIMY_SM_MSG_CRAWL_FINISHED' ), 'init' => JText::_( 'AIMY_SM_MSG_CRAWL_INIT' ), 'abort_first' => JText::_( 'AIMY_SM_MSG_CRAWL_ABORT_FIRST' ), 'retry' => JText::_( 'AIMY_SM_MSG_CRAWL_RETRY' ), 'userabort' => JText::_( 'AIMY_SM_MSG_CRAWL_USERABORT' ) ); if ( $this->allow_crawl ) { AimySitemapCompatHelper::addInlineJavascript( 'jQuery(document).ready(function()' . '{' . 'jQuery( "#toolbar-unpublish button" )' . '.prop( "disabled", true );' . 'Joomla.submitbutton = function( task )' . '{' . 'if ( task == "crawl.crawl" )' . '{' . 'g_aimysitemap_abort = false;' . 'jQuery( "#toolbar-tree-2 button" )' . '.prop( "disabled", true );' . 'jQuery( "#toolbar-unpublish button" )' . '.prop( "disabled", false );' . 'jQuery( "#crawl-hint" ).fadeOut();' . 'AimySitemapCrawl(' . json_encode( $i18n ) . ',' . '"' . JSession::getFormToken() . '",' . '"#crawling",' . 'function( success, msg )' . '{' . 'jQuery( "#toolbar-unpublish button" )' . '.prop( "disabled", true );' . 'jQuery( "#toolbar-tree-2 button" )' . '.prop( "disabled", false );' . ( defined( 'JDEBUG' ) && JDEBUG ? 'jQuery( "#dl_btn_cnt" ).fadeIn( "slow" );' : '' ) . '}' . ');' . 'return false;' . '}' . 'else if ( task == "crawl.abort" )' . '{' . 'g_aimysitemap_abort = true;' . 'jQuery( "#toolbar-unpublish button" )' . '.prop( "disabled", true );' . 'jQuery( "#toolbar-tree-2 button" )' . '.prop( "disabled", false );' . 'return false;' . '}' . '}' . ( defined( 'JDEBUG' ) && JDEBUG && ! is_readable( AimySitemapLogger::get_path() ) ? 'jQuery( "#dl_btn_cnt" ).hide();' : '' ) . '});' ); } ?>

<div id="j-main-container" class="j-main-container aimy-main clearfix">

<?php if ( $this->allow_crawl ) : ?>

<form action="<?php
 echo JRoute::_( 'index.php?option=com_aimysitemap&view=crawl' ); ?>" method="post" name="adminForm" id="adminForm">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_( 'form.token' ); ?>
</form>

<div id="crawl-hint">
    <h1><?php echo JText::_( 'AIMY_SM_CRAWL_HINT_HEADING' ); ?></h1>
    <p><?php echo JText::_( 'AIMY_SM_CRAWL_HINT_TEXT' ); ?></p>
</div>

<div id="crawling"></div>

<?php endif; ?>

<?php if ( defined( 'JDEBUG' ) && JDEBUG ) : ?>
<p id="dl_btn_cnt" style="padding:6px 0;">
    <a class="btn btn-lg btn-warning"
       href="index.php?option=com_aimysitemap&task=crawl.get_log&<?php
 echo JSession::getFormToken(); ?>=1"
       target="_blank"><?php
 echo JText::_( 'AIMY_SM_DOWNLOAD_LOG' ); ?></a>
<?php endif; ?>

</div>

<?php
 include( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/helpers/footer.php' ); 
