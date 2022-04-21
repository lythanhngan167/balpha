<?php
/**
 * @version    2.10.x
 * @package    K2
 * @author     JoomlaWorks https://www.joomlaworks.net
 * @copyright  Copyright (c) 2006 - 2020 JoomlaWorks Ltd. All rights reserved.
 * @license    GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

// Define default image size (do not change)
K2HelperUtilities::setDefaultImage($this->item, 'itemlist', $this->params);
// foreach ($this->item->extra_fields as $key => $extraField):
//     echo "<pre>";
//     print_r($extraField);
//     echo "</pre>";
// endforeach;
?>

<!-- Start K2 Item Layout -->
<div class="catItemView group<?php echo ucfirst($this->item->itemGroup); ?><?php echo ($this->item->featured) ? ' catItemIsFeatured' : ''; ?><?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">
    <!-- Plugins: BeforeDisplay -->
    <?php echo $this->item->event->BeforeDisplay; ?>

    <!-- K2 Plugins: K2BeforeDisplay -->
    <?php echo $this->item->event->K2BeforeDisplay; ?>
    <?php if($this->item->params->get('catItemExtraFields') && isset($this->item->extra_fields) && count($this->item->extra_fields)): ?>
    <?php $item_logo = '';

    foreach ($this->item->extra_fields as $key => $extraField):
        // print_r($extraField);
      if($extraField->id == 13){
        $insurance_benefits = $extraField->value;
      }
      if($extraField->id == 14){
        $waiting_time = $extraField->value;
      }
      if($extraField->id == 15){
        $hospital_intro = $extraField->value;
      }
      if($extraField->id == 16){
        $hospital_details = $extraField->value;
      }
      if($extraField->id == 17){
        $insurance_fees_intro = $extraField->value;
      }
      if($extraField->id == 18){
        $insurance_fees_details = $extraField->value;
      }
      if($extraField->id == 19){
        $support_link = $extraField->value;
      }
      if($extraField->id == 10){
        $Coverage_health = $extraField->value;
      }
    endforeach;
    ?>

    <?php endif; ?>

    <div class="insurance-company">
        <?php if($this->item->params->get('catItemDateCreated')): ?>
        <!-- Date created -->
        <!-- <span class="catItemDateCreated">
            <?php echo JHTML::_('date', $this->item->created , JText::_('K2_DATE_FORMAT_LC2')); ?>
        </span> -->
        <?php endif; ?>
        <?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
        <!-- Item Image -->
        <div class="catItemImageBlock">
            <span class="catItemImage">
                <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
                    <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
                </a>
            </span>
            <!-- <div class="clr"></div> -->
        </div>
        <?php elseif($this->item->params->get('catItemImage') && empty($this->item->image)): ?>
        <!-- Item Image -->
        <div class="catItemImageBlock">
            <span class="catItemImage">
            <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
            <img src="images/insurance-default.jpg" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
            </a>
        </span>
            <div class="clr"></div>
        </div>
        <?php endif; ?>

        <div class="health-title">
            <?php if($this->item->params->get('catItemTitle')): ?>
            <!-- Item title -->
            <h3 class="catItemTitle">
                <?php if(isset($this->item->editLink)): ?>
                <!-- Item edit link -->
                <span class="catItemEditLink">
                    <a data-k2-modal="edit" href="<?php echo $this->item->editLink; ?>">
                        <?php echo JText::_('K2_EDIT_ITEM'); ?>
                    </a>
                </span>
                <?php endif; ?>

                <?php if ($this->item->params->get('catItemTitleLinked')): ?>
                <a href="<?php echo $this->item->link; ?>">
                    <?php echo $this->item->title; ?>
                </a>
                <?php else: ?>
                <?php echo $this->item->title; ?>
                <?php endif; ?>

                <?php if($this->item->params->get('catItemFeaturedNotice') && $this->item->featured): ?>
                <!-- Featured flag -->
                <span>
                    <sup>
                        <?php echo JText::_('K2_FEATURED'); ?>
                    </sup>
                </span>
                <?php endif; ?>
            </h3>
            <?php endif; ?>
        </div>
    </div>

        <div class="benefits-Waiting-time">
            <div class="health-insurance-benefits">

                <div class="insurance-benefits"><?php echo $insurance_benefits; ?></div>
                <?php if($this->item->params->get('catItemAuthor')): ?>
                <!-- Item Author -->
                <!-- <span class="catItemAuthor">
                    <?php echo K2HelperUtilities::writtenBy($this->item->author->profile->gender); ?>
                    <?php if(isset($this->item->author->link) && $this->item->author->link): ?>
                    <a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
                    <?php else: ?>
                    <?php echo $this->item->author->name; ?>
                    <?php endif; ?>
                </span> -->
                <?php endif; ?>
            </div>
            <div class="Waiting-time-mobile">
                <span>Thời gian chờ</span>
            </div>
            <div class="Waiting-time">
                <?php echo  $waiting_time; ?>
            </div>
        </div>
        <div class="hospital-fees">
            <div class="Affiliated-hospital">
                    <div class="hospital-intro">
                        <?php echo $hospital_intro; ?>
                    </div>
                    <div class="hospital-intro-mobile">
                        <span><?php echo $hospital_intro; ?> bệnh viện liên kết</span>
                    </div>

                <div class="icon-hospital-intros" data-toggle="modal" data-target="#myModal-<?php echo $this->item->id; ?>">
                    <i class="fa fa-angle-down"></i>
                </div>

                <div class="modal" id="myModal-<?php echo $this->item->id; ?>" role="dialog">
                  <div class="modal-dialog">
                    <form name="reasonFrom" id="reasonForm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Bệnh viện liên kết</h4>
                      </div>
                      <div class="modal-body">
                        <?php echo $hospital_details ?>
                        <?php if ($hospital_details==""||$hospital_details==null||$hospital_details==undefine): ?>
                          <script>
                              jQuery(".icon-hospital-intros").hide();
                          </script>
                         <?php endif; ?>
                      </div>
                    </div>
                    </form>
                  </div>
                </div>
            </div>

            <div class="Insurance-fees">
                <div class="insurance-fees-intro">
                    <?php echo $insurance_fees_intro; ?>
                </div>
                <div class="insurance-fees-intro-mobile">
                    <span><b>Phạm vi bảo hiểm: </b><?php echo $insurance_fees_intro; ?></span>
                </div>
                <?php if ($insurance_fees_details==""): ?>
                  <script>
                     jQuery(".icon-insurance-fees-intro").hide();
                  </script>

                 <?php endif; ?>

                  <div class="icon-insurance-fees-intro" data-toggle="modal" data-target="#myModalfees-<?php echo $this->item->id; ?>">
                          <i class="fa fa-angle-down"></i>
                  </div>
                <div class="modal" id="myModalfees-<?php echo $this->item->id; ?>" role="dialog">
                  <div class="modal-dialog">
                    <form name="reasonFrom" id="reasonForm">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Phí bảo hiểm</h4>
                      </div>
                      <div class="modal-body">
                         <?php echo $insurance_fees_details; ?>
                         <?php if ($insurance_fees_details==""): ?>
                           <script>
                              jQuery(".icon-insurance-fees-intro").hide();
                           </script>

                          <?php endif; ?>

                      </div>
                    </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>

    <div class="Coverage-health-mobile"><p> Phạm vi bảo hiểm: <?php echo $Coverage_health; ?></p></div>

    <?php if ($this->item->params->get('catItemReadMore')): ?>
    <!-- Item "read more..." link -->
    <div class="support">
      <div class="button_bot">
  			<span id="support_link"><a href="<?php echo LANDINGPAGE_LINK; ?>" target="_blank">Đăng ký tư vấn</a></span>
  			<!-- <span id="see_more"><a href="<?php echo $this->item->link; ?>">Xem thêm</a></span> -->
		  </div>
        <!-- <a class="k2ReadMore" href="<?php echo $this->item->link; ?>">
            <?php echo JText::_('K2_READ_MORE'); ?>
        </a> -->
    </div>
    <?php endif; ?>


    <!-- Plugins: AfterDisplayTitle -->
    <!-- <?php echo $this->item->event->AfterDisplayTitle; ?> -->

    <!-- K2 Plugins: K2AfterDisplayTitle -->
    <!-- <?php echo $this->item->event->K2AfterDisplayTitle; ?> -->

    <?php if($this->item->params->get('catItemRating')): ?>
    <!-- Item Rating -->
    <!-- <div class="catItemRatingBlock">
        <span><?php echo JText::_('K2_RATE_THIS_ITEM'); ?></span>
        <div class="itemRatingForm">
            <ul class="itemRatingList">
                <li class="itemCurrentRating" id="itemCurrentRating<?php echo $this->item->id; ?>" style="width:<?php echo $this->item->votingPercentage; ?>%;"></li>
                <li><a href="#" data-id="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_1_STAR_OUT_OF_5'); ?>" class="one-star">1</a></li>
                <li><a href="#" data-id="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_2_STARS_OUT_OF_5'); ?>" class="two-stars">2</a></li>
                <li><a href="#" data-id="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_3_STARS_OUT_OF_5'); ?>" class="three-stars">3</a></li>
                <li><a href="#" data-id="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_4_STARS_OUT_OF_5'); ?>" class="four-stars">4</a></li>
                <li><a href="#" data-id="<?php echo $this->item->id; ?>" title="<?php echo JText::_('K2_5_STARS_OUT_OF_5'); ?>" class="five-stars">5</a></li>
            </ul>
            <div id="itemRatingLog<?php echo $this->item->id; ?>" class="itemRatingLog"><?php echo $this->item->numOfvotes; ?></div>
            <div class="clr"></div>
        </div>
        <div class="clr"></div>
    </div> -->
    <?php endif; ?>
    <!-- <div id="hospital-body" class="hospital-body">
      <div>
        <a href="javascript:void(0)" class="closebtn">×</a>
        <div class="hospital-details">
            <?php echo $hospital_details; ?>
        </div>
      </div>
    </div> -->
    <!-- <div id="mySidenav2-1" class="sidenav2-1">
      <div>
        <a href="javascript:void(0)" class="closebtn">×</a>
        <div class="insurance-fees-details">
            <?php echo $insurance_fees_details; ?>
        </div>
      </div>
    </div> -->
    <div class="Coverage-health"><p> Phạm vi bảo hiểm: <?php echo $Coverage_health; ?></p></div>

    <div class="catItemBody">
        <!-- Plugins: BeforeDisplayContent -->
        <?php echo $this->item->event->BeforeDisplayContent; ?>

        <!-- K2 Plugins: K2BeforeDisplayContent -->
        <?php echo $this->item->event->K2BeforeDisplayContent; ?>



        <?php if($this->item->params->get('catItemIntroText')): ?>
        <!-- Item introtext -->
        <!-- <div class="catItemIntroText">
            <?php echo $this->item->introtext; ?>
        </div> -->
        <?php endif; ?>

        <div class="clr"></div>

        <!-- <?php if($this->item->params->get('catItemExtraFields') && isset($this->item->extra_fields) && count($this->item->extra_fields)): ?>

        <div class="catItemExtraFields">
            <h4><?php echo JText::_('K2_ADDITIONAL_INFO'); ?></h4>
            <ul>
                <?php foreach ($this->item->extra_fields as $key => $extraField): ?>
                <?php if($extraField->value != ''): ?>
                <li class="<?php echo ($key%2) ? "odd" : "even"; ?> type<?php echo ucfirst($extraField->type); ?> group<?php echo $extraField->group; ?> alias<?php echo ucfirst($extraField->alias); ?>">
                    <?php if($extraField->type == 'header'): ?>
                    <h4 class="catItemExtraFieldsHeader"><?php echo $extraField->name; ?></h4>
                    <?php else: ?>
                    <span class="catItemExtraFieldsLabel"><?php echo $extraField->name; ?></span>
                    <span class="catItemExtraFieldsValue"><?php echo $extraField->value; ?></span>
                    <?php endif; ?>
                </li>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
        </div>
        <?php endif; ?> -->

        <!-- Plugins: AfterDisplayContent -->
        <?php echo $this->item->event->AfterDisplayContent; ?>

        <!-- K2 Plugins: K2AfterDisplayContent -->
        <?php echo $this->item->event->K2AfterDisplayContent; ?>

        <div class="clr"></div>
    </div>

    <?php if(
        $this->item->params->get('catItemHits') ||
        $this->item->params->get('catItemCategory') ||
        $this->item->params->get('catItemTags') ||
        $this->item->params->get('catItemAttachments')
    ): ?>
    <div class="catItemLinks">
        <?php if($this->item->params->get('catItemHits')): ?>
        <!-- Item Hits -->
        <div class="catItemHitsBlock">
            <!-- <span class="catItemHits">
                <?php echo JText::_('K2_READ'); ?> <b><?php echo $this->item->hits; ?></b> <?php echo JText::_('K2_TIMES'); ?>
            </span> -->
        </div>
        <?php endif; ?>

        <?php if($this->item->params->get('catItemCategory')): ?>
        <!-- Item category name -->
        <!-- <div class="catItemCategory">
            <span><?php echo JText::_('K2_PUBLISHED_IN'); ?></span>
            <a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
        </div> -->
        <?php endif; ?>

        <?php if($this->item->params->get('catItemTags') && isset($this->item->tags) && count($this->item->tags)): ?>
        <!-- Item tags -->
        <!-- <div class="catItemTagsBlock">
            <span><?php echo JText::_('K2_TAGGED_UNDER'); ?></span>
            <ul class="catItemTags">
                <?php foreach ($this->item->tags as $tag): ?>
                <li><a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
        </div> -->
        <?php endif; ?>

        <?php if($this->item->params->get('catItemAttachments') && isset($this->item->attachments) && count($this->item->attachments)): ?>
        <!-- Item attachments -->
        <div class="catItemAttachmentsBlock">
            <span><?php echo JText::_('K2_DOWNLOAD_ATTACHMENTS'); ?></span>
            <ul class="catItemAttachments">
                <?php foreach ($this->item->attachments as $attachment): ?>
                <li>
                    <a title="<?php echo K2HelperUtilities::cleanHtml($attachment->titleAttribute); ?>" href="<?php echo $attachment->link; ?>">
                        <?php echo $attachment->title ; ?>
                    </a>
                    <?php if($this->item->params->get('catItemAttachmentsCounter')): ?>
                    <span>(<?php echo $attachment->hits; ?> <?php echo ($attachment->hits==1) ? JText::_('K2_DOWNLOAD') : JText::_('K2_DOWNLOADS'); ?>)</span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="clr"></div>
    </div>
    <?php endif; ?>

    <div class="clr"></div>

    <?php if($this->item->params->get('catItemVideo') && !empty($this->item->video)): ?>
    <!-- Item video -->
    <!-- <div class="catItemVideoBlock">
        <h3><?php echo JText::_('K2_RELATED_VIDEO'); ?></h3>
        <?php if($this->item->videoType=='embedded'): ?>
        <div class="catItemVideoEmbedded">
            <?php echo $this->item->video; ?>
        </div>
        <?php else: ?>
        <span class="catItemVideo"><?php echo $this->item->video; ?></span>
        <?php endif; ?>
    </div> -->
    <?php endif; ?>

    <?php if($this->item->params->get('catItemImageGallery') && !empty($this->item->gallery)): ?>
    <!-- Item image gallery -->
    <!-- <div class="catItemImageGallery">
        <h4><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h4>
        <?php echo $this->item->gallery; ?>
    </div> -->
    <?php endif; ?>

    <div class="clr"></div>

    <?php if($this->item->params->get('catItemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
    <!-- Anchor link to comments below -->
    <!-- <div class="catItemCommentsLink">
        <?php if(!empty($this->item->event->K2CommentsCounter)): ?>

        <?php echo $this->item->event->K2CommentsCounter; ?>
        <?php else: ?>
        <?php if($this->item->numOfComments > 0): ?>
        <a href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
            <?php echo $this->item->numOfComments; ?> <?php echo ($this->item->numOfComments>1) ? JText::_('K2_COMMENTS') : JText::_('K2_COMMENT'); ?>
        </a>
        <?php else: ?>
        <a href="<?php echo $this->item->link; ?>#itemCommentsAnchor">
            <?php echo JText::_('K2_BE_THE_FIRST_TO_COMMENT'); ?>
        </a>
        <?php endif; ?>
        <?php endif; ?>
    </div> -->
    <?php endif; ?>


    <div class="clr"></div>

    <?php if($this->item->params->get('catItemDateModified')): ?>
    <!-- Item date modified -->
    <?php if($this->item->modified != $this->nullDate && $this->item->modified != $this->item->created ): ?>
    <span class="catItemDateModified">
        <?php echo JText::_('K2_LAST_MODIFIED_ON'); ?> <?php echo JHTML::_('date', $this->item->modified, JText::_('K2_DATE_FORMAT_LC2')); ?>
    </span>
    <?php endif; ?>
    <?php endif; ?>

    <!-- Plugins: AfterDisplay -->
    <?php echo $this->item->event->AfterDisplay; ?>

    <!-- K2 Plugins: K2AfterDisplay -->
    <?php echo $this->item->event->K2AfterDisplay; ?>

    <!-- <div class="clr"></div> -->
</div>
<!-- End K2 Item Layout -->
