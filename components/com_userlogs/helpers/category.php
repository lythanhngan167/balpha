<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Userlogs
 * @author     Minh Thái Thi <thiminhthaichoigame@gmail.com>
 * @copyright  2020 Minh Thái Thi
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Categories\Categories;
/**
 * Content Component Category Tree
 *
 * @since  1.6
 */

class UserlogsUserlogsCategories extends Categories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   11.1
	 */
	public function __construct($options = array())
	{
		$options['table'] = '#__userlogs';
		$options['extension'] = 'com_userlogs.userlogs';
		parent::__construct($options);
	}
}
