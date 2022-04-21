<?php
/*
 * Copyright (c) 2017-2020 Aimy Extensions, Netzum Sorglos Software GmbH
 * Copyright (c) 2015-2017 Aimy Extensions, Lingua-Systems Software GmbH
 *
 * https://www.aimy-extensions.com/
 *
 * License: GNU GPLv2, see LICENSE.txt within distribution and/or
 *          https://www.aimy-extensions.com/software-license.html
 */
 defined( '_JEXEC' ) or die(); abstract class AimySitemapLogger { static private $ready = false; static public function init() { jimport( 'joomla.log.log' ); JLog::addLogger( array( 'text_file' => self::get_log_name(), 'text_file_path' => self::get_dir_path(), 'text_entry_format' => '{DATE} {TIME} {PRIORITY} {MESSAGE}' ), JLog::ALL, 'aimysitemap' ); self::$ready = true; } static public function error( $msg ) { if ( ! self::$ready ) { self::init(); } JLog::add( $msg, JLog::ERROR, 'aimysitemap' ); } static public function debug( $msg ) { if ( ! defined( 'JDEBUG' ) or ! JDEBUG ) { return; } if ( ! self::$ready ) { self::init(); } JLog::add( $msg, JLog::DEBUG, 'aimysitemap' ); } static public function get_dir_path() { $dflt = JPATH_ROOT . DIRECTORY_SEPARATOR . 'logs'; try { return JFactory::getConfig()->get( 'log_path', $dflt ); } catch ( Exception $e ) { } return $dflt; } static public function get_log_name() { return 'aimysitemap.php'; } static public function get_path() { return self::get_dir_path() . DIRECTORY_SEPARATOR . self::get_log_name(); } } 
