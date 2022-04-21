<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Customer
 * @author     tung hoang <tungvacc@gmail.com>
 * @copyright  2017 tung hoang
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;
$user = JFactory::getUser();

if(isset($_GET['project']) && $_GET['project'] > 0){
  $project_id = $_GET['project'];
}else{
  $project_id = "";
}
if($_GET['uid'] > 0){
  $uid = $_GET['uid'];
  $uid_parameter = '&uid='.$uid;
}else{
  $uid_parameter = '';
}
$date_str = date("d-m-Y")." - ".date("d-m-Y");
$start_datetime_total = '';
$end_datetime_total = '';
if($_GET['startdate'] != '' && $_GET['enddate'] != ''){
  $start_datetime = date_create($_GET['startdate']);
  $end_datetime = date_create($_GET['enddate']);
  $date_str = date_format($start_datetime,"d-m-Y"). " - ".date_format($end_datetime,"d-m-Y");

  $start_datetime_total = $_GET['startdate']." 00:00:00";
  $end_datetime_total = $_GET['enddate']." 23:59:59";
}
?>
<h3>Thống kê Khách hàng theo trạng thái</h3>

<div class="row">

            <div class="col-lg-6 col-sm-12">
                Dự án: <select name="project" id="project">
                <option dir="<?php echo JRoute::_('index.php?option=com_customer&view=statistic&Itemid=292'.$uid_parameter); ?>" value="" >Tất cả</option>
              <?php foreach ($this->listProject as $i => $item) {
                  ?>
                        <option <?php if(isset($_GET['project'])&&$_GET['project'] == $item->id){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_customer&view=statistic&Itemid=292&project='.$item->id.$uid_parameter); ?>" value="<?php echo $item->id?>"><?php echo $item->title; ?></option>
                <?php }?>
                </select>

            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="totalrevenue"><strong>Tổng doanh số</strong>: <?php
            if($user->id > 0){
              echo "<span class='price'>".number_format($this->getTotalRevenue($user->id,$project_id,$start_datetime_total,$end_datetime_total),0,",",".")." ".BIZ_XU."</span>";
            }else{
              echo '<span class="price">0 '.BIZ_XU.'</span>';
            }
            ?></div>
            <div class="totalmoney">
            <strong><?php echo BIZ_XU; ?> đã dùng</strong>: <?php
          if($user->id > 0){
            echo "<span class='price'>".number_format($this->getTotalMoney($user->id,$project_id,$start_datetime_total,$end_datetime_total),0,",",".")." ".BIZ_XU."</span>";
          }else{
            echo '<span class="price">0 '.BIZ_XU.'</span>';
          }
          ?></div>
            </div>
        </div>
        <div style="padding-top:10px;">
        <input type="text" name="daterange" value="<?php echo $date_str; ?>" />
        <div style="margin-top:8px;" ><a href="<?php echo JRoute::_('index.php?option=com_customer&view=statistic&Itemid=292'); ?>"><button style="" type="button" class="btn btn-success">Xóa</button></a></div>

        <script type="text/javascript">
        jQuery(function() {
            //jQuery('input[name="daterange"]').daterangepicker('');

            jQuery('input[name="daterange"]').daterangepicker(
            {
                locale: {
                  format: 'DD-MM-YYYY'
                },
                // startDate: '2013-01-01',
                // endDate: '2013-12-31'
            },
            function(start, end, label) {
                //alert("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                window.location = '<?php echo JUri::base(); ?>index.php?option=com_customer&view=statistic&Itemid=292<?php if($project_id != '') echo "&project=".$project_id.$uid_parameter; ?>&startdate='+start.format('YYYY-MM-DD')+'&enddate='+end.format('YYYY-MM-DD');
            });
        });
        </script>
        <div>

<?php foreach($this->listCat as $cat){ ?>
<div class="bg-primary bg-statistic">
<h3 class="statistic-title"><?php echo $cat->title; ?></h3>
<div class="statistic-list">
  <table>
    <tr>
      <th>Trạng thái</th>
      <th>Số lượng</th>
      <!-- <th>Tổng tiền</th> -->
      <th>Tổng doanh thu</th>
    </tr>

    <tr>
      <td>Lưỡng lự</td>
      <td><?php echo $amount2 = $this->getCountContact($user->id,'2', $cat->id); ?></td>
      <!-- <td><span class="price"><?php $totalprice2 = $amount2 * $this->getPriceByCat($cat->id); echo number_format($totalprice2,0,",","."); ?> đ</span></td> -->
      <td><span class="price"><?php $totalrevenu2 = $this->getRevenueContact($user->id,'2', $cat->id); echo number_format($totalrevenu2,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr>

    <tr>
      <td>Quan tâm</td>
      <td><?php echo $amount3 = $this->getCountContact($user->id,'3', $cat->id); ?></td>
      <!-- <td><span class="price"><?php $totalprice3 = $amount3 * $this->getPriceByCat($cat->id); echo number_format($totalprice3,0,",","."); ?> đ</span></td> -->
      <td><span class="price"><?php $totalrevenu3 = $this->getRevenueContact($user->id,'3', $cat->id); echo number_format($totalrevenu3,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr>

    <!-- <tr>
      <td>Rất quan tâm</td>
      <td><?php echo $amount4 = $this->getCountContact($user->id,'4', $cat->id); ?></td>
      <td><span class="price"><?php $totalrevenu4 = $this->getRevenueContact($user->id,'4', $cat->id); echo number_format($totalrevenu4,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr> -->

    <tr>
      <td>Hoàn thành</td>
      <td><?php echo $amount7 = $this->getCountContact($user->id,'7', $cat->id); ?></td>
      <!-- <td><span class="price"><?php $totalprice7 = $amount7 * $this->getPriceByCat($cat->id); echo number_format($totalprice7,0,",","."); ?> <?php echo BIZ_XU; ?></span></td> -->
      <td><span class="price"><?php $totalrevenu7 = $this->getRevenueContact($user->id,'7', $cat->id); echo number_format($totalrevenu7,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr>

    <tr>
      <td>Trả lại</td>
      <td><?php echo $amount6 = $this->getCountContact($user->id,'6', $cat->id); ?></td>
      <!-- <td><span class="price"><?php $totalprice6 = $amount6 * $this->getPriceByCat($cat->id); echo number_format($totalprice6,0,",","."); ?> đ</span></td> -->
      <td><span class="price"><?php $totalrevenu6 = $this->getRevenueContact($user->id,'6', $cat->id); echo number_format($totalrevenu6,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr>

    <!-- <tr>
      <td>Hủy</td>
      <td><?php echo $amount8 = $this->getCountContactReturnCancel($user->id,'8', $cat->id); ?></td>
      <td><span class="price"><?php $totalrevenu8 = $this->getRevenueContact($user->id,'8', $cat->id); echo number_format($totalrevenu8,0,",","."); ?> đ</span></td>
    </tr> -->

    <tr class="alltotal">
      <td><strong>Tổng cộng</strong></td>
      <td><?php echo $amount2 + $amount3 + $amount4 + $amount7 + $amount6 + $amount8; ?></td>
      <!-- <td><span class="price"><?php echo number_format($totalprice2 + $totalprice3 + $totalprice4 + $totalprice7 + $totalprice6 + $totalprice8,0,",","."); ?> đ</span></td> -->
      <td><span class="price"><?php echo number_format($totalrevenu2 + $totalrevenu3 + $totalrevenu4 + $totalrevenu7 + $totalrevenu6 + $totalrevenu8,0,",","."); ?> <?php echo BIZ_XU; ?></span></td>
    </tr>
  </table>
</div>
</div>
<?php } ?>


<style>


.bg-statistic{margin-bottom:10px;}
.statistic-title{ font-size:20px; padding-top:7px; padding-left:7px; }
.statistic-list{background-color:#FFF;}
.statistic-list *{
  color:#000;
}

table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #f4f3f3;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #f4f3f3;
}
.price{color:red; font-weight:bold;}
.alltotal{ background-color:#CCC!important; }
.totalmoney,.totalrevenue{text-align: right;}
</style>
<script>
jQuery('#project').change(function () {
  //alert(jQuery(this).attr('dir'));

      window.location = jQuery('option:selected', this).attr('dir');


});
</script>
<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>


<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
