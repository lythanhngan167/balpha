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
 defined( '_JEXEC' ) or die(); require_once( JPATH_COMPONENT . '/helpers/config.php' ); require_once( JPATH_COMPONENT . '/helpers/compat.php' ); class AimySitemapController extends JControllerLegacy { protected $default_view = 'dashboard'; public function display( $cachable = false, $urlparams = false ) { $view = $this->input->get( 'view', $this->default_view ); $layout = $this->input->get( 'layout', 'default' ); $id = $this->input->getInt( 'id' ); if ( $view == 'urls' && $layout == 'edit' && ! $this->checkEditId( 'com_aimysitemap.edit.urls', $id ) ) { $this->setError( JText::sprintf( 'JLIB_APPLICATION_ERROR_UNHELD_ID', $id ) ); $this->setMessage( $this->getError(), 'error' ); $this->setRedirect( JRoute::_( 'index.php?option=com_aimysitemap&view=urls', false ) ); return false; } if ( ! AimySitemapConfigHelper::get_once( 'default_priority', 0 ) ) { JFactory::getApplication()->enqueueMessage( JText::sprintf( 'AIMY_SM_MSG_NOT_CONFIGURED', JRoute::_( 'index.php?option=com_config&' . 'view=component&' . 'component=com_aimysitemap' ) ), 'warning' ); } $jdoc = JFactory::getDocument(); $mediaUrl = sprintf( '%s/media/com_aimysitemap/', JURI::root( true ) ); $jdoc->addStylesheet( $mediaUrl . 'backend.css?r=27.3' ); $jdoc->addStylesheet( $mediaUrl . 'backend-j' . AimySitemapCompatHelper::getJoomlaMajorVersion() . '.css?r=27.3' ); AimySitemapCompatHelper::loadFramework( 'jquery' ); parent::display(); return $this; } } 
