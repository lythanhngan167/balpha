	<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileLibFieldEditor extends JoomprofileLibField
{
	public $name = 'editor';
	public $location = __DIR__;
	protected $searchable = true;
	
	public function format($field, $value, $userid, $on)
	{
		if(!is_array($value) || !isset($value[1]) || empty($value[1])){
			$value[0] = parent::format($field, $value[0], $userid, $on);
			$value = $this->__insertContent($value[0]);
		}
		else{
			list($content, $id) = $value;
			$content = parent::format($field, $content, $userid, $on);
			$this->__updateContent($id, $content);
			$value = $id;
		}

		return $value;
	}

	public function getUserEditHtml($fielddata, $value, $userid)
	{
		if($value){
			// has came from registration
			if(is_array($value)){
			
			}
			else{
				$content = $this->__getContent($value);
				$value = array($content->content, $value);
			}
		}
		else{
			$value = array('', 0);
		}
		
		return parent::getUserEditHtml($fielddata, $value, $userid);
	}

	public function getViewHtml($fielddata, $value, $user_id)
	{
		$value = $this->__getContent($value);
				
		$path 		= $this->location.'/templates';
		$template 	= new JoomprofileTemplate(array('path' => $path));				
		$template->set('fielddata', $fielddata)->set('value', $value);
		return $template->render('field.'.$this->name.'.view');
	}

	private function __updateContent($id, $content)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->update('`#__joomprofile_content`')
				->set('`content` = '.$db->quote($content))
				->where('`id` = '.$db->quote($id));
		$db->setQuery($query);
		return $db->query();
	}

	private function __insertContent($content)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->insert('`#__joomprofile_content`')
				->set('`content` = '.$db->quote($content));
		$db->setQuery($query);
		if($db->query()){
			return $db->insertid();
		}

		return false;
	}

	private function __getContent($id)
	{
		$db = JoomprofileHelperJoomla::getDBO();
		$query = $db->getQuery(true);
		$query->select('*')
				->from('`#__joomprofile_content`')
				->where('`id` = '.$db->quote($id));

		$db->setQuery($query);
		return $db->loadObject();
	}

    public function buildSearchQuery($fielddata, $query, $value)
    {
        $db      = JoomprofileHelperJoomla::getDbo();

        $tmpQuery = $db->getQuery(true);
        $tmpQuery->select('DISTINCT id as content_id')
            ->from('#__joomprofile_content')
            ->where('MATCH(`content`) AGAINST ('.$db->quote($value).' IN BOOLEAN MODE)');

        $query->where('`value` IN ('.$tmpQuery->__toString().')');

        return true;
    }
}
