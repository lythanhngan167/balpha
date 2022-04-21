<?php
/**
 * @version        1.0
 * @package        Joomla
 * @subpackage     OSFramework
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die();

class EShopView extends JViewLegacy
{
	/**
	 * @var JInput
	 */
	protected $input;

	/**
	 * Constructor.
	 *
	 * @param array $config
	 */
	public function __construct($config)
	{
		$paths   = array();
		$paths[] = JPATH_COMPONENT . '/themes/default/views/' . $this->getName();
		$theme   = EshopHelper::getConfigValue('theme');

		if ($theme != 'default')
		{
			$paths[] = JPATH_COMPONENT . '/themes/' . $theme . '/views/' . $this->getName();
		}

		$config['template_path'] = $paths;

		$this->input = JFactory::getApplication()->input;

		parent::__construct($config);
	}

	/**
	 * Set document page title
	 *
	 * @param string $title
	 */
	protected function setPageTitle($title)
	{
		$app = JFactory::getApplication();

		// Set title of the page
		$siteNamePosition = $app->get('sitename_pagetitles');

		if ($siteNamePosition == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($siteNamePosition == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		JFactory::getDocument()->setTitle($title);
	}

	/**
	 * Execute and display a template script.
	 *
	 * @param   string $tpl The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @see     JViewLegacy::loadTemplate()
	 * @since   12.2
	 */
	public function display($tpl = null)
	{
		$format = $this->input->getCmd('format');

		if ($format == 'raw')
		{
			parent::display($tpl);

			return;
		}

		$result = $this->loadTemplate($tpl);

		if ($result instanceof Exception)
		{
			return $result;
		}

		echo '<div id="eshop-main-container" class="eshop-container">' . $result . '</div>';
	}
}