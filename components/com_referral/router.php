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

use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;

/**
 * Class ReferralRouter
 *
 */
class ReferralRouter extends RouterView
{
	private $noIDs;
	public function __construct($app = null, $menu = null)
	{
		$params = JComponentHelper::getComponent('com_referral')->params;
		$this->noIDs = (bool) $params->get('sef_ids');
		
		$referrals = new RouterViewConfiguration('referrals');
		$referrals->setKey('id')->setNestable();
		$this->registerView($referrals);
			$referral = new RouterViewConfiguration('referral');
			$referral->setKey('id')->setParent($referrals, 'catid');
			$this->registerView($referral);
			$referralform = new RouterViewConfiguration('referralform');
			$referralform->setKey('id');
			$this->registerView($referralform);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));

		if ($params->get('sef_advanced', 0))
		{
			$this->attachRule(new StandardRules($this));
			$this->attachRule(new NomenuRules($this));
		}
		else
		{
			JLoader::register('ReferralRulesLegacy', __DIR__ . '/helpers/legacyrouter.php');
			JLoader::register('ReferralHelpersReferral', __DIR__ . '/helpers/referral.php');
			$this->attachRule(new ReferralRulesLegacy($this));
		}
	}


	
			/**
			 * Method to get the segment(s) for a category
			 *
			 * @param   string  $id     ID of the category to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getReferralsSegment($id, $query)
			{
				$category = Categories::getInstance('referral.referrals')->get($id);

				if ($category)
				{
					$path = array_reverse($category->getPath(), true);
					$path[0] = '1:root';

					if ($this->noIDs)
					{
						foreach ($path as &$segment)
						{
							list($id, $segment) = explode(':', $segment, 2);
						}
					}

					return $path;
				}

				return array();
			}
		/**
		 * Method to get the segment(s) for an referral
		 *
		 * @param   string  $id     ID of the referral to retrieve the segments for
		 * @param   array   $query  The request that is built right now
		 *
		 * @return  array|string  The segments of this item
		 */
		public function getReferralSegment($id, $query)
		{
			return array((int) $id => $id);
		}
			/**
			 * Method to get the segment(s) for an referralform
			 *
			 * @param   string  $id     ID of the referralform to retrieve the segments for
			 * @param   array   $query  The request that is built right now
			 *
			 * @return  array|string  The segments of this item
			 */
			public function getReferralformSegment($id, $query)
			{
				return $this->getReferralSegment($id, $query);
			}

	
			/**
			 * Method to get the id for a category
			 *
			 * @param   string  $segment  Segment to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getReferralsId($segment, $query)
			{
				if (isset($query['id']))
				{
					$category = Categories::getInstance('referral.referrals', array('access' => false))->get($query['id']);

					if ($category)
					{
						foreach ($category->getChildren() as $child)
						{
							if ($this->noIDs)
							{
								if ($child->alias == $segment)
								{
									return $child->id;
								}
							}
							else
							{
								if ($child->id == (int) $segment)
								{
									return $child->id;
								}
							}
						}
					}
				}

				return false;
			}
		/**
		 * Method to get the segment(s) for an referral
		 *
		 * @param   string  $segment  Segment of the referral to retrieve the ID for
		 * @param   array   $query    The request that is parsed right now
		 *
		 * @return  mixed   The id of this item or false
		 */
		public function getReferralId($segment, $query)
		{
			return (int) $segment;
		}
			/**
			 * Method to get the segment(s) for an referralform
			 *
			 * @param   string  $segment  Segment of the referralform to retrieve the ID for
			 * @param   array   $query    The request that is parsed right now
			 *
			 * @return  mixed   The id of this item or false
			 */
			public function getReferralformId($segment, $query)
			{
				return $this->getReferralId($segment, $query);
			}
}
