<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidator');

//$listAgent = $this->getListAgents();
// $listAgentLevel1 = $this->getListAgentLevel1();
// echo "<pre>";
// print_r($listAgentLevel1);
// echo "</pre>";
// die;

//$this->updateAllBuyOfAgent();
//$this->upgradeAgentLevel1();
// echo "<br>";
// print_r($this->newAgents[0]->id);
// die;
?>
<h3>Đại lý ưu tiên nhận Liên hệ (Data)</h3>
<div class="users">
	<table class="table table-striped">
		<thead>
			<tr>
				<th><a>STT</a></th>
				<th><a>ID</a></th>
				<th><a>ID Biznet</a></th>
				<th><a>Họ và tên</a></th>
				<th><a>Tên đăng nhập</a></th>
				<th><a>Ngày mua gần nhất</a></th>
				<th><a>Ngày đăng ký</a></th>
				<th><a>Level</a></th>
				<th style="text-align:right"><a>Tổng tiền</a></th>
				<th><a>Đã mua</a></th>
				<!-- <th><a>Thống kê</a></th> -->
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ($this->agentsWillBuy as $index => $agent){
		?>
		<tr>
			<td><?php echo $index+1 ;?></td>
			<td>#<?php echo $agent->id;?></td>
			<td><?php echo $agent->id_biznet;?></td>
			<td><a><?php echo $agent->name;?></a></td>
			<td><?php echo $agent->username;?></td>
			<td><?php echo $agent->buydate != '0000-00-00 00:00:00'? date('d-m-Y H:i:s', strtotime($agent->buydate)): '#';?></td>
			<td><?php echo date('d-m-Y H:i:s', strtotime($agent->registerDate));?></td>
			<td><?php echo $agent->level; ?></td>
			<td align="right"><span class="price"><?php echo number_format($agent->money,0,".","."); ?> đ<span></td>
			<td><?php echo $agent->buyall; ?></td>
			<!-- <td>
				<a target="_blank" href="<?php //echo JRoute::_('index.php?option=com_customer&view=sumary&Itemid=630&uid='.$sale->id);  ?>">Thống kê khách hàng</a>
				<div style="padding-top:8px;"><a target="_blank" href="<?php //echo JRoute::_('index.php?option=com_customer&view=statistic&Itemid=630&uid='.$sale->id);  ?>">Thống kê trạng thái</a></div>
			</td> -->

		</tr>
		<?php }?>
		</tbody>
	</table>
</div>



<style>
@media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px)  {
        /* Force table to not be like tables anymore */
        table, thead, tbody, th, td, tr {
            display: block;
        }
        /* Hide table headers (but not display: none;, for accessibility) */
        thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        tr { border: 1px solid #eee; margin-bottom: 10px}
        td {
            /* Behave  like a "row" */
            border: none;
            border-bottom: 1px solid #eee;
            position: relative;
            padding-left: 50%;
            border-top: 0 !important;
        }
        td:last-of-type{
            border: none;
        }
        td:before {
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            color: #0b0b0b;
            font-weight: 600;
        }
        /*
		Label the data
		*/
				td:nth-of-type(1):before { content: "STT:"; }
        td:nth-of-type(2):before { content: "ID:"; }
				td:nth-of-type(3):before { content: "ID Biznet"; }
        td:nth-of-type(4):before { content: "Họ và tên:"; }
				td:nth-of-type(5):before { content: "Tên đăng nhập:"; }
        td:nth-of-type(6):before { content: "Ngày mua gần nhất:"; }
        td:nth-of-type(7):before { content: "Ngày đăng ký:"; }
        td:nth-of-type(8):before { content: "Level:"; }
				td:nth-of-type(9):before { content: "Tổng tiền:"; }
				td:nth-of-type(10):before { content: "Đã mua:"; }



        .note{
            display: inline-block;
        }
        .btn{
            width: auto;
        }
        .pagination{margin: 0px;}
    }
</style>
