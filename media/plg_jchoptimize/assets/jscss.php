<?php

/**
 * JCH Optimize - Joomla! plugin to aggregate and minify external resources for 
 *   optmized downloads
 * @author Samuel Marshall <sdmarshall73@gmail.com>
 * @copyright Copyright (c) 2010 Samuel Marshall. All rights reserved.
 * @license GNU/GPLv3, See LICENSE file 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * If LICENSE file missing, see <http://www.gnu.org/licenses/>. 
 * 
 * This plugin includes other copyrighted works. See individual 
 * files for details.
 */
//$start = microtime(true);

if(!defined('_JEXEC'))
{
	define('_JEXEC', 1);
}

defined('_JEXEC') or die('Restricted access');

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/plugins/system/jch_optimize/bootstrap.php';

jchoptimize_class_autoload('JchOptimize\\Core\\Output');

JchOptimize\Core\Output::getCombinedFile();
