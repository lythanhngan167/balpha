<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Request_package
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

use \Joomla\CMS\Factory;
use \Joomla\Utilities\ArrayHelper;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Table\Table;

/**
 * Request_package model.
 *
 * @since  1.6
 */
class Request_packageModelRequestpackageForm extends \Joomla\CMS\MVC\Model\FormModel
{
    private $item = null;





    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return void
     *
     * @since  1.6
     *
     * @throws Exception
     */
    protected function populateState()
    {
        $app = Factory::getApplication('com_request_package');

        // Load state from the request userState on edit or from the passed variable on default
        if (Factory::getApplication()->input->get('layout') == 'edit')
        {
                $id = Factory::getApplication()->getUserState('com_request_package.edit.requestpackage.id');
        }
        else
        {
                $id = Factory::getApplication()->input->get('id');
                Factory::getApplication()->setUserState('com_request_package.edit.requestpackage.id', $id);
        }

        $this->setState('requestpackage.id', $id);

        // Load the parameters.
        $params       = $app->getParams();
        $params_array = $params->toArray();

        if (isset($params_array['item_id']))
        {
                $this->setState('requestpackage.id', $params_array['item_id']);
        }

        $this->setState('params', $params);
    }

    /**
     * Method to get an ojbect.
     *
     * @param   integer $id The id of the object to get.
     *
     * @return Object|boolean Object on success, false on failure.
     *
     * @throws Exception
     */
    public function getItem($id = null)
    {
        if ($this->item === null)
        {
            $this->item = false;

            if (empty($id))
            {
                    $id = $this->getState('requestpackage.id');
            }

            // Get a level row instance.
            $table = $this->getTable();

            if ($table !== false && $table->load($id) && !empty($table->id))
            {
                $user = Factory::getUser();
                $id   = $table->id;



				if ($id)
				{
					$canEdit = $user->authorise('core.edit', 'com_request_package.requestpackage.' . $id) || $user->authorise('core.create', 'com_request_package.requestpackage.' . $id);
				}
				else
				{
					$canEdit = $user->authorise('core.edit', 'com_request_package') || $user->authorise('core.create', 'com_request_package');
				}

                if (!$canEdit && $user->authorise('core.edit.own', 'com_request_package.requestpackage.' . $id))
                {
                        $canEdit = $user->id == $table->created_by;
                }

                if (!$canEdit)
                {
                        throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
                }

                // Check published state.
                if ($published = $this->getState('filter.published'))
                {
                        if (isset($table->state) && $table->state != $published)
                        {
                                return $this->item;
                        }
                }

                // Convert the JTable to a clean JObject.
                $properties = $table->getProperties(1);
                $this->item = ArrayHelper::toObject($properties, 'JObject');



            }
        }

        return $this->item;
    }

    /**
     * Method to get the table
     *
     * @param   string $type   Name of the JTable class
     * @param   string $prefix Optional prefix for the table class name
     * @param   array  $config Optional configuration array for JTable object
     *
     * @return  JTable|boolean JTable if found, boolean false on failure
     */
    public function getTable($type = 'Requestpackage', $prefix = 'Request_packageTable', $config = array())
    {
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_request_package/tables');

        return Table::getInstance($type, $prefix, $config);
    }

    /**
     * Get an item by alias
     *
     * @param   string $alias Alias string
     *
     * @return int Element id
     */
    public function getItemIdByAlias($alias)
    {
        $table      = $this->getTable();
        $properties = $table->getProperties();

        if (!in_array('alias', $properties))
        {
                return null;
        }

        $table->load(array('alias' => $alias));
        $id = $table->id;


            return $id;

    }

    /**
     * Method to check in an item.
     *
     * @param   integer $id The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkin($id = null)
    {
        // Get the id.
        $id = (!empty($id)) ? $id : (int) $this->getState('requestpackage.id');

        if ($id)
        {
            // Initialise the table
            $table = $this->getTable();

            // Attempt to check the row in.
            if (method_exists($table, 'checkin'))
            {
                if (!$table->checkin($id))
                {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Method to check out an item for editing.
     *
     * @param   integer $id The id of the row to check out.
     *
     * @return  boolean True on success, false on failure.
     *
     * @since    1.6
     */
    public function checkout($id = null)
    {
        // Get the user id.
        $id = (!empty($id)) ? $id : (int) $this->getState('requestpackage.id');

        if ($id)
        {
            // Initialise the table
            $table = $this->getTable();

            // Get the current user object.
            $user = Factory::getUser();

            // Attempt to check the row out.
            if (method_exists($table, 'checkout'))
            {
                if (!$table->checkout($user->get('id'), $id))
                {
                    return false;
                }
            }
        }

        return true;

    }

