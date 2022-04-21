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
 defined( '_JEXEC' ) or die(); abstract class AimySitemapKVStore { const KVTABLE = '#__aimysitemap_kvstore'; static public function get( $k ) { $db = JFactory::getDbo(); $q = $db->getQuery( true ); $q->select( $db->quoteName( 'v' ) ) ->from( self::KVTABLE ) ->where( $db->quoteName( 'k' ) . ' = ' . $db->quote( $k ) ); $db->setQuery( $q ); $res = $db->loadRow(); if ( is_array( $res ) and count( $res ) === 1 ) { return unserialize( $res[0] ); } return ''; } static public function set( $k, $v ) { $db = JFactory::getDbo(); $o = new JObject(); $o->k = $k; $o->v = serialize( $v ); try { $db->insertObject( self::KVTABLE, $o ); } catch ( Exception $e ) { return $db->updateObject( self::KVTABLE, $o, 'k' ); } return true; } static public function delete( $k ) { $db = JFactory::getDbo(); $q = $db->getQuery( true ); $q->delete( $db->quoteName( self::KVTABLE ) ) ->where( $db->quoteName( 'k' ) . ' = ' . $db->quote( $k ) ); $db->setQuery( $q ); $db->execute(); } } 
