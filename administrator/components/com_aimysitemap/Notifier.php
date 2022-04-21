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
 defined( '_JEXEC' ) or die(); require_once( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/HttpClient.php' ); require_once( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/Uri.php' ); require_once( JPATH_ADMINISTRATOR . '/components/com_aimysitemap/helpers/config.php' ); abstract class AimySitemapNotifier { static $ses = array( 'google' => 'http://www.google.com/webmasters/sitemaps/ping?sitemap=%s', 'bing' => 'http://www.bing.com/ping?sitemap=%s' ); static public function is_enabled( $se ) { $cfg = new AimySitemapConfigHelper(); return $cfg->get( 'notify_' . strtolower( $se ), false ); } static public function ping_all_enabled() { $rv = true; foreach ( static::$ses as $se => $ping_url ) { if ( self::is_enabled( $se ) ) { if ( ! self::ping( $se ) ) { $rv = false; } } } return $rv; } static public function ping( $se ) { $se = strtolower( $se ); if ( ! isset( self::$ses[ $se ] ) ) { return false; } $cfg = new AimySitemapConfigHelper(); $sm_path = $cfg->get( 'xml_path', '/sitemap.xml' ); $sm_url = JUri::root() . $sm_path; $req_url = sprintf( self::$ses[ $se ], urlencode( $sm_url ) ); $u = new AimySitemapUri( $req_url ); $resp = null; AimySitemapHttpClient::set_ua_name( 'AimySitemapNotifier/27.3' ); $resp = AimySitemapHttpClient::get_url( $u ); if ( is_array( $resp ) && isset( $resp[ 'head' ] ) && $resp[ 'head' ][ 'code' ] == '200' ) { AimySitemapLogger::debug( "Notifier: Sending ping to $se: OK" ); return true; } AimySitemapLogger::debug( "Notifier: Sending ping to $se: FAILED ($req_url)" ); return false; } } 
