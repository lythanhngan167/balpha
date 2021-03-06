<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die('Restricted access');

class CTableLike extends JTable
{

	// Tables' fileds
	var $id = null;
	var $element = null;
	var $uid = null;
	var $like = null;
	var $dislike = null;
	var $reaction_ids = null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__community_likes', 'id', $db );
	}

	public function store($updateNulls=null)
	{
		return parent::store();
	}

	public function loadInfo($element, $itemId)
	{
        if (!$itemId) {
            return;
        }

		$db	= JFactory::getDBO();

		$query = 'SELECT * FROM ' . $db->quoteName('#__community_likes') . ' '
			    . 'WHERE ' . $db->quoteName('element') . '=' . $db->Quote($element) . ' '
			    . 'AND ' . $db->quoteName('uid') . '=' . $db->Quote($itemId);

		$db->setQuery($query);

		$result = $db->loadObject();

		if ($result) {
			if (!$result->reaction_ids) {
				$result->reaction_ids = preg_replace('/\d+/', '1', $result->like);

				$query = 'UPDATE ' . $db->quoteName('#__community_likes')
					. ' SET ' . $db->quoteName('reaction_ids') . ' = ' . $db->Quote($result->reaction_ids)
					. ' WHERE ' . $db->quoteName('element') . '=' . $db->Quote($element)
			    	. ' AND ' . $db->quoteName('uid') . '=' . $db->Quote($itemId);

			    $db->setQuery($query);
            	$db->execute();
			}

			$this->bind($result);
		}

		return;
	}
}