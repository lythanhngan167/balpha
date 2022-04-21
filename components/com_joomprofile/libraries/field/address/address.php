<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldAddress extends JoomprofileLibField
{
	public $name = 'address';
	public $location = __DIR__;
	
	public function format($field, $value, $userid, $on)
	{
		$value = parent::format($field, $value, $userid, $on);

		$address = $this->__getAddress($field->id, $userid);

		if($address){
			$old_md5 = $address->md5;
			foreach($address as $key => $val){
				$address->$key = isset($value[$key]) ? $value[$key] : '';
			}		
			$address->md5 = $this->__getMd5($address);

			if($old_md5 != $address->md5 || empty($address->latitude) || empty($address->longitude)){
				// get latitude and longitude
				list($address->latitude, $address->longitude) = $this->__getGeocode($address, $field->params);
			}

			$this->__updateAddress($field->id, $userid, $address);
			$value = $address->id;
		}
		else{
			$address = (object) $value;
			$address->md5 = $this->__getMd5($address);
			list($address->latitude, $address->longitude) = $this->__getGeocode($address, $field->params);
			$value = $this->__insertAddress($field->id, $userid, $address);
		}

		return $value;
	}

	public function getUserEditHtml($fielddata, $value, $userid)
	{		
		$value = $this->__getAddress($fielddata->id, $userid);
		return parent::getUserEditHtml($fielddata, $value, $userid);
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
		$value = '';
		$address = $this->__getAddress($fielddata->id, $user_id);
		if($address){
			$path 		= $this->location.'/templates';
			$template 	= new JoomprofileTemplate(array('path' => $path));				
			$template->set('fielddata', $fielddata)->set('address', $address);
			return $template->render('field.'.$this->name.'.view');			
		}
		
		return $value;
	}

	public function getMiniProfileViewHtml($fielddata, $value, $user_id)
	{
		$value = '';
		$address = $this->__getAddress($fielddata->id, $user_id);
		if($address){
			$path 		= $this->location.'/templates';
			$template 	= new JoomprofileTemplate(array('path' => $path));				
			$template->set('fielddata', $fielddata)->set('address', $address);
			return $template->render('field.'.$this->name.'.miniprofileview');			
		}
		
		return $value;
	}

	private function __getGeocode($address, $params = array())
	{
		$address_str = $address->line.', '.$address->city.', '.$address->state.', '.$address->zipcode.', '.$address->country;
		$address_str = preg_replace('/\s/', ' ', $address_str);
		$address_str = urlencode($address_str);
		$url = "http://maps.google.com/maps/api/geocode/json?address=".$address_str."&sensor=false&region=".preg_replace('/\s/', ' ', $address->country);
		if(isset($params['google_key']) && !empty($params['google_key'])){
			$url .= '&key='.$params['google_key'];
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		if($response_a->status == 'ZERO_RESULTS' || empty($response_a->results)){
			return array('', '');
		}

		$lat 	= $response_a->results[0]->geometry->location->lat;		
		$long 	= $response_a->results[0]->geometry->location->lng;
		return array($lat, $long);
	}

	private function __getMd5($address)
	{
		$str = $address->line.$address->city.$address->zipcode.$address->state.$address->country;		
		$str = preg_replace('/\s/', '', $str);
		return md5($str);
	}

	private function __updateAddress($fieldid, $userid, $address)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->update('`#__joomprofile_address`')
				->set('`line` = '.$db->quote($address->line))
				->set('`city` = '.$db->quote($address->city))
				->set('`zipcode` = '.$db->quote($address->zipcode))
				->set('`state` = '.$db->quote($address->state))
				->set('`country` = '.$db->quote($address->country))
				->set('`latitude` = '.$db->quote($address->latitude))
				->set('`longitude` = '.$db->quote($address->longitude))
				->set('`md5` = '.$db->quote($address->md5))
				->where('`field_id` = '.$db->quote($fieldid))
				->where('`user_id` = '.$db->quote($userid));
		$db->setQuery($query);
		return $db->query();
	}

	private function __insertAddress($fieldid, $userid, $address)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->insert('`#__joomprofile_address`')
				->set('`field_id` = '.$db->quote($fieldid))
				->set('`user_id` = '.$db->quote($userid))
				->set('`line` = '.$db->quote($address->line))
				->set('`city` = '.$db->quote($address->city))
				->set('`zipcode` = '.$db->quote($address->zipcode))
				->set('`state` = '.$db->quote($address->state))
				->set('`country` = '.$db->quote($address->country))
				->set('`latitude` = '.$db->quote($address->latitude))
				->set('`longitude` = '.$db->quote($address->longitude))
				->set('`md5` = '.$db->quote($address->md5));
		$db->setQuery($query);
		if($db->query()){
			return $db->insertid();
		}

		return false;
	}

	private function __getAddress($fieldid, $userid)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
				->from('`#__joomprofile_address`')
				->where('`field_id` = '.$db->quote($fieldid))
				->where('`user_id` = '.$db->quote($userid));

		$db->setQuery($query);
		return $db->loadObject();
	}

	public function buildSearchQuery($fielddata, $query, $value)
	{		
		// Sequence
		// 0 => address
		// 1 => distance
		// 2 => unit/range
		// 3 => formatted
		// 4 => latitude
		// 5 => longitude
		$value = array_values($value);
		// minimum 2 values must be set to search
		if(!is_array($value) || count($value)< 2 || !isset($value[0]) || empty($value[0])){
			return false;
		}

        $db = JoomprofileHelperJoomla::getDBO();

		if (!empty($value[4]) && !empty($value[5])) {
            $latitude  = $db->escape($value[4]);
            $longitude = $db->escape($value[5]);
        } elseif(!empty($value[0])) {
			//need to get data from api
			$address = new stdclass();
			$address->line = $db->escape($value[0]);
			$address->city = '';
			$address->state = '';
			$address->country = '';
			$address->zipcode = '';

			list($latitude, $longitude) = $this->__getGeocode($address, $fielddata->params);
		} else {
		    return true;
        }

		if($value[2] == 'mile'){
			$range   = '3959'; // in mile
		}else {
			$range  = '6371';  // in km
		}

		$distance = !isset($value[1]) ? $fielddata->params['default_distance'] :  $db->escape($value[1]);

		$query->clear('from');		
		$sql = " (SELECT `user_id`, `field_id`,
    				( ".$range." * acos( cos( radians($latitude) ) 
                   					* cos( radians( `latitude` ) ) 
                   					* cos( radians( `longitude` ) 
                       				- radians($longitude) ) 
                   			+ sin( radians($latitude) ) 
                   			* sin( radians( latitude ) ) 
                 	)
   				) AS distance 
				FROM #__joomprofile_address 
				where `field_id` = $fielddata->id
				HAVING distance < ".$distance." ) as tbl{$fielddata->id}";

		$query->from($sql);		
		return true;
	}					

	public function getSearchHtml($fielddata, $value, $onlyFieldHtml = false)
	{
		if(!is_array($value)){
			$value = array();
		}

		$value = array_values($value);
		return parent::getSearchHtml($fielddata, $value, $onlyFieldHtml);
	}

	public function getAssets($on, $field)
	{
		if($on == self::ON_SEARCH){
			// do not load multiple times
			static $loaded = false;
			if($loaded == true){
				return '';
			}
			$loaded = true;

			$path 		= $this->location.'/templates';
			$template 	= new JoomprofileTemplate(array('path' => $path));
			$template->set('field', $field->toArray());
				
			return $template->render('field.'.$this->name.'.search.assets');
		}

		return parent::getAssets($on);
	}

	public function getAppliedSearchHtml($fieldObj, $values)
	{
		$values = array_values($values);
		return $values[1].' '.JText::_('COM_JOOMPROFILE_SEARCH_ADDRES_KMS').' '.
				' '.JText::_('COM_JOOMPROFILE_FROM').' '.$values[0];
	}

	public function isValueSearchable($fielddata, $value)
	{
		if(!is_array($value)){
			return false;
		}

		return true;
	}

	public function getExportValue($fielddata, $value, $user_id)
	{
		$address = $this->__getAddress($fielddata->id, $user_id);		
		$ret = array();
		if(@$fielddata->params['show_line']){
			$ret[] = !empty($address->line) ? $address->line : '';
		}
		if(@$fielddata->params['show_city']){
			$ret[] = !empty($address->city) ? $address->city : '';
		}
		if(@$fielddata->params['show_zipcode']){
			$ret[] = !empty($address->zipcode) ? $address->zipcode : '';
		}
		if(@$fielddata->params['show_state']){
			$ret[] = !empty($address->state) ? $address->state : '';
		}
		if(@$fielddata->params['show_country']){
			$ret[] = !empty($address->country) ? $address->country : '';
		}

		return $ret;
	}

	public function getExportColumn($fielddata)
	{
		$ret = array();
		if(@$fielddata->params['show_line']){
			$ret[] = $fielddata->title.'::Line';
		}
		if(@$fielddata->params['show_city']){
			$ret[] = $fielddata->title.'::City';
		}
		if(@$fielddata->params['show_zipcode']){
			$ret[] = $fielddata->title.'::Zipcode';
		}
		if(@$fielddata->params['show_state']){
			$ret[] = $fielddata->title.'::State';
		}
		if(@$fielddata->params['show_country']){
			$ret[] = $fielddata->title.'::Country';
		}

		return $ret;	
	}
}
