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
$config = new JConfig();
?>
<!-- Start K2 Item Layout -->
<div class="catItemView group<?php echo ucfirst($this->item->itemGroup); ?><?php echo ($this->item->featured) ? ' catItemIsFeatured' : ''; ?><?php if($this->item->params->get('pageclass_sfx')) echo ' '.$this->item->params->get('pageclass_sfx'); ?>">
    <!-- Plugins: BeforeDisplay -->
    <?php echo $this->item->event->BeforeDisplay; ?>

    <!-- K2 Plugins: K2BeforeDisplay -->
    <?php echo $this->item->event->K2BeforeDisplay; ?>



    <?php if($this->item->params->get('catItemRating')): ?>
    <!-- Item Rating -->
    <div class="catItemRatingBlock">
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
    </div>
    <?php endif; ?>

    <div class="catItemBody">
        <!-- Plugins: BeforeDisplayContent -->
        <?php echo $this->item->event->BeforeDisplayContent; ?>

        <!-- K2 Plugins: K2BeforeDisplayContent -->
        <?php echo $this->item->event->K2BeforeDisplayContent; ?>

        <?php if($this->item->params->get('catItemImage') && !empty($this->item->image)): ?>
        <!-- Item Image -->
        <div class="catItemImageBlock">
            <span class="catItemImage">

                <?php if($this->item->catid == CATEGORY_VIDEO || (isset($config->showed_top_item) &&  in_array($this->item->catid, $config->showed_top_item))){ ?>
                <a href="#" data-toggle="modal" data-target="#videoModal" data-whatever="<?php echo htmlspecialchars($this->item->video) ?>">
                    <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
                    <div class="play-icon"><svg width="60" height="42" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <g> <title>Videos</title> <g id="icomoon-ignore"/>
                        <use x="5.397434" y="-68.326835" transform="matrix(0.15880563740596462,0,0,0.1590301359360811,-34.233496722840435,-44.6817534019825) " id="icon" xlink:href="#svg_1"/> <path id="svg_4" d="m24,8.380953l0.190475,22.761904l16.952381,-11.333332l-17.142857,-11.428572z" stroke-linecap="null" stroke-linejoin="null" stroke-width="5" stroke="null" fill="#ffffff"/> <path id="relleno" d="m24.285713,8.666666l0,22.666666l17.238094,-11.523809l-17.238094,-11.142857z" stroke-linecap="null" stroke-linejoin="null" stroke-width="5" stroke="null" fill="#ffffff"/> </g> <defs> <svg id="svg_1" viewBox="0 0 944 1024" height="1024" width="944" xmlns:xlink="http://www.w3.org/1999/xlink"> <g id="icomoon-ignore"/> <path id="play-svg" d="m589.426025,406.15799c0,-31.289978 -25.345032,-56.652985 -56.618042,-56.652985h-265.616974c-31.27301,0 -56.618011,25.359985 -56.618011,56.652985v151.894989c0,31.290039 25.345001,56.653015 56.618011,56.653015h265.616974c31.273987,0 56.618042,-25.361023 56.618042,-56.653015v-151.894989l0,0zm-227.311035,140.032013v-142.677002l108.192017,71.339996l-108.19101,71.339996l-0.001007,-0.002991z"/> </svg> </defs> </svg>
                    </div>
                </a>
                <?php }else{ ?>
                <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
                    <img src="<?php echo $this->item->image; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
                </a>
                <?php }?>
            </span>
            <div class="clr"></div>
        </div>
        <?php elseif($this->item->params->get('catItemImage') && empty($this->item->image)): ?>
        <!-- Item Image -->
        <!-- <div class="catItemImageBlock">
            <span class="catItemImage">
            <a href="<?php echo $this->item->link; ?>" title="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>">
            <img src="images/default-news.png" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" style="width:<?php echo $this->item->imageWidth; ?>px; height:auto;" />
            </a>
        </span>
            <div class="clr"></div>
        </div> -->
        <?php endif; ?>



        <div class="clr"></div>

        <?php if($this->item->params->get('catItemExtraFields') && isset($this->item->extra_fields) && count($this->item->extra_fields)): ?>
        <!-- Item extra fields -->
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
        <?php endif; ?>

        <!-- Plugins: AfterDisplayContent -->
        <?php echo $this->item->event->AfterDisplayContent; ?>

        <!-- K2 Plugins: K2AfterDisplayContent -->
        <?php echo $this->item->event->K2AfterDisplayContent; ?>

        <div class="clr"></div>
    </div>

    <div class="catItemHeader">


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
            <?php
            if(in_array($this->item->category->id, $config->categories_faq)){ ?>
              <a href="#" class="show-faq" id="show-faq-<?php echo $this->item->id; ?>" onclick="showFaq(<?php echo $this->item->id; ?>)">
                  <?php echo $this->item->title; ?>
              </a>
            <?php
            }else{
            ?>
            <a href="<?php echo $this->item->link; ?>">
                <?php echo $this->item->title; ?>
            </a>
          <?php } ?>
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

        <?php if($this->item->params->get('catItemDateCreated')): ?>
        <!-- Date created -->
        <span class="catItemDateCreated">
            <?php echo JHTML::_('date', $this->item->created , JText::_('K2_DATE_FORMAT_LC2')); ?>
        </span>
        <?php endif; ?>

        <?php if($this->item->params->get('catItemIntroText')): ?>
        <!-- Item introtext -->
        <div class="catItemFullText" id="catItemFullText-<?php echo $this->item->id; ?>">
          <?php if($this->item->fulltext !=''):
            echo $this->item->fulltext;
            ?>
          <?php else:
            echo $this->item->introtext;
          ?>
          <?php endif; ?>
          <div class="author-bottom">
              <div style="text-align:left" class="title-author">Tác giả</div>
              <a rel="author" href="<?php echo $this->item->author->link; ?>">
                <img class="image-avatar float-left" src="<?php echo $this->item->author->avatar; ?>" onerror="if (this.src != 'error.jpg') this.src = '<?php echo JURI::root().'images/avatark2/avatardefault.png?v=118062022' ?>'" alt="<?php echo K2HelperUtilities::cleanHtml($this->item->author->name); ?>" />
              </a>
              <div class="float-left info-author">
              <h3 class=""><a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
              <span style="font-size: 15px;"><?php echo $this->item->author->profile->description; ?></span>
              </h3>
              </div>
          </div>
        </div>
        <div class="catItemIntroText">

        <?php
        //echo $this->item->introtext;
        $this->item->introtext = strip_tags($this->item->introtext);
        $more_text = '';
        if(strlen($this->item->introtext) >= 300){
          $more_text = ' ...';
        }
        echo mb_substr($this->item->introtext,0,300, "utf-8").$more_text;
        ?>
        </div>
        <?php endif; ?>

        <?php if($this->item->params->get('catItemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
        <!-- Anchor link to comments below -->
        <div class="catItemCommentsLink">
            <?php if(!empty($this->item->event->K2CommentsCounter)): ?>
            <!-- K2 Plugins: K2CommentsCounter -->
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
        </div>
        <?php endif; ?>

        <?php if($this->item->params->get('catItemAuthor')): ?>
        <!-- Item Author -->
        <span class="catItemAuthor">
            <?php echo K2HelperUtilities::writtenBy($this->item->author->profile->gender); ?>
            <?php if(isset($this->item->author->link) && $this->item->author->link): ?>
            <a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
            <?php else: ?>
            <?php echo $this->item->author->name; ?>
            <?php endif; ?>
        </span>
        <?php endif; ?>
    </div>
    <div class="catItemIntroText-mobile">
    <?php

    $this->item->introtext = strip_tags($this->item->introtext);
    $more_text = '';
    if(strlen($this->item->introtext) >= 300){
      $more_text = ' ...';
    }
    echo mb_substr($this->item->introtext,0,300, "utf-8").$more_text;
    ?>
    </div>

    <!-- Plugins: AfterDisplayTitle -->
    <?php echo $this->item->event->AfterDisplayTitle; ?>

    <!-- K2 Plugins: K2AfterDisplayTitle -->
    <?php echo $this->item->event->K2AfterDisplayTitle; ?>


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
            <span class="catItemHits">
                <?php echo JText::_('K2_READ'); ?> <b><?php echo $this->item->hits; ?></b> <?php echo JText::_('K2_TIMES'); ?>
            </span>
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
        <div class="catItemTagsBlock">
            <span><?php echo JText::_('K2_TAGGED_UNDER'); ?></span>
            <ul class="catItemTags">
                <?php foreach ($this->item->tags as $tag): ?>
                <li><a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a></li>
                <?php endforeach; ?>
            </ul>
            <div class="clr"></div>
        </div>
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
    <div class="catItemVideoBlock">
        <h3><?php echo JText::_('K2_RELATED_VIDEO'); ?></h3>
        <?php if($this->item->videoType=='embedded'): ?>
        <div class="catItemVideoEmbedded">
            <?php echo $this->item->video; ?>
        </div>
        <?php else: ?>
        <span class="catItemVideo"><?php echo $this->item->video; ?></span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php if($this->item->params->get('catItemImageGallery') && !empty($this->item->gallery)): ?>
    <!-- Item image gallery -->
    <div class="catItemImageGallery">
        <h4><?php echo JText::_('K2_IMAGE_GALLERY'); ?></h4>
        <?php echo $this->item->gallery; ?>
    </div>
    <?php endif; ?>

    <div class="clr"></div>



    <?php if ($this->item->params->get('catItemReadMore')): ?>
    <!-- Item "read more..." link -->
    <!-- <div class="catItemReadMore">
            <a class="k2ReadMore" href="<?php echo $this->item->link; ?>">
                <?php echo JText::_('K2_READ_MORE'); ?>
            </a>
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

    <div class="clr"></div>
</div>
<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<!-- End K2 Item Layout -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
(function ( $ ) {
    $('#videoModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever') // Extract info from data-* attributes
  var modal = $(this)
//   modal.find('.modal-title').html('New message to ' + recipient)
  modal.find('.modal-body').html(recipient)
})
}( jQuery ));
</script>
<script>
function showFaq(id){
  var is_mobile = 0;
  if(jQuery( "body" ).hasClass( "mobile" )){
    is_mobile  = 1;
  }
  if(is_mobile == 0){
    var catItemFullText = jQuery('#catItemFullText-'+id).html();
    jQuery('.show-faq').removeClass("active");
    jQuery('#show-faq-'+id).addClass("active");
    jQuery('#faq-result-content').html(catItemFullText);
    jQuery('html, body').animate({
          scrollTop: jQuery(".itemList-right").offset().top - 50
      }, 1000);
  }else{
    jQuery('.show-faq').removeClass("active");
    jQuery('#show-faq-'+id).addClass("active");

    if (jQuery('#catItemFullText-'+id).is(':visible')) {
			jQuery('#catItemFullText-'+id).css("display","none");
      jQuery('html, body').animate({
            scrollTop: jQuery("#show-faq-"+id).offset().top - 50
        }, 1000);
		}else{
      jQuery('.catItemFullText').css("display","none");
			jQuery('#catItemFullText-'+id).css("display","block");
      jQuery('html, body').animate({
            scrollTop: jQuery("#catItemFullText-"+id).offset().top - 70
        }, 1000);
		}



  }

}

