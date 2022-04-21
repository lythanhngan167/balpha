<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Registration
 * @author     nganly <lythanhngan167@gmail.com>
 * @copyright  2020 nganly
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

?>
<div id="report-registration">
	<div id="tool-date">
		<a href="index.php?option=com_registration&view=report&date=today"><button class="btn btn-warning"  type="button">Hôm nay từ 00:00</button></a>
		<a href="index.php?option=com_registration&view=report&date=week"><button class="btn btn-warning" type="button">Tuần này (Từ 00:00 Thứ 2 đến hiện tại)</button></a>
		<a href="index.php?option=com_registration&view=report&date=last_week"><button class="btn btn-warning" type="button">Tuần trước (Từ 00:00 Thứ 2 đến CN tuần trước)</button></a>
		<a href="index.php?option=com_registration&view=report&date=month"><button class="btn btn-warning" type="button">Tháng này (Từ 00:00 ngày 01/<?php echo date('m'); ?> đến hiện tại)</button></a>
		<a href="index.php?option=com_registration&view=report&date=last_month"><button class="btn btn-warning" type="button">Tháng trước (Từ 00:00 ngày 01/<?php echo date("m", strtotime("first day of previous month")); ?> đến <?php echo date("d-m", strtotime("last day of previous month")); ?>)</button></a>
	</div>
<table class="report-registration">

  <thead>
		<tr>
    <th>Source</th>
    <th>Medium</th>
    <th>Compain</th>
		<th>Landingpage</th>
  </tr>
	</thead>
  <tr>
		<td>
			<?php
			foreach($this->sources as $source){
				echo "<p><span class=\"name-type\">".$source->utm_sourceonly."</span> (<span class=\"quantity\">".$source->quantity."</span>)</p>";
			}
			?>
		</td>
    <td>
			<?php
			foreach($this->mediums as $medium){
				echo "<p><span class=\"name-type\">".$medium->utm_mediumonly."</span> (<span class=\"quantity\">".$medium->quantity."</span>)</p>";
			}
			?>
		</td>
    <td>
			<?php
			foreach($this->compains as $compain){
				echo "<p><span class=\"name-type\">".$compain->utm_compainonly."</span> (<span class=\"quantity\">".$compain->quantity."</span>)</p>";
			}
			?>

		</td>

		<td>
			<?php
			foreach($this->landingpages as $landingpage){
				echo "<p><span class=\"name-type\">".$landingpage->from_landingpage."</span> (<span class=\"quantity\">".$landingpage->quantity."</span>)</p>";
			}
			?>
		</td>
  </tr>

</table>
</div>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
.subhead-collapse{
	display:none;
}
#report-registration{
	padding-top:20px;
}
.quantity{
	color:red;
}
.name-type{
	color: #1a3867;
}
#tool-date{
	padding-bottom:20px;
	padding-top:10px;
}
</style>
