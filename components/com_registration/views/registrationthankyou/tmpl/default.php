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
<h3>
<?php
  $active = JFactory::getApplication()->getMenu()->getActive();
echo $active->title;
?>
</h3>
<p><span style="font-size: 15px; color: #008000;">
  Cảm ơn bạn đã quan tâm và đăng ký cơ hội phát triển kinh doanh cùng B-Alpha.<br>
  B-Alpha sẽ sớm liên hệ với bạn trong vài ngày tới, bạn nhớ chú ý điện thoại để không bỏ lỡ cuộc gọi từ Chuyên viên nhé.<br>
  <b style="color:red;">Ngoài ra, B-Alpha cũng vừa gửi tặng bạn 1 món quà nhỏ qua địa chỉ email bạn đăng ký.</b><br>
  Bạn xác nhận email để nhận được quà tặng nhé!<br><br>
  Chúc bạn một ngày làm việc thật vui vẻ!
</span></p>
<br>
<img title="Thank you" alt="Thank you" src="<?php echo JUri::root() ?>images/thankyou.png" />

<?php
$uri = JUri::getInstance();
$url_utm =  $uri->toString();
$url_utm = str_replace("type=user&","",$url_utm);
$url_utm = str_replace("type=user","",$url_utm);
$url_utm = str_replace("type=total&","",$url_utm);
$url_utm = str_replace("type=total","",$url_utm);
$url_utm = str_replace("type=agent&","",$url_utm);
$url_utm = str_replace("type=agent","",$url_utm);

$array_url_utm =  explode("?",$url_utm);
$params_link = '';
if($array_url_utm[1] != ''){
  $params_link = "?".$array_url_utm[1];
}

if($_REQUEST['type'] == 'total'){
  if($_REQUEST['lpage'] == 'bhcn'){
    $link = JUri::root().'bao-hiem-cong-nghe'.$params_link;
  }elseif($_REQUEST['lpage'] == 'ccslbca'){
    $link = JUri::root().'cau-chuyen-nha-sang-lap-bca'.$params_link;
  }else{
    $link = JUri::root().'bao-hiem-40'.$params_link;
  }

}
if($_REQUEST['type'] == 'agent'){
  $link = JUri::root().'agent.html'.$params_link;
}
if($_REQUEST['type'] == 'user'){
  $landingpage_username = $_REQUEST['landing'];
  if($landingpage_username != ''){
    $link = JUri::root().'agent/'.$landingpage_username.'.html'.$params_link;
  }else{
    $link = JUri::root().'agent.html'.$params_link;
  }

}
?>
<!-- <div class="comeback">
  <a href="<?php echo $link; ?>">
  <button type="button" class="btn btn-warning">Quay lại</button>
  </a>
</div> -->
 <?php
$user    = JFactory::getUser();

 ?>
 <script>
jQuery(document).ready(function(){
  jQuery('#sp-logo .logo a').attr('href','<?php echo $link; ?>');
});
 </script>

 <style>
 #sp-title{
   display: none;
 }
 #sp-menu{
   display: none;
 }
 #sp-top-bar{
   display: none;
 }
 .sp-copyright{
   display: none;
 }
 #sp-main-body {
     min-height: 650px;
 }
 .comeback{
   text-align: left;
 }
 </style>
