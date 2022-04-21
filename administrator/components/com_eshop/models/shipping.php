<?php
/**
 * @version		1.3.2
 * @package		Joomla
 * @subpackage	EShop
 * @author  	Giang Dinh Truong
 * @copyright	Copyright (C) 2012 Ossolution Team
 * @license		GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

/**
 * Eshop Component Model
 *
 * @package		Joomla
 * @subpackage	EShop
 * @since 1.5
 */
class EShopModelShipping extends EShopModel
{

	/**
	 * Save shipping plugin parameter
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		$input = JFactory::getApplication()->input;
		$db = $this->getDbo();
		$row = new EShopTable('#__eshop_shippings', 'id', $db);
		if ($data['id'])
			$row->load($data['id']);
		if (!$row->bind($data))
		{
			return false;
		}
		//Save parameters
		$params = $input->get('params', null, 'post', 'array');
		if (is_array($params))
		{
			$txt = array();
			foreach ($params as $k => $v)
			{
				if (is_array($v))
				{
					for ($i = 0; $n = count($v), $i < $n; $i++)
					{
						$v[$i] = '"' . $v[$i] . '"';
					}
					$v = implode(',', $v);
					$txt[] = '"' . $k . '":[' . $v . ']';
				}
				else
				{
					$v = str_replace("\r\n", '\r\n', $v);
					$txt[] = '"' . $k . '":"' . $v . '"';
				}
			}
			$row->params = '{' . implode(",", $txt) . '}';
		}
		if (!$row->store())
		{
			return false;
		}
		$data['id'] = $row->id;
		
		return true;
	}

	/**
	 * Install a shipping plugin
	 * @return boolean
	 */
	function install()
	{
		$input = JFactory::getApplication()->input;
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.folder');
		$db = $this->getDbo();
		$plugin = $input->files->get('plugin_package', null, 'raw');
		if ($plugin['error'] || $plugin['size'] < 1)
		{
			$input->set('msg', JText::_('ESHOP_UPLOAD_PLUGIN_ERROR'));
			
			return false;
		}
		$config = new JConfig();
		$dest = $config->tmp_path . '/' . $plugin['name'];
		$uploaded = JFile::upload($plugin['tmp_name'], $dest, false, true);
		
		if (!$uploaded)
		{
			$input->set('msg', JText::_('ESHOP_UPLOAD_PLUGIN_FAILED'));
			
			return false;
		}
		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');
		$extractDir = JPath::clean(dirname($dest) . '/' . $tmpdir);
		$result = JArchive::extract($dest, $extractDir);
		if (!$result)
		{
			$input->set('msg', JText::_('ESHOP_EXTRACT_PLUGIN_ERROR'));
			return false;
		}
		$dirList = array_merge(JFolder::files($extractDir, ''), JFolder::folders($extractDir, ''));
		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractDir . '/' . $dirList[0]))
			{
				$extractDir = JPath::clean($extractDir . '/' . $dirList[0]);
			}
		}
		//Now, search for xml file
		$xmlfiles = JFolder::files($extractDir, '.xml$', 1, true);
		if (empty($xmlfiles))
		{
			$input->set('msg', JText::_('ESHOP_COULD_NOT_FIND_XML_FILE'));
			return false;
		}
		$file = $xmlfiles[0];
		$root = JFactory::getXML($file, true);
		$pluginType = $root->attributes()->type;
		$pluginGroup = $root->attributes()->group;
		if ($root->getName() !== 'install')
		{
			$input->set('msg', JText::_('ESHOP_INVALID_XML_FILE'));
			return false;
		}
		if ($pluginType != 'eshopplugin')
		{
			$input->set('msg', JText::_('ESHOP_INVALID_ESHOP_SHIPPING_PLUGIN'));
			return false;
		}
		$name = (string) $root->name;
		$title = (string) $root->title;
		$author = (string) $root->author;
		$creationDate = (string) $root->creationDate;
		$copyright = (string) $root->copyright;
		$license = (string) $root->license;
		$authorEmail = (string) $root->authorEmail;
		$authorUrl = (string) $root->authorUrl;
		$version = (string) $root->version;
		$description = (string) $root->description;
		$row = new EShopTable('#__eshop_shippings', 'id', $db);
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__eshop_shippings')
			->where('name = "' . $db->escape($name) . '"');
		$db->setQuery($query);
		$pluginId = (int) $db->loadResult();
		if ($pluginId)
		{
			$row->load($pluginId);
			$row->name = $name;
			$row->title = $title;
			$row->author = $author;
			$row->creation_date = $creationDate;
			$row->copyright = $copyright;
			$row->license = $license;
			$row->author_email = $authorEmail;
			$row->author_url = $authorUrl;
			$row->version = $version;
			$row->description = $description;
		}
		else
		{
			$row->name = $name;
			$row->title = $title;
			$row->author = $author;
			$row->creation_date = $creationDate;
			$row->copyright = $copyright;
			$row->license = $license;
			$row->author_email = $authorEmail;
			$row->author_url = $authorUrl;
			$row->version = $version;
			$row->description = $description;
			$row->published = 0;
			$row->ordering = $row->getNextOrder('published=1');
		}
		$row->store();
		$pluginDir = JPATH_ROOT . '/components/com_eshop/plugins/shipping/';
		JFile::move($file, $pluginDir . '/' . basename($file));
		$files = $root->files->children();
		
		for ($i = 0, $n = count($files); $i < $n; $i++)
		{
			$file = $files[$i];
			
			if ($file->getName() == 'filename')
			{
				$fileName = $file;
				JFile::copy($extractDir . '/' . $fileName, $pluginDir . '/' . $fileName);
			}
			elseif ($file->getName() == 'folder')
			{
				$folderName = $file;
				
				if (JFolder::exists($extractDir . '/' . $folderName))
				{
					if (JFolder::exists($pluginDir . '/' . $folderName))
					{
						JFolder::delete($pluginDir . '/' . $folderName);
					}
					
					JFolder::move($extractDir . '/' . $folderName, $pluginDir . '/' . $folderName);
				}
			}
		}
		
		$languageFiles = $root->languages->children();
		
		for ($i = 0; $n = count($languageFiles), $i < $n; $i++)
		{
			$languageFile = $languageFiles[$i];
			$languageDir = JPATH_ROOT . '/language/' . $languageFile->attributes()->tag;
			if (!JFile::exists($languageDir . '/' . basename((string) $languageFile)))
			{
				JFile::copy($extractDir . '/' . (string) $languageFile, $languageDir . '/' . basename((string) $languageFile));
			}
		}
		
		JFolder::delete($extractDir);
		return true;
	}

	/**
	 * Remove the selected shipping plugin
	 * @see EShopModel::delete()
	 */
	function delete($cid = array())
	{
		jimport('joomla.filesystem.folder');
		$db = $this->getDbo();
		$row = new EShopTable('#__eshop_shippings', 'id', $db);
		$pluginDir = JPATH_ROOT . '/components/com_eshop/plugins/shipping/';
		foreach ($cid as $id)
		{
			$row->load($id);
			$name = $row->name;
			$file = $pluginDir . '/' . $name . '.xml';
			if (!JFile::exists($file))
			{
				//Simply delete the record
				$row->delete();
				return 1;
			}
			else
			{
				$root = JFactory::getXML($file);
				$files = $root->files->children();
				for ($i = 0, $n = count($files); $i < $n; $i++)
				{
					$file = $files[$i];
					if ($file->getName() == 'filename')
					{
						$fileName = $file;
						if (JFile::exists($pluginDir . '/' . $fileName))
						{
							JFile::delete($pluginDir . '/' . $fileName);
						}
					}
					elseif ($file->getName() == 'folder')
					{
						$folderName = $file;
						if ($folderName)
						{
							if (JFolder::exists($pluginDir . '/' . $folderName))
							{
								JFolder::delete($pluginDir . '/' . $folderName);
							}
						}
					}
				}
				JFile::delete($pluginDir . '/' . $name . '.xml');
				$row->delete();
			}
		}
		return 1;
	}
}