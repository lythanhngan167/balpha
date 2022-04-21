<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Referral
 * @author     Truyền Đặng Minh <minhtruyen.ut@gmail.com>
 * @copyright  2021 Truyền Đặng Minh
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Session\Session;
use \Joomla\CMS\Language\Text;

/**
 * Referral controller class.
 *
 * @since  1.6
 */
class ReferralControllerReferralForm extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @return void
	 *
	 * @since    1.6
     *
     * @throws Exception
	 */
	public function edit($key = NULL, $urlVar = NULL)
	{
		$app = Factory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_referral.edit.referral.id');
		$editId     = $app->input->getInt('id', 0);

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_referral.edit.referral.id', $editId);

		// Get the model.
		$model = $this->getModel('ReferralForm', 'ReferralModel');

		// Check out the item
		if ($editId)
		{
			$model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId)
		{
			$model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(Route::_('index.php?option=com_referral&view=referralform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return void
	 *
	 * @throws Exception
	 * @since  1.6
	 */
	public function save($key = NULL, $urlVar = NULL)
	{
		// Check for request forgeries.
		Session::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app   = Factory::getApplication();
		$model = $this->getModel('ReferralForm', 'ReferralModel');

		// Get the user data.
		$data = Factory::getApplication()->input->get('jform', array(), 'array');
		if(!isset($data['status_id'])){
			$data['status_id'] = 'new'; 
		}
		// Validate the posted data.
		$form = $model->getForm();
		if (!$form)
		{
			throw new Exception($model->getError(), 500);
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			$input = $app->input;
			$jform = $input->get('jform', array(), 'ARRAY');

			// Save the data in the session.
			$app->setUserState('com_referral.edit.referral.data', $jform);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_referral.edit.referral.id');
			$this->setRedirect(Route::_('index.php?option=com_referral&view=referralform&layout=edit&id=' . $id, false));

			$this->redirect();
		}

		// Attempt to save the data.
		$return = $model->save($data);

		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_referral.edit.referral.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_referral.edit.referral.id');
			$this->setMessage(Text::sprintf('Save failed', $model->getError()), 'warning');
			$this->setRedirect(Route::_('index.php?option=com_referral&view=referralform&layout=edit&id=' . $id, false));
		}

		// Check in the profile.
		if ($return)
		{
			$model->checkin($return);
		}

		
		$this->getDataReferralBiznet($data);
		// Clear the profile id from the session.
		$app->setUserState('com_referral.edit.referral.id', null);

		// Redirect to the list screen.
		// $this->setMessage(Text::_('COM_REFERRAL_ITEM_SAVED_SUCCESSFULLY'));
		// $menu = Factory::getApplication()->getMenu();
		// $item = $menu->getActive();
		// $url  = (empty($item->link) ? 'index.php?option=com_referral&view=referrals' : $item->link);
		// $this->setRedirect(Route::_($url, false));

		$iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$mac = strpos($_SERVER['HTTP_USER_AGENT'],"Mac");
		$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
		$windows = strpos($_SERVER['HTTP_USER_AGENT'],"Windows");
		file_put_contents('./public/upload/install_log/agent',$_SERVER['HTTP_USER_AGENT']);
		if($iPad||$iPhone||$iPod||$mac){
			$this->setRedirect('https://apps.apple.com/us/app/insurchannel/id1548202292');
		}else if($android||$windows){
			$this->setRedirect('https://play.google.com/store/apps/details?id=com.bizappco.insurchannel');
		}else{
			$this->setRedirect('https://play.google.com/store/apps/details?id=com.bizappco.insurchannel');
		}


		// Flush the data from the session.
		$app->setUserState('com_referral.edit.referral.data', null);
	}

	public function getDataReferralBiznet($data){
		$param = $data;
		if($_SERVER['HTTP_HOST'] == 'localhost'){
				$url_biznet = 'http://localhost/biznetweb';
		}else{
			$url_biznet = 'https://biznet.com.vn';//http://biznetweb.local
		}
		// URL có chứa hai thông tin name và diachi
		$url = $url_biznet.'/index.php?option=com_referral&task=referralform.getReferralBCA';
		// print_r($url);die();
		// Khởi tạo CURL
		$ch = curl_init($url);
		// Thiết lập có return
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Thiết lập sử dụng POST
		curl_setopt($ch, CURLOPT_POST, count($param));
		// Thiết lập các dữ liệu gửi đi
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		
		curl_close($ch);

		return $result;
	}
	/**
	 * Method to abort current operation
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function cancel($key = NULL)
	{
		$app = Factory::getApplication();

		// Get the current edit id.
		$editId = (int) $app->getUserState('com_referral.edit.referral.id');

		// Get the model.
		$model = $this->getModel('ReferralForm', 'ReferralModel');

		// Check in the item
		if ($editId)
		{
			$model->checkin($editId);
		}

		$menu = Factory::getApplication()->getMenu();
		$item = $menu->getActive();
		$url  = (empty($item->link) ? 'index.php?option=com_referral&view=referrals' : $item->link);
		$this->setRedirect(Route::_($url, false));
	}

	/**
	 * Method to remove data
	 *
	 * @return void
	 *
	 * @throws Exception
     *
     * @since 1.6
	 */
	public function remove()
    {
        $app   = Factory::getApplication();
        $model = $this->getModel('ReferralForm', 'ReferralModel');
        $pk    = $app->input->getInt('id');

        // Attempt to save the data
        try
        {
            $return = $model->delete($pk);

            // Check in the profile
            $model->checkin($return);

            // Clear the profile id from the session.
            $app->setUserState('com_referral.edit.referral.id', null);

            $menu = $app->getMenu();
            $item = $menu->getActive();
            $url = (empty($item->link) ? 'index.php?option=com_referral&view=referrals' : $item->link);

            // Redirect to the list screen
            $this->setMessage(Text::_('COM_REFERRAL_ITEM_DELETED_SUCCESSFULLY'));
            $this->setRedirect(Route::_($url, false));

            // Flush the data from the session.
            $app->setUserState('com_referral.edit.referral.data', null);
        }
        catch (Exception $e)
        {
            $errorType = ($e->getCode() == '404') ? 'error' : 'warning';
            $this->setMessage($e->getMessage(), $errorType);
            $this->setRedirect('index.php?option=com_referral&view=referrals');
        }
    }
}
