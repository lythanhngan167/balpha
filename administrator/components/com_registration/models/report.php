<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Registration records.
 *
 * @since  1.6
 */
class RegistrationModelReport extends \Joomla\CMS\MVC\Model\ListModel
{


/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	public function getGroupLandingpage($from_date,$to_date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('from_landingpage,count(*) as quantity');
		//$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('from_landingpage') . " != ''");
		$query->where($db->quoteName('state') . " = 1");
		$query->where("created_date >= '" . $from_date . "'");
		$query->where("created_date <= '" . $to_date . "'");
		$query->where("created_by = 0");
		$query->where("(status = 'converted')");
		$query->group('from_landingpage');
		//$query->order('id DESC');
		$query->setLimit(0);
		//echo $query->__toString();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getGroupSource($from_date,$to_date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('utm_sourceonly,count(*) as quantity');
		//$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('utm_sourceonly') . " != ''");
		$query->where($db->quoteName('state') . " = 1");
		$query->where("created_date >= '" . $from_date . "'");
		$query->where("created_date <= '" . $to_date . "'");
		$query->where("created_by = 0");
		$query->where("(status = 'converted')");
		$query->group('utm_sourceonly');
		//$query->order('id DESC');
		$query->setLimit(0);
		//echo $query->__toString();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getGroupMedium($from_date,$to_date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('utm_mediumonly,count(*) as quantity');
		//$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('utm_mediumonly') . " != ''");
		$query->where($db->quoteName('state') . " = 1");
		$query->where("created_date >= '" . $from_date . "'");
		$query->where("created_date <= '" . $to_date . "'");
		$query->where("created_by = 0");
		$query->where("(status = 'converted')");
		$query->group('utm_mediumonly');
		//$query->order('id DESC');
		$query->setLimit(0);
		//echo $query->__toString();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function getGroupCompain($from_date,$to_date){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('utm_compainonly,count(*) as quantity');
		//$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('utm_compainonly') . " != ''");
		$query->where($db->quoteName('state') . " = 1");
		$query->where("created_date >= '" . $from_date . "'");
		$query->where("created_date <= '" . $to_date . "'");
		$query->where("created_by = 0");
		$query->where("(status = 'converted')");
		$query->group('utm_compainonly');
		//$query->order('id DESC');
		$query->setLimit(0);
		//echo $query->__toString();
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}


}
