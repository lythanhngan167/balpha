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
 defined( '_JEXEC' ) or die(); jimport( 'joomla.session.session' ); require_once( JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'rights.php' ); require_once( JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'Notifier.php' ); class AimySitemapControllerNotify extends JControllerAdmin { public function ping_ajax() { JSession::checkToken() or jexit( JText::_( 'INVALID TOKEN' ) ); header( 'Content-Type: application/json; charset=UTF-8' ); $rights = AimySitemapRightsHelper::getRights(); if ( ! $rights->get( 'aimysitemap.notify' ) ) { echo json_encode(array( 'err' => JText::_( 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' ) )); jexit( 1 ); } $jinput = JFactory::getApplication()->input; $se = $jinput->getWord( 'n', null ); if ( is_null( $se ) ) { echo json_encode(array( 'err' => 'Internal error' )); jexit( 1 ); } if ( ! AimySitemapNotifier::is_enabled( $se ) ) { echo json_encode(array( 'err' => JText::_( 'JDISABLED' ) )); jexit( 1 ); } $rv = false; try { $rv = AimySitemapNotifier::ping( $se ); } catch ( Exception $e ) { echo json_encode(array( 'err' => JText::sprintf( 'AIMY_SM_ERR_CONNECT', $e->getMessage() ) )); jexit( 1 ); } if ( $rv ) { echo json_encode(array( 'ok' => 1 )); } else { echo json_encode(array( 'err' => JText::_( 'AIMY_SM_ERR_NOTIFY' ) )); jexit( 1 ); } JFactory::getApplication()->close(); } public function ping() { JSession::checkToken() or jexit( JText::_( 'INVALID TOKEN' ) ); $rights = AimySitemapRightsHelper::getRights(); if ( ! $rights->get( 'aimysitemap.notify' ) ) { $this->setError( JText::_( 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' ) ); $this->setMessage( $this->getError(), 'error' ); $this->setRedirect( JRoute::_( 'index.php?option=com_aimysitemap&view=urls', false ) ); return false; } return true; } } 