    /**
     * Method to get the profile form.
     *
     * The base form is loaded from XML
     *
     * @param   array   $data     An optional array of data for the form to interogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return    JForm    A JForm object on success, false on failure
     *
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm('com_request_package.requestpackage', 'requestpackageform', array(
                        'control'   => 'jform',
                        'load_data' => $loadData
                )
        );

        if (empty($form))
        {
                return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return    array  The default data is an empty array.
     * @since    1.6
     */
    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState('com_request_package.edit.requestpackage.data', array());

        if (empty($data))
        {
            $data = $this->getItem();
        }

        if ($data)
        {

		// Support for multiple or not foreign key field: province
		$array = array();

		foreach ((array) $data->province as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->province = $array;
		}
		// Support for multiple or not foreign key field: status
		$array = array();

		foreach ((array) $data->status as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->status = $array;
		}
		// Support for multiple or not foreign key field: company
		$array = array();

		foreach ((array) $data->company as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->company = $array;
		}
		// Support for multiple or not foreign key field: services
		$array = array();

		foreach ((array) $data->services as $value)
		{
			if (!is_array($value))
			{
				$array[] = $value;
			}
		}
		if(!empty($array)){

		$data->services = $array;
		}

            return $data;
        }

        return array();
    }

    /**
     * Method to save the form data.
     *
     * @param   array $data The form data
     *
     * @return bool
     *
     * @throws Exception
     * @since 1.6
     */
    public function save($data)
    {
        $id    = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('requestpackage.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user  = Factory::getUser();

        if ($id)
        {
            // Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_request_package.requestpackage.' . $id) || $authorised = $user->authorise('core.edit.own', 'com_request_package.requestpackage.' . $id);
        }
        else
        {
            // Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_request_package');
        }

        if ($authorised !== true)
        {
            throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $table = $this->getTable();
        if ($table->counselor_id > 0) {
          $user = Factory::getUser($table->counselor_id);
          if ($user->id > 0) {
            $table->counselor_id = $table->counselor_id;
          }
          else {
            $table->counselor_id = 0;
          }
        }
        else {
          $table->counselor_id = 0;
        }

        if ($table->save($data) === true)
        {
            //Save customer
            JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_registration/models', 'RegistrationModel');
            $model = JModelLegacy::getInstance('RegistrationForm', 'RegistrationModel', array('ignore_request' => true));

            $data['project_id'] = PROJECT_REQUEST_PACKAGE;
            $data['reference_id'] = $table->id;
            $data['reference_type'] = 'request_package';
            $data['category_id'] = DATA_NEW;
            $data['province'] = $table->province;
            $new_customer = $model->createCustomer($data);
            $new_customer = $model->save($data);
            return $table->id;
        }
        else
        {
            return false;
        }

    }

    /**
     * Method to delete data
     *
     * @param   int $pk Item primary key
     *
     * @return  int  The id of the deleted item
     *
     * @throws Exception
     *
     * @since 1.6
     */
    public function delete($pk)
    {
        $user = Factory::getUser();


            if (empty($pk))
            {
                    $pk = (int) $this->getState('requestpackage.id');
            }

            if ($pk == 0 || $this->getItem($pk) == null)
            {
                    throw new Exception(Text::_('COM_REQUEST_PACKAGE_ITEM_DOESNT_EXIST'), 404);
            }

            if ($user->authorise('core.delete', 'com_request_package.requestpackage.' . $id) !== true)
            {
                    throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
            }

            $table = $this->getTable();

            if ($table->delete($pk) !== true)
            {
                    throw new Exception(Text::_('JERROR_FAILED'), 501);
            }

            return $pk;

    }

    /**
     * Check if data can be saved
     *
     * @return bool
     */
    public function getCanSave()
    {
        $table = $this->getTable();

        return $table !== false;
    }

}
