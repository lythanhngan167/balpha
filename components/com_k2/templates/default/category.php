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
?>
<?php
$type_insurance = '';
switch($this->category->id){
  case LIFE_INSURANCE:
  $type_insurance = 'insurance';
  break;
  case HEALTH_INSURANCE:
  $type_insurance = 'health';
  break;
  case TRAVEL_INSURANCE:
  $type_insurance = 'travel';
  break;
  case CAR_INSURANCE:
  $type_insurance = 'car';
  break;
  case HOME_INSURANCE:
  $type_insurance = 'home';
  break;
  case CRITICAL_ILLNESS_INSURANCE:
  $type_insurance = 'illness';
  break;
  default:
  $type_insurance = 'item';
  break;
}

?>

<!-- Start K2 Category Layout -->
<div id="k2Container" class="category-<?php echo $this->category->id; ?> itemListView<?php if ($this->params->get('pageclass_sfx')) echo ' '.$this->params->get('pageclass_sfx'); ?>">
    <?php if ($this->params->get('show_page_title')): ?>
    <!-- Page title -->
    <div class="componentheading<?php echo $this->params->get('pageclass_sfx')?>">
        <?php echo $this->escape($this->params->get('page_title')); ?>
    </div>
    <?php endif; ?>

    <?php if ($this->params->get('catFeedIcon')): ?>
    <!-- RSS feed icon -->
    <div class="k2FeedIcon">
        <a href="<?php echo $this->feed; ?>" title="<?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?>">
            <span><?php echo JText::_('K2_SUBSCRIBE_TO_THIS_RSS_FEED'); ?></span>
        </a>
        <div class="clr"></div>
    </div>
    <?php endif; ?>

    <?php if (isset($this->category) || ( $this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories) )): ?>
    <!-- Blocks for current category and subcategories -->
    <div class="itemListCategoriesBlock">
        <?php if (isset($this->category) && ( $this->params->get('catImage') || $this->params->get('catTitle') || $this->params->get('catDescription') || $this->category->event->K2CategoryDisplay )): ?>
        <!-- Category block -->
        <div class="itemListCategory">
            <?php if (isset($this->addLink)): ?>
            <!-- Item add link -->
            <!-- <span class="catItemAddLink">
                <a data-k2-modal="edit" href="<?php echo $this->addLink; ?>">
                    <?php echo JText::_('K2_ADD_A_NEW_ITEM_IN_THIS_CATEGORY'); ?>
                </a>
            </span> -->
            <?php endif; ?>

            <?php if ($this->params->get('catImage') && $this->category->image): ?>
            <!-- Category image -->
            <img alt="<?php echo K2HelperUtilities::cleanHtml($this->category->name); ?>" src="<?php echo $this->category->image; ?>" style="width:<?php echo $this->params->get('catImageWidth'); ?>px; height:auto;" />
            <?php endif; ?>

            <?php if ($this->params->get('catTitle')): ?>
            <!-- Category title -->
            <h2>
              <?php echo $this->category->name; ?>
              <?php if ($this->params->get('catTitleItemCounter')) echo ' ('.$this->pagination->total.')'; ?>
            </h2>
            <?php endif; ?>

            <?php if ($this->params->get('catDescription')): ?>
            <!-- Category description -->
            <div><?php echo $this->category->description; ?></div>
            <?php endif; ?>

            <!-- K2 Plugins: K2CategoryDisplay -->
            <?php echo $this->category->event->K2CategoryDisplay; ?>

            <div class="clr"></div>
        </div>
        <?php endif; ?>

        <?php if ($this->params->get('subCategories') && isset($this->subCategories) && count($this->subCategories)): ?>
        <!-- Subcategories -->
        <div class="itemListSubCategories">
            <div class="itemListSubCategories-title">
              <!-- <h3> echo JText::_('K2_CHILDREN_CATEGORIES');</h3>  -->
            <div>
            <?php foreach($this->subCategories as $key=>$subCategory): ?>
            <?php
            // Define a CSS class for the last container on each row
            if ((($key+1)%($this->params->get('subCatColumns'))==0))
                $lastContainer = ' subCategoryContainerLast';
            else
                $lastContainer = '';
            ?>
            <div class="subCategoryContainer sub-categories-knowledge<?php echo $lastContainer; ?>"<?php echo (count($this->subCategories)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('subCatColumns'), 1).'%;"'; ?>>
                <div class="subCategory">
                    <?php if ($this->params->get('subCatImage') && $subCategory->image): ?>
                    <!-- Subcategory image -->
                    <a class="subCategoryImage" href="<?php echo $subCategory->link; ?>">
                        <img alt="<?php echo K2HelperUtilities::cleanHtml($subCategory->name); ?>" src="<?php echo $subCategory->image; ?>" />
                    </a>
                    <?php endif; ?>

                    <?php if ($this->params->get('subCatTitle')): ?>
                    <!-- Subcategory title -->
                    <h2>
                        <a href="<?php echo $subCategory->link; ?>">
                            <!-- <?php echo $subCategory->name; ?><?php if ($this->params->get('subCatTitleItemCounter')) echo ' ('.$subCategory->numOfItems.')'; ?> -->
                            <?php echo $subCategory->name; ?><?php if ($this->params->get('subCatTitleItemCounter')) ; ?>

                        </a>
                    </h2>
                    <?php endif; ?>

                    <?php if ($this->params->get('subCatDescription')): ?>
                    <!-- Subcategory description -->
                    <div><?php echo $subCategory->description; ?></div>
                    <?php endif; ?>

                    <!-- Subcategory more... -->
                    <!-- <a class="subCategoryMore" href="<?php echo $subCategory->link; ?>">
                        <?php echo JText::_('K2_VIEW_ITEMS'); ?>
                    </a> -->

                    <div class="clr"></div>
                </div>
            </div>
            <?php if (($key+1)%($this->params->get('subCatColumns'))==0): ?>
            <!-- <div class="clr"></div> -->
            <?php endif; ?>
            <?php endforeach; ?>

            <div class="clr"></div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <?php
    if (count($this->primary) == 0): ?>
    <div id="itemListPrimary">
      <?php echo JText::_('K2_NO_ITEM'); ?>
    </div>
    <?php endif; ?>

    <?php if ((isset($this->leading) || isset($this->primary) || isset($this->secondary) || isset($this->links)) && (count($this->leading) || count($this->primary) || count($this->secondary) || count($this->links))): ?>
    <!-- Item list -->
    <?php
    $config = new JConfig();


    ?>
    <?php
    $faqText = '';
    if(in_array($this->category->id, $config->categories_faq)){
      $faqText = 'list-faq';
    }
    ?>
    <div class="itemList <?php echo $faqText; ?>">
      <?php
      if(in_array($this->category->id, $config->categories_faq)){
      ?>
      <div class="itemList-title-faq">
        NHỮNG CÂU HỎI THƯỜNG GẶP VỀ <?php echo str_replace("Hỏi đáp","",$this->category->name);; ?>
      </div>

      <div class="itemList-left">
        <h3 class="faq">Câu hỏi</h3>
      <?php } ?>
        <?php if (isset($this->leading) && count($this->leading)): ?>
        <!-- Leading items -->
        <div id="itemListLeading">
            <?php foreach($this->leading as $key=>$item): ?>
            <?php
            // Define a CSS class for the last container on each row
            if ((($key+1)%($this->params->get('num_leading_columns'))==0) || count($this->leading) < $this->params->get('num_leading_columns'))
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>
            <div class="itemContainer<?php echo $lastContainer; ?> <?php echo $type_insurance; ?>"<?php echo (count($this->leading)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_leading_columns'), 1).'%;"'; ?>>

                <?php
                    // Load category_item.php by default
                    $this->item = $item;
                    switch($this->category->id){
                      case LIFE_INSURANCE:
                      echo $this->loadTemplate('insurance');
                      break;
                      case HEALTH_INSURANCE:
                      echo $this->loadTemplate('health');
                      break;
                      case TRAVEL_INSURANCE:
                      echo $this->loadTemplate('travel');
                      break;
                      case CAR_INSURANCE:
                      echo $this->loadTemplate('car');
                      break;
                      case HOME_INSURANCE:
                      echo $this->loadTemplate('home');
                      break;
                      case CRITICAL_ILLNESS_INSURANCE:
                      echo $this->loadTemplate('illness');
                      break;
                      default:
                      echo $this->loadTemplate('item');
                      break;
                    }


                ?>
            </div>
            <?php if (($key+1)%($this->params->get('num_leading_columns'))==0): ?>
            <!-- <div class="clr"></div> -->
            <?php endif; ?>
            <?php endforeach; ?>
            <!-- <div class="clr"></div> -->
        </div>
        <?php endif; ?>

        <?php if (isset($this->primary) && count($this->primary)): ?>
        <!-- Primary items -->
        <div id="itemListPrimary">
            <?php
            $hide_header_health = 1;
            $hide_header_travel = 1;
            switch($this->category->id){
                case LIFE_INSURANCE:
                    break;
                case HEALTH_INSURANCE:
                    $hide_header_health = 0;
                    break;
                case TRAVEL_INSURANCE:
                    $hide_header_travel = 0;
                    break;
                case CAR_INSURANCE:
                    break;
                case HOME_INSURANCE:
                    break;
                case CRITICAL_ILLNESS_INSURANCE:
                    break;
                default:
                    break;
            }

            ?>
            <!-- munu-header-suc-khoe -->
            <?php if($hide_header_health == 0){ ?>
            <div class="itemContainer itemContainerLast health header" style="width:100.0%;">
                <div class="catItemView groupPrimary">
                    <div class="insurance-company">
                        <p>Công ty bảo hiểm</p>
                    </div>

                    <div class="health-insurance-benefits">
                    <p>Quyền lợi bảo hiểm</p>
                    </div>

                    <div class="Waiting-time">
                        <p>Thời gian chờ</p>
                    </div>
                    <div class="Affiliated-hospital">
                        <p>Bệnh viện liên kết</p>
                    </div>
                    <div class="Insurance-fees">
                        <p>Phí bảo hiểm</p>
                    </div>


                    <div class="support">
                        <p>Hỗ trợ</p>
                    </div>

                </div>
            </div>
            <?php } ?>

            <!-- munu-header-du-lich -->
            <?php if($hide_header_travel == 0){ ?>
            <div class="itemContainer itemContainerLast health header" style="width:100.0%;">
                <div class="catItemView groupPrimary">
                    <div class="insurance-company">
                        <p>Bảo hiểm du lịch</p>
                    </div>

                    <div class="travel-the-insured">
                    <p>Đối tượng bảo hiểm</p>
                    </div>

                    <div class="duration-insurance">
                        <p>Thời hạn bảo hiểm</p>
                    </div>
                    <div class="travel-insurance-benefits">
                        <p>Quyền lợi bảo hiểm</p>
                    </div>
                    <div class="travel-insurance-fees">
                        <p>Phí bảo hiểm</p>
                    </div>
                    <div class="support-travel">
                        <p>Hỗ trợ</p>
                    </div>

                </div>
            </div>
            <?php } ?>

            <?php foreach($this->primary as $key=>$item): ?>

            <?php
            $firs_row = '';
            if($key == 0){
              $firs_row = 'first-row';
            }
            // Define a CSS class for the last container on each row
            if ((($key+1)%($this->params->get('num_primary_columns'))==0) || count($this->primary) < $this->params->get('num_primary_columns'))
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>

            <div class="<?php echo $firs_row; ?> itemContainer<?php echo $lastContainer; ?> <?php echo $type_insurance; ?>"<?php echo (count($this->primary)==0) ? '' : ' style="width:'.number_format(100/$this->params->get('num_primary_columns'), 1).'%;"'; ?>>

                <?php
                    // Load category_item.php by default
                    $this->item = $item;
                    switch($this->category->id){
                      case LIFE_INSURANCE:
                      echo $this->loadTemplate('insurance');
                      break;
                      case HEALTH_INSURANCE:
                      echo $this->loadTemplate('health');
                      break;
                      case TRAVEL_INSURANCE:
                      echo $this->loadTemplate('travel');
                      break;
                      case CAR_INSURANCE:
                      echo $this->loadTemplate('car');
                      break;
                      case HOME_INSURANCE:
                      echo $this->loadTemplate('home');
                      break;
                      case CRITICAL_ILLNESS_INSURANCE:
                      echo $this->loadTemplate('illness');
                      break;
                      default:
                      echo $this->loadTemplate('item');
                      break;
                    }
                ?>
            </div>
            <?php if (($key+1)%($this->params->get('num_primary_columns'))==0): ?>
            <!-- <div class="clr"></div> -->
            <?php endif; ?>
            <?php endforeach; ?>

            <!-- <div class="clr"></div> -->
        </div>
        <?php endif; ?>


        <?php if (isset($this->secondary) && count($this->secondary)): ?>
        <!-- Secondary items -->
        <div id="itemListSecondary">
            <?php foreach($this->secondary as $key=>$item): ?>
            <?php
            // Define a CSS class for the last container on each row
            if ((($key+1)%($this->params->get('num_secondary_columns'))==0) || count($this->secondary) < $this->params->get('num_secondary_columns'))
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>
            <div class="itemContainer<?php echo $lastContainer; ?> <?php echo $type_insurance; ?>"<?php echo (count($this->secondary)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_secondary_columns'), 1).'%;"'; ?>>
                <?php
                    // Load category_item.php by default
                    $this->item = $item;
                    switch($this->category->id){
                      case LIFE_INSURANCE:
                      echo $this->loadTemplate('insurance');
                      break;
                      case HEALTH_INSURANCE:
                      echo $this->loadTemplate('health');
                      break;
                      case TRAVEL_INSURANCE:
                      echo $this->loadTemplate('travel');
                      break;
                      case CAR_INSURANCE:
                      echo $this->loadTemplate('car');
                      break;
                      case HOME_INSURANCE:
                      echo $this->loadTemplate('home');
                      break;
                      case CRITICAL_ILLNESS_INSURANCE:
                      echo $this->loadTemplate('illness');
                      break;
                      default:
                      echo $this->loadTemplate('item');
                      break;
                    }
                ?>
            </div>
            <?php if (($key+1)%($this->params->get('num_secondary_columns'))==0): ?>
            <!-- <div class="clr"></div> -->
            <?php endif; ?>
            <?php endforeach; ?>
            <!-- <div class="clr"></div> -->
        </div>
        <?php endif; ?>

        <?php if (isset($this->links) && count($this->links)): ?>
        <!-- Link items -->
        <div id="itemListLinks">
            <h4><?php echo JText::_('K2_MORE'); ?></h4>
            <?php foreach($this->links as $key=>$item): ?>
            <?php
            // Define a CSS class for the last container on each row
            if ((($key+1)%($this->params->get('num_links_columns'))==0) || count($this->links) < $this->params->get('num_links_columns'))
                $lastContainer= ' itemContainerLast';
            else
                $lastContainer='';
            ?>
            <div class="itemContainer<?php echo $lastContainer; ?> <?php echo $type_insurance; ?>"<?php echo (count($this->links)==1) ? '' : ' style="width:'.number_format(100/$this->params->get('num_links_columns'), 1).'%;"'; ?>>
                <?php
                    // Load category_item.php by default
                    $this->item = $item;
                    switch($this->category->id){
                      case LIFE_INSURANCE:
                      echo $this->loadTemplate('insurance');
                      break;
                      case HEALTH_INSURANCE:
                      echo $this->loadTemplate('health');
                      break;
                      case TRAVEL_INSURANCE:
                      echo $this->loadTemplate('travel');
                      break;
                      case CAR_INSURANCE:
                      echo $this->loadTemplate('car');
                      break;
                      case HOME_INSURANCE:
                      echo $this->loadTemplate('home');
                      break;
                      case CRITICAL_ILLNESS_INSURANCE:
                      echo $this->loadTemplate('illness');
                      break;
                      default:
                      echo $this->loadTemplate('item');
                      break;
                    }
                ?>
            </div>
            <?php if (($key+1)%($this->params->get('num_links_columns'))==0): ?>
            <!-- <div class="clr"></div> -->
            <?php endif; ?>
            <?php endforeach; ?>
            <!-- <div class="clr"></div> -->
        </div>
        <?php endif; ?>
        <?php
        if(in_array($this->category->id, $config->categories_faq)){
        ?>
        </div>
        <div class="itemList-right">
          <h3 class="faq faq-result">Đáp</h3>
          <div class="faq-result-content" id="faq-result-content">
          </div>
        </div>
        <?php } ?>

    </div>

    <!-- Pagination -->
    <?php if ($this->pagination->getPagesLinks()): ?>
    <div class="k2Pagination">
        <?php if ($this->params->get('catPagination', 1)): ?>
        <div class="k2PaginationLinks">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
        <?php endif; ?>
        <?php if ($this->params->get('catPaginationResults', 1)): ?>
        <div class="k2PaginationCounter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<!-- End K2 Category Layout -->
