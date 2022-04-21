<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$user = JFactory::getUser();

// echo "<pre>";
// print_r($data->counselors);
// echo "</pre>";
//die();

// public function getFieldVal($id)
// {
//   foreach($this->$data->counselors as $couns){
//     if($couns->user_id == $id){
//       return $couns->value;
//     }
//   }
// }

// public function getExtraField(id)
// {
//   foreach($data->counselors as $counselors){
//     if($counselors->user_id == id){
//       return $counselors;
//     }
//   }
// }


?>


    <div class="f90pro-wrapper row clearfix ">
        <!-- HEADER START -->
      <div class="f90pro-wrapper-left col-md-9 col-lg-9">
        <div class="f90pro-header row-fluid clearfix ">
            <div class="f90pro-left span3 col-lg-3 col-md-3 col-sm-4 col-xs-12 col-6">
                <?php if($data->avatar !== false):?>
                    <div class="f90pro-avtar ">
                        <img class="img-polaroid" src="<?php echo !empty($data->avatar) ? $data->avatar : JUri::root().'media/com_joomprofile/images/default.png' ;?>" alt="Avtar <?php echo $data->user->name;?>" width="140" height="140"/>
                    </div>
                <?php endif;?>
            </div>

            <div class="f90pro-right span9 col-lg-9 col-md-9 col-6">
                <div class="f90pro-user">
                    <h1 class="f90pro-display-name">
                        <?php echo $data->user->name;?>
                    </h1>
                    <h4>
                      <?php
                      $u = JFactory::getUser($data->user->id);
                      $province = JoomprofileProfileViewJsonUser::getProvinceName($u->province);
                      echo $province;
                      ?>
                    </h4>

                      <div style="display:none;" class="number-star-user" id="number-star-user-<?php echo $u->id; ?>">
                        <?php
                        $field_values = JoomprofileProfileViewJsonUser::getFieldsValue($u->id);
                        foreach ($field_values as $vl) {
                          if ($vl->field_id == 2) {
                            $rate_star = $vl->value;
                          }
                        }
                        echo $u->star;

                         ?>
                      </div>
                      <!-- <div class="rating">
                        <input type="radio" id="1" name="star" value="1">
                        <input type="radio" id="2" name="star" value="2">
                        <input type="radio" id="3" name="star" value="3">
                        <input type="radio" id="4" name="star" value="4">
                        <input type="radio" id="star5" name="star" value="5">
                      </div> -->

                      <div class="wapper-rating ">
                        <i class="fa fa-star-o star star1" id="1" aria-hidden="true"></i>
                        <i class="fa fa-star-o star star2" id="2" aria-hidden="true"></i>
                        <i class="fa fa-star-o star star3" id="3" aria-hidden="true"></i>
                        <i class="fa fa-star-o star star4" id="4" aria-hidden="true"></i>
                        <i class="fa fa-star-o star star5" id="5" aria-hidden="true"></i>
                      </div>

                      <script>
                      jQuery(document).ready(function () {
                          jQuery(".star").click(function(){
                            var star = jQuery(this).attr('id');
                            jQuery.ajax({
                              url: "<?php echo JUri::root().'index.php?option=com_joomprofile&view=profile&task=user.ratingStar';?>",
                              method: "POST",
                              data:{user_id:<?php echo $data->user->id;?>,user_rating_id:<?php echo $user->id;?>,star:star},
                              success:function(data){
                                if (data == 1) {
                                  alert("Đánh giá sao thành công!");
                                }
                                if (data == 0) {
                                  alert("Đánh giá sao thất bại!");
                                }
                                if (data == 2) {
                                  alert("Bạn phải đăng nhập trước");
                                }
                                if (data == 3) {
                                  alert("Bạn đã đánh giá cho tư vấn viên này!");
                                }
                                if (data == 4) {
                                  alert("Không thể tự đánh giá cho chính mình!");
                                }
                              }
                            })
                              // var data_test = 'This is first demo';
                              // $.ajax({
                              //     url: 'rating.php',
                              //     type: 'POST',
                              //     data: 'string=' + data_test,
                              //     success: function (data) {
                              //         setTimeout(function(){
                              //             $('#demo-ajax').html(data);
                              //         }, 3000);
                              //     },
                              //     error: function (e) {
                              //         console.log(e.message);
                              //     }
                              // });
                              // var data_test = jQuery(this).attr('id');
                              // alert(star);
                          });
                      });
                     </script>


                      <script>
                      jQuery(document).ready(function(){
                        var number_star_user = jQuery('#number-star-user-<?php echo $u->id; ?>').html();
                        if(number_star_user > 0){
                          var i;
                          for (i = 1; i <= number_star_user; i++) {
                            jQuery('.wapper-rating #'+i).addClass('fa-star');
  													jQuery('.wapper-rating #'+i).removeClass('fa-star-o');
                          }
                        }
                      });
                      </script>


                    <div class="f90pro-usermeta clearfix ">
                        <ul class="clearfix ">
                            <?php if($data->loggedin) : ?>
                                <li class="f90pro-online-status f90-online"><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_ONLINE');?></li>
                                <!-- f90-online , f90-offline, f90-away; @TODO -->
                            <?php else : ?>
                                <li class="f90pro-online-status f90-offline"><i class="fa fa-user"></i> <?php echo JText::_('COM_JOOMPROFILE_OFFLINE');?></li>
                            <?php endif;?>

                            <?php $regdate = new JDate($data->user->registerDate);?>
                            <li class="f90pro-user-since "><i class="fa fa-clock-o"></i> <?php echo JText::_('COM_JOOMPROFILE_MEMBER_SINCE');?> : <?php echo $regdate->format('d-M-Y');?></li>
                            <!-- <li class="f90pro-location "><i class="fa fa-globe"></i> India</span></li> -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- HEADER END -->

        <!-- DETAILS START -->
        <div id="f90pro-detail" class="f90pro-detail row-fluid clearfix col-md-12">
            <!-- Tabs Left : <li> "href = fieldgroup container ID" will be with prefix 'f90pro-fgtab-XXX' -->
            <!-- <div class="f90pro-left clearfix span3 col-lg-3 col-md-3 col-sm-4 col-xs-12 ">
                <ul id="f90pro-fieldgroup-tab" class="f90pro-stack-nav">
                    <?php $counter = 0;?>
                    <?php if(!empty($data->fieldgroups)):?>
                        <?php foreach($data->fieldgroups as $group):?>
                            <?php $group = $group-> toObject();?>
                            <li <?php echo (!$data->fieldgroup_id && $counter === 0) || ($data->fieldgroup_id == $group->id) ? 'class="active"' : '';?>>
                                <a data-toggle="tab" href="#f90pro-fgtab-<?php echo $group->id;?>">
                                    <i class="<?php echo isset($group->params['icon_class']) ? $group->params['icon_class'] : '';?>"> </i>
                                    <span class="f90pro-sn-tabname"> <?php echo JText::_($group->title);?></span>
                                </a>
                            </li>
                            <?php $counter++;?>
                        <?php endforeach;?>
                    <?php else:?>
                        <br/>
                        <h3><?php echo JText::_('There are no fieldgroups configured for you.');?></h3>
                    <?php endif;?>
                </ul>
            </div> -->


            <!-- All Fields Content : cantains fieldgroup in tab-->
            <div class="f90pro-right clearfix span12 col-lg-12 col-md-12">
                <div class="f90pro-fgtab-content tab-content">
                    <?php if(!empty($data->fieldgroups)):?>
                        <?php $counter = 0;?>
                        <?php foreach($data->fieldgroups as $group):?>
                            <?php list($fields, $mapping) = $group->getFieldsAndMappings();?>
                            <?php $group = $group->toObject();?>
                            <div id="f90pro-fgtab-<?php echo $group->id;?>" class="tab-pane <?php echo (!$data->fieldgroup_id && $counter === 0) || ($data->fieldgroup_id == $group->id) ? 'active' : '';?>" >
                                <div id="joomprofile-fieldgroup-<?php echo $group->id;?>">
                                    <?php if(count($fields) > 0):?>
                                        <?php
                                        ob_start();
                                        $showEditButton = false;
                                        ?>
                                          <?php
                                          $num=1;
                                          $linkfb = "";
                                          $numstar = 0;
                                          $i = 0;
                                          $len = count($fields);
                                          ?>
                                        <?php foreach($fields as $field_fieldgroup_id => $field):
                                          // echo "<pre>";
                                          // print_r($field->getClassCSS());
                                          // echo "</pre>";
                                          ?>
                                            <?php if(!$field->getPublished() || (isset($mapping[$field_fieldgroup_id]) && !$mapping[$field_fieldgroup_id]->visible &&  !$data->can_edit)):?>
                                                <?php continue;?>
                                            <?php endif;?>

                                            <?php $fieldObj =$field->toObject();?>
                                            <?php $field_instance = JoomprofileLibField::get($fieldObj->type);?>

                                            <?php if (!$showEditButton && $field_instance->showEditButton($fieldObj, $data->user->id)) : ?>
                                                <?php $showEditButton = true; ?>
                                            <?php endif;?>

                                            <?php $fieldValue = $field_instance->getViewHtml($fieldObj, isset($data->field_values[$fieldObj->id]) ? $data->field_values[$fieldObj->id] : '', $data->user->id);?>
                                            <?php if((isset($data->can_edit) && $data->can_edit) || (!$data->isAdmin && $data->isProfileEditor) || !isset($data->config['show_blank_field']) || $data->config['show_blank_field'] || ($fieldValue !== null && $fieldValue !== '') ):?>
                                                <?php
                                                $id_field = $field->getIDField();
                                                if ($id_field == 2) {
                                                  $numstar = $fieldValue;
                                                }
                                                if ($id_field == 11) {
                                                  $linkfb = $fieldValue;
                                                  continue;
                                                }
                                                ?>
                                                <div class="<?php echo $field->getClassCSS(); ?>" id="<?php echo "f90pro-field-display-".$id_field; ?>">
                                                <div class="f90pro-field-display row-fluid clearfix " >
                                                    <?php if(!isset($fieldObj->params['show_label']) || intval($fieldObj->params['show_label'])) : ?>
                                                        <div class="f90pro-field-name span3 col-lg-3 col-md-3 col-sm-5 col-xs-12 ">
                                                            <h4 class="">
                                                                <?php echo JText::_($fieldObj->title);?>
                                                            </h4>
                                                        </div>
                                                        <div class="f90pro-field-content span9 col-lg-9 col-md-9 col-sm-7 col-xs-12 ">
                                                            <?php echo $fieldValue;?>

                                                            <?php
                                                            if ($id_field == 10) {
                                                              echo "<br>Ngày đăng ký: ".date("d-m-Y", strtotime($u->registerDate));
                                                            }
                                                            ?>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="f90pro-field-content span12 col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                            <?php echo $fieldValue;?>

                                                        </div>
                                                    <?php endif; ?>

                                                </div>
                                              </div>

                                            <?php endif;?>

                                        <?php endforeach;?>
                                        <?php

                                        $contents = ob_get_contents();
                                        ob_end_clean();
                                        ?>

                                        <?php if($showEditButton && ((isset($data->can_edit) && $data->can_edit) || (!$data->isAdmin && $data->isProfileEditor))):?>

                                          <div class="f90pro-header row-fluid clearfix ">
                                            <div class="f90pro-left span3 col-lg-3 col-md-3 col-sm-4 col-xs-12 col-6">
                                              <?php echo "&nbsp"; ?>
                                            </div>

                                            <div class="f90pro-right span9 col-lg-9 col-md-9 col-6">
                                              <div class="f90pro-user">
                                                <span class="f90pro-edit">
                                                    <a class="btn-f90pro btn btn-info" href="#" onClick="joomprofile.profile.getfieldGroupEditHtml(<?php echo $data->user->id;?>, <?php echo $group->id;?>); return false;">
                                                        <i class="fa fa-edit"></i>
                                                        <?php echo (!$data->isAdmin && $data->isProfileEditor && !$data->can_edit)
                                                            ? JText::_('COM_JOOMPROFILE_EDIT_AS_ADMIN')
                                                            : JText::_('COM_JOOMPROFILE_EDIT');?>
                                                    </a>
                                                </span>
                                              </div>
                                            </div>
                                          </div>



                                      <?php endif;?>

                                        <?php echo $contents;?>
                                    <?php else:?>
                                        <?php echo JText::_('COM_JOOMPROFILE_NO_FIELDS');?>
                                    <?php endif;?>
                                </div>
                            </div>
                            <?php $counter++;?>
                        <?php endforeach;?>
                    <?php else:?>
                        <br/>
                        <?php echo JText::_('COM_JOOMPROFILE_NO_FIELDGRUOPS');?>
                    <?php endif;?>
                </div>
            </div>
        </div>
      </div>
      <div class="f90pro-wrapper-right col-md-3 col-lg-3">
        <h4>Chi tiết liên hệ</h4>
        <div class="deep-detail-contact-profile">
          <span class="deep-detail-username"><i class="fa fa-phone"></i><?php echo $data->user->username;?></span><br>
          <span class="deep-detail-email"><i class="fa fa-envelope"></i><?php echo $data->user->email;?></span><br>
          <span class="deep-detail-address"><i class="fa fa-map-marker market"></i>
            <?php
            $u = JFactory::getUser($data->user->id);
            $province = JoomprofileProfileViewJsonUser::getProvinceName($u->address);
            echo $province;
            ?>
          </span><br>
          <span class="deep-detail-facebook"><i class="fa fa-facebook-square"></i><?php echo $linkfb; ?>
          </span><br>
        </div>

        <div class="send-request-support">
          <button type="button" name="button">
            <!-- <a href="index.php?Itemid=196">Gửi yêu cầu tư vấn</a> -->
            <a href="<?php echo JRoute::_('index.php?Itemid=196&user_id='.$data->user->id.'');?>">Gửi yêu cầu tư vấn</a>
          </button>
        </div>

        <div class="can-you-care col-md-12">
          <h4>Có thể bạn quan tâm</h4>
          <?php foreach ($data->otherUser as $key => $o_user): ?>
            <?php
            $province = JoomprofileProfileViewJsonUser::getProvinceName($o_user->province);
            $field_values = JoomprofileProfileViewJsonUser::getFieldsValue($o_user->id);
             ?>
            <div class="other-you-care col-md-12">
              <div class="avt-other col-md-4 col-5">
                <?php
                  $has_image = 0;
                  $link_image = '';
                  $star = 1;
                  $experience = 0;
                  $position = " ";
                  foreach ($field_values as  $val) {
                    if ($val->field_id == 7) {
                      $has_image = 1;
                      $link_image = $val->value;
                    }
                    if ($val->field_id == 2) {
                      $star = $val->value;
                    }
                    if (($val->field_id == 1)) {
                      $experience = $val->value;
                    }
                    if (($val->field_id == 4)) {
                      $position = $val->value;
                    }
                  }
                  if ($has_image == 1) {?>
                    <a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$o_user->id);?>"><?php
                    echo '<img src="'.JUri::root().$link_image.'" alt="'.$o_user->name.'" width="140" height="140">'; ?>
                  </a><?php
                }else{ ?>
                  <a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$o_user->id);?>"><?php
                    echo '<img src="'.JUri::root().'media/com_joomprofile/images/default.png" alt="'.$o_user->name.'">'; ?>
                  </a> <?php
                  }
                 ?>
              </div>
              <div class="name-other col-md-8 col-7">
                <div class="other-item">
                  	<a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$o_user->id);?>">
                  <?php echo $o_user->name; ?>
                    </a>
                </div>
                <?php echo '<div class="other-item">'. $province. "</div>"; ?>
                <div style="display:none" id="number-star-other-<?php echo $o_user->id;?>" class="other-item number-star-other-<?php echo $o_user->id;?>">
                  <?php echo $o_user->star; ?>
                </div>
                <div class="other-item wapper-rating-other-<?php echo $o_user->id; ?>">
                    <i class="fa fa-star-o star1" id="star-1" aria-hidden="true"></i>
                    <i class="fa fa-star-o star2" id="star-2" aria-hidden="true"></i>
                    <i class="fa fa-star-o star3" id="star-3" aria-hidden="true"></i>
                    <i class="fa fa-star-o star4" id="star-4" aria-hidden="true"></i>
                    <i class="fa fa-star-o star5" id="star-5" aria-hidden="true"></i>
                </div>
                <div class="other-item"><i class="fa fa-briefcase" aria-hidden="true"></i><?php echo " Kinh nghiệm: ".$experience. " năm"; ?></div>
                <div class="other-item"><i class="fa fa-user-md" aria-hidden="true"></i><?php echo " Vị trí ". $position; ?></div>
                <script>
                jQuery(document).ready(function(){
                  var number_star_user = jQuery('#number-star-other-<?php echo $o_user->id; ?>').html();
                  if(number_star_user > 0){
                    var i;
                    for (i = 1; i <= number_star_user; i++) {
                      ///jQuery('.wapper-rating-other-<?php echo $o_user->id; ?> #star-'+i).addClass('active-star');
                      jQuery('.wapper-rating-other-<?php echo $o_user->id; ?> #star-'+i).addClass('fa-star');
                      jQuery('.wapper-rating-other-<?php echo $o_user->id; ?> #star-'+i).removeClass('fa-star-o');
                    }
                  }
                });
                </script>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php
      if (($data->user->id) != ($user->id)) { ?>
        <style>
        #f90pro-field-display-7{
          display: none;
        }
        </style>
    <?php } ?>
    <script>
    var current_url = window.location.href;
    var position = current_url.indexOf('/administrator/');
    if (position > 0) {
      jQuery('.joomprofile-admin .f90pro-wrapper-right').hide();
    }
    </script>
<?php
