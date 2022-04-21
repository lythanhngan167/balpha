<?php
/*
 * Copyright (c) 2018-2020 Aimy Extensions, Netzum Sorglos Software GmbH
 *
 * https://www.aimy-extensions.com/
 *
 * License: GNU GPLv2, see LICENSE.txt within distribution and/or
 *          https://www.aimy-extensions.com/software-license.html
 */
 defined( '_JEXEC' ) or die(); abstract class AimySitemapCompatHelper { static public function getJoomlaMajorVersion() { return substr( strVal( JVERSION ), 0, 1 ); } static public function isJoomla4() { return ( self::getJoomlaMajorVersion() == '4' ); } static public function getKnownLanguages() { if ( self::isJoomla4() ) { require_once( JPATH_ROOT . '/libraries/src/Language/LanguageHelper.php' ); return Joomla\CMS\Language\LanguageHelper::getKnownLanguages(); } $jlang = JFactory::getLanguage(); return $jlang->getKnownLanguages(); } static public function getJHtmlBootstrapMethodName( $s ) { if ( self::isJoomla4() ) { switch ( $s ) { case 'startPane': return 'bootstrap.startTabSet'; case 'endPane': return 'bootstrap.endTabSet'; case 'addPanel': return 'bootstrap.addTab'; case 'endPanel': return 'bootstrap.endTab'; } } return 'bootstrap.' . $s; } static public function addInlineJavascript( $s ) { return JFactory::getDocument()->addScriptDeclaration( $s ); } static public function loadFramework( $s ) { $n = $s; switch ( $s ) { case 'jquery': $n = 'jquery.framework'; break; } return JHtml::_( $n ); } } 
