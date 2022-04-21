<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class JoomprofileProfileLibField extends JoomprofileLib
{
	protected $id 			= 0;
	protected $type 		= 0;
	protected $title 		= 0;
	protected $tooltip 		= '';
	protected $published 	= 0;
	protected $css_class 	= 0;
	protected $params 		= 0;

	protected function __construct($config = array())
	{
		parent::__construct($config);

		$this->id = 0;
		$this->type = '';
		$this->title = '';
		$this->tooltip = '';
		$this->published = true;
		$this->css_class = '';
		$this->params = new JRegistry();
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function getClassCSS()
	{
		return $this->css_class;
	}

	public function getIDField()
	{
		return $this->id;
	}

	public function getPublished()
	{
		return $this->published;
	}



    public function getType()
    {
        return $this->type;
    }

	/**
	 * @return JoomprofileLibField
	 */
	public function getFieldInstance()
	{
		return JoomprofileLibField::get($this->type);
	}
}
