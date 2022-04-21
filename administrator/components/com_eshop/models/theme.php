<?php
/**
 * @version		3.1.0
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
class EShopModelTheme extends EShopModel
{

	/**
	 * Save theme parameter
	 * @see EShopModel::store()
	 */
	function store(&$data)
	{
		$input = JFactory::getApplication()->input;
		$db = $this->getDbo();
		$row = new EShopTable('#__eshop_themes', 'id', $db);
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
	 * Install a theme
	 * @return boolean
	 */
	function install()
	{
		$input = JFactory::getApplication()->input;
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.folder');
		$db = $this->getDbo();
		$theme = $input->files->get('theme_package', null, 'raw');
		if ($theme['error'] || $theme['size'] < 1)
		{
			$input->set('msg', JText::_('ESHOP_UPLOAD_ERROR'));
			
			return false;
		}
		$config = new JConfig();
		$dest = $config->tmp_path . '/' . $theme['name'];
		$uploaded = JFile::upload($theme['tmp_name'], $dest, false, true);
		
		if (!$uploaded)
		{
			$input->set('msg', JText::_('ESHOP_UPLOAD_FAILED'));
			
			return false;
		}
		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');
		$extractdir = JPath::clean(dirname($dest) . '/' . $tmpdir);
		$result = JArchive::extract($dest, $extractdir);
		if (!$result)
		{
			$input->set('msg', JText::_('ESHOP_EXTRACT_THEME_ERROR'));
			return false;
		}
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));
		if (count($dirList) == 1)
		{
			if (JFolder::exists($extractdir . '/' . $dirList[0]))
			{
				$extractdir = JPath::clean($extractdir . '/' . $dirList[0]);
			}
		}
		//Now, search for xml file
		$xmlfiles = JFolder::files($extractdir, '.xml$', 1, true);
		if (empty($xmlfiles))
		{
			$input->set('msg', JText::_('ESHOP_COULD_NOT_FIND_XML_FILE'));
			return false;
		}
		$file = $xmlfiles[0];
		$root = JFactory::getXML($file, true);
		$themeType = $root->attributes()->type;
		$themeGroup = $root->attributes()->group;
		if ($root->getName() !== 'install')
		{
			$input->set('msg', JText::_('ESHOP_INVALID_XML_FILE'));
			return false;
		}
		if ($themeType != 'eshoptheme')
		{
			$input->set('msg', JText::_('ESHOP_INVALID_ESHOP_THEME'));
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
		$row = new EShopTable('#__eshop_themes', 'id', $db);
		$query = $db->getQuery(true);
		$query->select('id')
			->from('#__eshop_themes')
			->where('name = "' . $db->escape($name) . '"');
		$db->setQuery($query);
		$themeId = (int) $db->loadResult();
		if ($themeId)
		{
			$row->load($themeId);
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
		JFolder::create(JPATH_ROOT . '/components/com_eshop/themes/' . $name);
		$themeDir = JPATH_ROOT . '/components/com_eshop/themes/' . $name;
		JFile::move($file, $themeDir . '/' . basename($file));
		$files = $root->files->children();
		for ($i = 0, $n = count($files); $i < $n; $i++)
		{
			$file = $files[$i];
			if ($file->getName() == 'filename')
			{
				$fileName = $file;
				if (!JFile::exists($themeDir . '/' . $fileName))
				{
					JFile::copy($extractdir . '/' . $fileName, $themeDir . '/' . $fileName);
				}
			}
			elseif ($file->getName() == 'folder')
			{
				$folderName = $file;
				if (JFolder::exists($extractdir . '/' . $folderName))
				{
					JFolder::move($extractdir . '/' . $folderName, $themeDir . '/' . $folderName);
				}
			}
		}
		
		JFolder::delete($extractdir);
		return true;
	}

	/**
	 * Remove the selected theme
	 * @see EShopModel::delete()
	 */
	public function delete($cid = array())
	{
		jimport('joomla.filesystem.folder');
		$db = $this->getDbo();
		$row = new EShopTable('#__eshop_themes', 'id', $db);
		$themeDir = JPATH_ROOT . '/components/com_eshop/themes';
		foreach ($cid as $id)
		{
			$row->load($id);
			$name = $row->name;
			$file = $themeDir . '/' . $name . '/' . $name . '.xml';
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
						if (JFile::exists($themeDir . '/' . $name . '/' . $fileName))
						{
							JFile::delete($themeDir . '/' . $name . '/' . $fileName);
						}
					}
					elseif ($file->getName() == 'folder')
					{
						$folderName = $file;
						if ($folderName)
						{
							if (JFolder::exists($themeDir . '/' . $name . '/' . $folderName))
							{
								JFolder::delete($themeDir . '/' . $name . '/' . $folderName);
							}
						}
					}
				}
				JFile::delete($themeDir . '/' . $name . '/' . $name . '.xml');
				if (JFile::exists($themeDir . '/' . $name . '/index.html'))
				{
					JFile::delete($themeDir . '/' . $name . '/index.html');
				}
				JFolder::delete($themeDir. '/' . $name);
				$row->delete();
			}
		}
		return 1;
	}
}