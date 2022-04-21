<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Registrations list controller class.
 *
 * @since  1.6
 */
class RegistrationControllerRegistrations extends RegistrationController
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional
	 * @param   array   $config  Configuration array for model. Optional
	 *
	 * @return object	The model
	 *
	 * @since	1.6
	 */
	public function &getModel($name = 'Registrations', $prefix = 'RegistrationModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));

		return $model;
	}

	public function duplicateBiznet(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__registration'));
		$query->where($db->quoteName('project_id') . " = 22");
		$query->where($db->quoteName('is_exist') . " = 1");
		$query->where($db->quoteName('created_date') . " >= '2021-07-10 15:17:00'");
		$query->order('id DESC');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		$arrRegis = array();
		foreach ($result as $key => $regis) {
			$arrRegis[] = $regis->phone;
		}
		echo count($arrRegis);
		echo "\n";
		$strRegis = implode(",",$arrRegis);
    print_r($strRegis);
		die;
	}


}
