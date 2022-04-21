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
 defined( '_JEXEC' ) or die(); require_once( JPATH_COMPONENT . '/helpers/rights.php' ); require_once( JPATH_COMPONENT . '/helpers/message.php' ); jimport( 'joomla.filesystem.file' ); class AimySitemapControllerRobotsTxt extends JControllerAdmin { public function save() { JSession::checkToken() or jexit( JText::_( 'INVALID TOKEN' ) ); $rights = AimySitemapRightsHelper::getRights(); if ( ! $rights->get( 'aimysitemap.write' ) ) { jexit( JText::_( 'JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN' ) ); } $msg = new AimySitemapMessageHelper(); $app = JFactory::getApplication(); $txt = $app->input->getString( 'robotstxt', '' ); $path = JPATH_ROOT . DIRECTORY_SEPARATOR . 'robots.txt'; if ( JFile::write( $path, $txt ) === false ) { $msg->error( JText::_( 'AIMY_SM_ROBOTSTXT_FAILED_TO_WRITE' ) ); } else { $msg->message( JText::_( 'AIMY_SM_ROBOTSTXT_WRITTEN' ) ); } $this->setRedirect( JRoute::_( 'index.php?option=com_aimysitemap&view=robotstxt', false ) ); } } 
