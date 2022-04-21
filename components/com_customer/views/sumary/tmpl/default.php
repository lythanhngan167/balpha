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
?>
<h3><?php echo $this->params->get('page_title'); ?> <?php if($_GET['uid'] > 0){ echo " của <strong>". JFactory::getUser($_GET['uid'])->username."</strong>";} ?></h3>
<div class="row">

            <div class="col-lg-6 col-sm-12">
                Dự án: <select name="project" id="project">
                <option dir="<?php echo JRoute::_('index.php?option=com_customer&view=sumary&Itemid=292'.$uid_parameter); ?>" value="" >Tất cả</option>
              <?php foreach ($this->listProject as $i => $item) {
                  ?>
                        <option <?php if(isset($_GET['project'])&&$_GET['project'] == $item->id){ echo 'selected="selected"';} ?> dir="<?php echo JRoute::_('index.php?option=com_customer&view=sumary&Itemid=292&project='.$item->id.$uid_parameter); ?>" value="<?php echo $item->id?>"><?php echo $item->title; ?></option>
                <?php }?>
                </select>
                <div style="margin-top:8px;" ><a href="<?php echo JRoute::_('index.php?option=com_customer&view=sumary&Itemid=292'); ?>"><button style="" type="button" class="btn btn-success">Xóa</button></a></div>

            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="totalrevenue"><strong>Tổng doanh số</strong>: <?php
            if($user->id > 0){
              echo "<span class='price'>".number_format($this->getTotalRevenue($user->id,$project_id,'',''),0,",",".")." ".BIZ_XU."</span>";
            }else{
              echo '<span class="price">0 đ</span>';
            }
            ?></div>
            <div class="totalmoney">
            <strong><?php echo BIZ_XU; ?> đã dùng</strong>: <?php
          if($user->id > 0){
            echo "<span class='price'>".number_format($this->getTotalMoney($user->id,$project_id,'',''),0,",",".")." ".BIZ_XU."</span>";
          }else{
            echo '<span class="price">0 đ</span>';
          }
          ?></div>
            </div>
        </div>
<div class="bg-primary bg-statistic">
<h3 class="statistic-title">Khách hàng đang chờ</h3>
<div class="statistic-list">
  <table>
    <tr>
      <th>Loại</th>
      <th>Số lượng</th>
      <!-- <th>Tổng tiền</th> -->
    </tr>
    <?php foreach($this->listCat as $cat){ ?>
    <tr>
      <td><?php echo $cat->title; ?></td>
      <td><?php echo $amount = $this->getCountContact($user->id,'1', $cat->id); ?></td>
      <!-- <td><span class="price"><?php echo number_format($amount * $this->getPriceByCat($cat->id),0,",","."); ?> đ</span></td> -->
    </tr>
    <?php } ?>



  </table>
</div>
</div>
<!-- <div class="bg-success bgstatistic">
  <h3 class="statistic-title">Khách hàng tiềm năng</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'5', $cat->id); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div> -->
<div class="bg-info bgstatistic">
  <h3 class="statistic-title">Khách hàng lưỡng lự</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'2', $cat->id); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
<div class="bg-warning bgstatistic">
  <h3 class="statistic-title">Khách hàng quan tâm</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'3', $cat->id); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>

<!-- <div class="bg-danger bgstatistic">
  <h3 class="statistic-title">Khách hàng rất quan tâm</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'4', $cat->id); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div> -->
<div class="bg-success bgstatistic">
  <h3 class="statistic-title">Khách hàng hoàn tất</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
        <th>Doanh thu</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'7', $cat->id); ?></td>
        <td>
          <span class="price"><?php echo number_format($this->getRevenueContact($user->id,'7', $cat->id),0,",","."); ?> <?php echo BIZ_XU; ?></span>
        </td>
      </tr>
      <?php } ?>

    </table>
  </div>
</div>

<div class="bg-warning bgstatistic">
  <h3 class="statistic-title">Khách hàng Trả lại</h3>
  <div class="statistic-list">
    <table>
      <tr>
        <th>Loại</th>
        <th>Số lượng</th>
      </tr>
      <?php foreach($this->listCat as $cat){ ?>
      <tr>
        <td><?php echo $cat->title; ?></td>
        <td><?php echo $this->getCountContact($user->id,'6', $cat->id); ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>


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
.totalmoney,.totalrevenue{text-align: right;}
</style>
<script>
jQuery('#project').change(function () {
  //alert(jQuery(this).attr('dir'));

      window.location = jQuery('option:selected', this).attr('dir');


});
</script>
