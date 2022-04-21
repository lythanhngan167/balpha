<?php
/*------------------------------------------------------------------------
# mod_bannerslider - WWM Banner Slideshow
# ------------------------------------------------------------------------
# author    walkswithme.net
# copyright Copyright (C) 2013 walkswithme.net. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.walkswithme.net/
# Technical Support:  Forum - http://www.walkswithme.net/joomla-banner-slideshow-module
-------------------------------------------------------------------------*/
 // no direct access



defined( '_JEXEC' ) or die( 'Restricted access' );

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."modules/mod_bannerslider/assets/css/slick.css");
$document->addStyleSheet(JURI::root()."modules/mod_bannerslider/assets/css/slick-theme.css");
if($params->get('loadjs') == 1)
$document->addScript(JURI::root()."modules/mod_bannerslider/assets/js/slick.min.js");

require_once JPATH_ROOT . '/components/com_banners/helpers/banner.php';
$baseurl = JUri::base();
?>
<div class="bannergroup<?php echo $moduleclass_sfx ?>">
<?php if ($headerText) : ?>
	<?php echo $headerText; ?>
<?php endif; ?>
<div class="banner_controls"></div>
<div class="banner_slider">
<?php foreach ($list as $item) : ?>
	<div class="banneritem">
		<?php $link = JRoute::_('index.php?option=com_banners&task=click&id='. $item->id);?>
		<?php if ($item->type == 1) :?>
			<?php // Text based banners ?>
			<?php echo str_replace(array('{CLICKURL}', '{NAME}'), array($link, $item->name), $item->custombannercode);?>
		<?php else:?>
			<?php $imageurl = $item->params->get('imageurl');?>
			<?php $width = $item->params->get('width');?>
			<?php $height = $item->params->get('height');?>
			<?php if (BannerHelper::isImage($imageurl)) :?>
				<?php // Image based banner ?>
				<?php $alt = $item->params->get('alt');?>
				<?php $alt = $alt ? $alt : $item->name; ?>
				<?php $alt = $alt ? $alt : JText::_('MOD_BANNERS_BANNER'); ?>
				<?php if ($item->clickurl) :?>
					<?php // Wrap the banner in a link?>
					<?php $target = $params->get('target', 1);?>
					<?php if ($target == 1) :?>
						<?php // Open in a new window?>
						<a
							href="<?php echo $link; ?>" target="_blank"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php elseif ($target == 2):?>
						<?php // open in a popup window?>
						<a
							href="<?php echo $link;?>" onclick="window.open(this.href, '',
								'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=550');
								return false"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php else :?>
						<?php // open in parent window?>
						<a
							href="<?php echo $link;?>"
							title="<?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');?>">
							<img
								src="<?php echo $baseurl . $imageurl;?>"
								alt="<?php echo $alt;?>"
								<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
								<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
							/>
						</a>
					<?php endif;?>
				<?php else :?>
					<?php // Just display the image if no link specified?>
					<img
						src="<?php echo $baseurl . $imageurl;?>"
						alt="<?php echo $alt;?>"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				<?php endif;?>
			<?php elseif (BannerHelper::isFlash($imageurl)) :?>
				<object
					classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
					codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
					<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
					<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
				>
					<param name="movie" value="<?php echo $imageurl;?>" />
					<embed
						src="<?php echo $imageurl;?>"
						loop="false"
						pluginspage="http://www.macromedia.com/go/get/flashplayer"
						type="application/x-shockwave-flash"
						<?php if (!empty($width)) echo 'width ="'. $width.'"';?>
						<?php if (!empty($height)) echo 'height ="'. $height.'"';?>
					/>
				</object>
			<?php endif;?>
		<?php endif;?>
		<div class="clr"></div>
	</div>
<?php endforeach; ?>
</div>
<?php if ($footerText) : ?>
	<div class="bannerfooter">
		<?php echo $footerText; ?>
	</div>
<?php endif; ?>
</div>
<script type="text/javascript">
    $ = jQuery.noConflict();
    $(function () {
        $('.banner_slider').slick({
            dots: true,
            autoplay: true,
            autoplaySpeed: 2000,
            arrows: true,
            slidesToShow: 1,
            slidesToScroll: 1,
          /*  responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]*/
        });
        // On before slide change
        $('.banner_slider').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            alert('fsafsafa')
        });
    })
</script>