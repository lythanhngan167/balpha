<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Export_customer
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2017 nganly
 * @license    bản quyền mã nguồn mở GNU phiên bản 2
 */
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Class Export_customerController
 *
 * @since  1.6
 */
class Export_customerController extends JControllerLegacy
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean $cachable  If true, the view output will be cached
	 * @param   mixed   $urlparams An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController   This object to support chaining.
	 *
	 * @since    1.5
	 */
	public function display($cachable = false, $urlparams = false)
	{
        $app  = JFactory::getApplication();
        $view = $app->input->getCmd('view', 'exportcustomers');
		$app->input->set('view', $view);

		parent::display($cachable, $urlparams);

		return $this;
	}
}
