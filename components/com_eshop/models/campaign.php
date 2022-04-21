<?php
/**
 * @version        3.1.0
 * @package        Joomla
 * @subpackage     EShop
 * @author         Giang Dinh Truong
 * @copyright      Copyright (C) 2012 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
require_once dirname(__FILE__) . '/products.php';

class EShopModelCampaign extends EShopModelProducts
{
    protected function _buildQueryJoins(JDatabaseQuery $query)
    {
        return parent::_buildQueryJoins($query);
    }

    protected function _buildQueryWhere(JDatabaseQuery $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();
        if ($state->id > 1) {
            $query->where('hp.group_id =' . (int)$state->id);
        } elseif ($state->id == 1) {
            $query->where('a.product_featured = 1');
        }
        return $this;
    }

    /**
     * Builds FROM tables list for the query
     */
    protected function _buildQueryFrom(JDatabaseQuery $query)
    {
        $state = $this->getState();
        if ($state->id > 1) {
            $query->from('#__eshop_home_products AS hp')
                ->leftJoin('#__eshop_products as a on a.id = hp.product_id');
        } else {
            $query->from('#__eshop_products AS a');
        }


        return $this;
    }

    public function getCampaignInfo($id)
    {
        $db = JFactory::getDbo();
        $sql = 'SELECT * from #__eshop_home_group WHERE id = ' . (int)$id;
        return $db->setQuery($sql)->loadAssoc();
    }
}