jQuery(document).ready(function(){
  var is_mobile = 0;
  if(jQuery( "body" ).hasClass( "mobile" )){
    is_mobile  = 1;
  }
  if(is_mobile == 0){
    var catItemFullTextFirst =  jQuery('#itemListPrimary .first-row .catItemFullText').html();
    jQuery('#faq-result-content').html(catItemFullTextFirst);
    jQuery('#itemListPrimary .first-row .show-faq').addClass("active");
  }

});
</script>
<style>
    .modal-body iframe{height: 315px;}
    <?php
    if(in_array($this->item->category->id, $config->categories_faq)){ ?>
    div.catItemCommentsLink,.catItemIntroText,.catItemDateCreated{
      display:none;
    }
    #k2Container div.catItemView{
      padding:0px;
    }
    #k2Container .itemContainer{
      margin-bottom: 0px;
    }
    #k2Container div.catItemView .catItemTitle{
      min-height: 20px;
    }
    #k2Container .itemContainer{
      box-shadow: none;
    }
    #k2Container .catItemTitle a.show-faq{
      color:#646464!important;
      font-weight: normal;
    }
    #itemListPrimary .item .groupPrimary .catItemHeader .catItemTitle {
      min-height: 20px;
    }

    @media (max-width: 960px) {
      #itemListPrimary .item .groupPrimary .catItemHeader{
        padding-top: 0px;
      }
      #itemListPrimary .item .groupPrimary .catItemIntroText-mobile{
        display:none!important;
      }
      .itemListCategory h2 {
        margin-left: 0px!important;
      }
      #k2Container .itemList-left h3.faq{
        margin-right: 0px;
      }
      .itemList-left {
        padding-right: 0px;
      }
      .item .groupPrimary .catItemHeader {
        padding-bottom: 5px;
      }
    }

    <?php } ?>
</style>
