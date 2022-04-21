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
 defined( '_JEXEC' ) or die(); class AimySitemapMessageHelper { private $app = null; public function __construct() { $this->app = JFactory::getApplication(); } public function error( $msg ) { return $this->queue( $msg, 'error' ); } public function warning( $msg ) { return $this->queue( $msg, 'warning' ); } public function notice( $msg ) { return $this->queue( $msg, 'notice' ); } public function message( $msg ) { return $this->queue( $msg, 'message' ); } public function queue( $msg, $type = 'message' ) { return $this->app->enqueueMessage( $msg, $type ); } } 
