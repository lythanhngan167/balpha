<?php
/**
 * @package     Joomla.Admin
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
if(defined('_JEXEC')===false) die();

class com_joomprofileInstallerScript
{
	function postflight($type, $parent)
	{
		ob_start();
		$this->createImagesFolder();
		$this->installExtensions();

		$extensions 	= array();
		$extensions[] 	= array('type'=>'system', 'name'=>'joomprofile');

		$this->changeExtensionState($extensions);
		$content = ob_get_contents();
		ob_end_clean();
		return $this->_addTracking().$content;
	}

	public function installExtensions($actionPath=null,$delFolder=true)
	{
		//if no path defined, use default path
		if($actionPath==null)
			$actionPath = dirname(__FILE__).'/admin/plugins';

		//get instance of installer
		$installer =  new JInstaller();

		$extensions	= JFolder::folders($actionPath);

		//no extension to install
		if(empty($extensions))
			return true;

		//install all extensions
		foreach ($extensions as $extension)
		{
			$msg = " ". $extension . ' : Installed Successfully ';

			// Install the packages
			if($installer->install("{$actionPath}/{$extension}")==false){
				$msg = " ". $extension . ' : Installation Failed. Please try to reinstall.';
			}

			//enque the message
			JFactory::getApplication()->enqueueMessage($msg);
		}

		if($delFolder){
			$delPath = JPATH_ADMINISTRATOR.'/components/com_joomprofile/plugins';
			JFolder::delete($delPath);
		}

		return true;
	}

	function changeExtensionState($extensions = array(), $state = 1)
	{
		if(empty($extensions)){
			return true;
		}

		$db		= JFactory::getDBO();
		$query		= 'UPDATE '. $db->quoteName( '#__extensions' )
				. ' SET   '. $db->quoteName('enabled').'='.$db->Quote($state);

		$subQuery = array();
		foreach($extensions as $extension => $value){
			$subQuery[] = '('.$db->quoteName('element').'='.$db->Quote($value['name'])
				    . ' AND ' . $db->quoteName('folder').'='.$db->Quote($value['type'])
			            .'  AND `type`="plugin"  )   ';
		}

		$query .= 'WHERE '.implode(' OR ', $subQuery);

		$db->setQuery($query);
		return $db->query();
	}
	//Redirects After Installation
	function _addTracking()
	{
		
		?>
			<iframe src="http://www.function90.com/broadcast/installation.html?dom=<?php echo urlencode($_SERVER['HTTP_HOST']);?>" style="display:none;"></iframe>
		<?php
	}
	
	function update($parent)
	{
		//update field-fieldgroup mapping table for other attributes
		// like editable, visible etc
		if(!$this->alterSchema()){
			return false;
		}
		// patch for 1.1.2
		if(!$this->patchFieldgroupRegistrationColumn()){
			return false;
		}

		// patch for 1.1.6
		if(!$this->patchUsergroupTable()){
			return false;
		}

		// patch for 1.2.0
		if(!$this->patchAddressTable() && !$this->patchContentTable()){
			return false;
		}

		// patch for 1.2.2
		if(!$this->patchFulltextInFieldOption()){
			return false;
		}

		// patch for 1.3.0
		if(!$this->patchForSearchProfile()){
			return false;
		}

		//patch for image/file field validation
		if(!$this->patchFieldValidation()){
			return false;
		}

		return true;
	}
	
	function createImagesFolder()
	{
		if(!JFolder::exists(JPATH_SITE.'/images/com_joomprofile')){
			JFolder::create(JPATH_SITE.'/images/com_joomprofile');
			JFolder::create(JPATH_SITE.'/images/com_joomprofile/user');
			JFolder::create(JPATH_SITE.'/images/com_joomprofile/tmp');
		}
	}

	function alterSchema()
	{
		$db = JFactory::getDBO();
		$columns = $db->getTableColumns('#__joomprofile_field_fieldgroups');

		//considering if any one column exists out of these 4 then all the others will too 
		if(isset($columns['visible']) || isset($columns['editable']) && isset($columns['required']) && isset($columns['registration'])){
			return true;
		}
		
		$query = ' ALTER TABLE '.$db->quoteName( '#__joomprofile_field_fieldgroups')
				 .' ADD '. $db->quoteName('visible').' tinyint(1)  NOT NULL DEFAULT 1, '
				 .' ADD '. $db->quoteName('editable').' tinyint(1) NOT NULL DEFAULT 1, '
				 .' ADD '. $db->quoteName('required').' tinyint(1) NOT NULL DEFAULT 0, '
				 .' ADD '. $db->quoteName('registration').' tinyint(1) NOT NULL DEFAULT 1, '
				 .' ADD '. $db->quoteName('params').' text ';
				 
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		// add full text indexing in field_values table
		$query = 'ALTER TABLE '.$db->quoteName( '#__joomprofile_field_values').' ENGINE = MyISAM';
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		$query = 'ALTER TABLE '.$db->quoteName( '#__joomprofile_field_values')
				 .' ADD FULLTEXT INDEX `fullx_value` ('. $db->quoteName('value').')';
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		$query = 'UPDATE '.$db->quoteName( '#__joomprofile_field_values')
				 .' SET `value` = REPLACE(`value`, "media/com_joomprofile/", "images/com_joomprofile/") '
	 			 .' WHERE `value` LIKE "media/com_joomprofile/%"';	
		$db->setQuery($query); 
		if(!$db->query()){
			return false;
		}
		?><div class="alert alert-info ">Thanks for instaling Joom Profile. There are some changes in location strofing 
		files/images. We have changed the path of files/images in the database. Now you have to move the existing folder to a 
		new location. We are sorry for inconvenience. <br/><br/>
		<big>Move following folders :
		<ul class="text-warning">
			<li>Root/media/com_joomprofile/tmp =&gt; Root/images/com_joomprofile/tmp</li>
			<li>Root/media/com_joomprofile/user =&gt; Root/images/com_joomprofile/user</li>
		</ul>
		</big>
		</div><?php 

		// create table usergroup_searchfields
		$query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName( '#__joomprofile_usergroup_searchfields').' ('
			     . $db->quoteName('usergroup_searchfield_id').' int(11) NOT NULL AUTO_INCREMENT,'
			     . $db->quoteName('usergroup_id').' int(11) NOT NULL,'
			     . $db->quoteName('field_id').' int(11) NOT NULL,'
			     . $db->quoteName('ordering').' int(11) NOT NULL,'
				 .' PRIMARY KEY ('. $db->quoteName('usergroup_searchfield_id').'),'
				 .' INDEX `idx_usergroup_id` ('. $db->quoteName('usergroup_id').'),'
				 .' INDEX `idx_field_id` ('. $db->quoteName('field_id').')'
				 .') ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ';
		$db->setQuery($query);
		return $db->query();
	}

	public function patchFieldgroupRegistrationColumn()
	{
		$db = JFactory::getDBO();
		$columns = $db->getTableColumns('#__joomprofile_fieldgroup');

		//considering if any one column exists out of these 4 then all the others will too 
		if(isset($columns['registration'])){
			return true;
		}
		
		$query = ' ALTER TABLE '.$db->quoteName( '#__joomprofile_fieldgroup')
				 .' ADD '. $db->quoteName('registration').' tinyint(1) NOT NULL DEFAULT 1 ';
				 
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}
		return true;
	}

	public function patchUsergroupTable()
	{
		$db = JFactory::getDBO();
		// create table usergroup_searchfields
		$query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName( '#__joomprofile_usergroup').' ('
			     . $db->quoteName('usergroup_id').' int(11) NOT NULL,'
			     . $db->quoteName('params').' text,'
			     .' PRIMARY KEY ('. $db->quoteName('usergroup_id').')'
				 .') ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ';
		$db->setQuery($query);
		return $db->query();
	}

	public function patchAddressTable()
	{
		$db = JFactory::getDBO();
		// create table usergroup_searchfields
		$query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName( '#__joomprofile_address').' ('
			     . $db->quoteName('address_id').' int(11) NOT NULL AUTO_INCREMENT,'
			     . $db->quoteName('field_id').' int(11) NOT NULL,'
			     . $db->quoteName('user_id').' int(11) NOT NULL,'
			     . $db->quoteName('line').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('city').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('zipcode').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('state').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('country').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('latitude').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('longitude').' varchar(255) DEFAULT NULL,'
			     . $db->quoteName('md5').' varchar(255) DEFAULT NULL,'
			     .' PRIMARY KEY ('. $db->quoteName('address_id').'),'
			     .'  INDEX `idx_user_id` (`user_id`),'
			     .'  INDEX `idx_field_id` (`field_id`),'
			     .'  INDEX `idx_latitude` (`latitude`),'
			     .'  INDEX `idx_longitude` (`longitude`)'
				 .') ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ';
		$db->setQuery($query);
		return $db->query();
	}

	public function patchContentTable()
	{
		$db = JFactory::getDBO();
		// create table usergroup_searchfields
		$query = 'CREATE TABLE IF NOT EXISTS '.$db->quoteName( '#__joomprofile_content').' ('
			     . $db->quoteName('id').' int(11) NOT NULL AUTO_INCREMENT,'
			     . $db->quoteName('content').' TEXT,'
			     .' PRIMARY KEY ('. $db->quoteName('id').')'
				 .') ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ';
		$db->setQuery($query);
		return $db->query();
	}

	public function patchFulltextInFieldOption()
	{
		$db = JFactory::getDBO();
		$query = "SHOW INDEX FROM ".$db->quoteName( '#__joomprofile_fieldoption')." WHERE Key_name LIKE 'fullx_title'";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		if(!empty($result)){
			return true;;
		}

		// add full text indexing in field_values table
		$query = 'ALTER TABLE '.$db->quoteName( '#__joomprofile_fieldoption').' ENGINE = MyISAM';
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		$query = 'ALTER TABLE '.$db->quoteName( '#__joomprofile_fieldoption')
				 .' ADD FULLTEXT INDEX `fullx_title` ('. $db->quoteName('title').')';
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		return true;
	}

	public function patchForSearchProfile()
	{
		$db = JFactory::getDBO();
		$columns = $db->getTableColumns('#__joomprofile_usergroup_searchfields');

		//considering if column exists
		if(isset($columns['showOnProfile'])){
			return true;
		}

		$query = ' ALTER TABLE '.$db->quoteName( '#__joomprofile_usergroup_searchfields')
				 .' ADD '. $db->quoteName('showOnProfile').' tinyint(1) NOT NULL DEFAULT 0 ';
				 
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}

		return true;
	}

	public function patchFieldValidation()
	{
		$db 	= JFactory::getDBO();
		$query 	= "SELECT * FROM `#__joomprofile_field` WHERE `type` IN ('image', 'file')";

		$db->setQuery($query);
		$results = $db->loadObjectList();

		$error_image 			= "";
		$error_file     		= "";
		$allowed_ext    		= array();
		$supported_image_ext 		= array('bmp', 'gif', 'png', 'jpg', 'jpeg', 'jpe');

		$supported_file_ext  		=  array('bmp', 'gif', 'png', 'jpg', 'jpeg', 'jpe', 'ppt','gz', 'pdf','tar', 'tgz', 'zip', 'tiff', 'tif', 'txt', 'mpeg', 'mpg', 'mpe', 'qt', 'mov', 'avi', 'flv', 'doc', 'mp4');

		foreach ($results as $field) 
		{
			$params 		= json_decode($field->params);

			if (is_array($params->allowed_ext)) {
			    continue;
            }
			if(isset($params->restricted_ext)){
				unset($params->restricted_ext);
			}
			$data  		= explode(",", $params->allowed_ext);
				
			foreach ($data as $value) {

				$value  		= trim($value, " ");
				$value  		= trim($value, ".");
                $value          = strtolower($value);

				if($field->type == 'image'){
						if(in_array($value, $supported_image_ext)){
							$allowed_ext[]	= $value;
						}else {
							$error_image  .= $value.",";
						}
				}
				if($field->type == 'file'){
						if(in_array($value, $supported_file_ext)){
							$allowed_ext[]	= $value;
						}else {
							$error_file  .= $value.",";
						}
				}
					
			}	

			if(!empty($allowed_ext)){
				$params->allowed_ext  = $allowed_ext;
				$params  = json_encode($params);

				$query   = "UPDATE `#__joomprofile_field` SET `params` = ".stripcslashes($db->quote($params))." WHERE `id` = ".$field->id;

				$db->setQuery($query);
				$db->query();
			}
				

		}
		if($error_image || $error_file	){ ?>
			<div class="alert alert-info ">Thanks for Updating Joom Profile. There are some changes in image and File validation. <br/><br/>
			<?php if($error_image):?>
					<?php echo $error_image?> these are not supported for image type.
			<?php endif;?>

			<?php if($error_file):?>
				<br/><br/>
					<?php echo $error_file?> these are not supported for file type.
			<?php endif;?>

			</div>
	<?php	}

	return true;

	}
}
