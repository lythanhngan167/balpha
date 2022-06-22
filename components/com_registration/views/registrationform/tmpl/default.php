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
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
use \Joomla\CMS\HTML\HTMLHelper;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;

// HTMLHelper::_('behavior.keepalive');
// HTMLHelper::_('behavior.tooltip');
// HTMLHelper::_('behavior.formvalidation');
// HTMLHelper::_('formbehavior.chosen', 'select');
//JHTML::_('behavior.modal');
// Load admin language file

$lang = Factory::getLanguage();
$lang->load('com_registration', JPATH_SITE);
$doc = Factory::getDocument();
$doc->addScript(Uri::base() . '/media/com_registration/js/form.js');

$user    = Factory::getUser();
$canEdit = RegistrationHelpersRegistration::canUserEdit($this->item, $user);
$session = JFactory::getSession();
//$session->destroy();
if($user->id > 0){
	$session->destroy();
}

?>
<div id="registration-wrap">
	<h3>
	<?php if($this->item->id > 0){
		echo "#".$this->item->id." : ".$this->item->name." - ".$this->item->phone;
	}else{
		//echo 'ĐĂNG KÝ THÔNG TIN';
	} ?>
	</h3>

	<?php

	$userid = $session->get('landingpage_userid');
	$username = $session->get('landingpage_username');
	$pageid = $session->get('landingpage_pageid');

	//$agent = $this->getUserNameAgent($userid);

	$transfer_landingpage = TRANSFER_BCA;
	if ($_REQUEST['Itemid'] == AGENT) {
		// if ($transfer_landingpage == 1) {
		// 	if ($_SERVER['HTTP_HOST'] == "localhost") {
		// 		$url = "http://localhost/bcavietnam/agent/".$userid.".html";
		// 	}else {
		// 		$url = "http://bcavietnam.com/agent/".$userid.".html";
		// 	}
		// 	header("Location: $url");
		// 	exit();
		//
		// }
	}




	if($userid > 0 && $_REQUEST['Itemid'] != TECH_INSURACNE && $_REQUEST['Itemid'] != FOUNDER_STORY && $_REQUEST['Itemid'] != FOUR_ZERO_INSURACNE){
		// $intro = $this->getIntro($userid);
		// $images = $this->getImages($userid);
		// $contact = $this->getContact($userid);
		$intro = $this->arrayJSON->intro;
		$images = $this->arrayJSON->image;
		$contact = $this->arrayJSON->contact;
		$userinfo = $this->arrayJSON->userinfo;


	}else{
		$session->set('landingpage_userid', 0);
		if($contact->id == 0){
			$contact = new stdClass();
			$contact->id = 9999999999;
			$contact->phone = HOTLINE_BCAVIETNAM;
			$contact->email = EMAIL_BCAVIETNAM;
			$contact->address = ADDRESS_BCAVIETNAM;
		}
	}

	?>

	<?php if($intro->id > 0){ ?>
		<div class="container landingpage-intro" id="landingpage-intro">

			<div id="SECTION696" class="ladi-section">
      <div class="ladi-section-background"></div>
      <div class="ladi-container">
        <div id="GROUP701" class="ladi-element">
          <div class="ladi-group">
            <div id="BOX702" class="ladi-element">
              <div class="ladi-box"></div>
            </div>
            <div id="BOX703" class="ladi-element">
              <div class="ladi-box"></div>
            </div>
            <div id="BOX704" class="ladi-element">
              <div class="ladi-box"></div>
            </div>
          </div>
        </div>
        <div id="PARAGRAPH705" class="ladi-element ladi-animation">
          <p class="ladi-paragraph">
						<h3 class="font-tiempos pb-20"><?php echo $intro->title; ?></h3>
						<p class="pb-60"><?php echo nl2br($intro->intro_text); ?></p>
					</p>
        </div>
        <div id="PARAGRAPH720" class="ladi-element">
					<?php if($userinfo->id > 0){
						$user_name = $userinfo->name;

						$arr_user_name = explode(" ",$user_name);
						$number_name = count($arr_user_name);
						$name_end = '';
						if($number_name > 0){
							$name_end = $arr_user_name[$number_name - 1];
						}else{
							$name_end = 'TÔI';
						}
					}
					?>
          <p class="ladi-paragraph">TẠI SAO <?php echo mb_strtoupper($name_end); ?> LỰA CHỌN KINH DOANH TẠI B-Alpha</p>
        </div>
        <div id="PARAGRAPH722" class="ladi-element ladi-animation">
          <p class="ladi-paragraph">Câu chuyện của <?php echo ($name_end); ?> tại B-Alpha<br></p>
        </div>
        <div id="LINE725" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="GROUP730" class="ladi-element ladi-animation">
					<?php if($intro->youtube_video_url != ''){ ?>
						<?php $arr_youtube_video_url = explode("?v=",$intro->youtube_video_url);
						if($arr_youtube_video_url[1] !=''){
							$youtube_video_url_end = $arr_youtube_video_url[1];
						}else{
							$arr_youtube_video_url2 = explode(".be/",$intro->youtube_video_url);
							if($arr_youtube_video_url2[1] !=''){
								$youtube_video_url_end = $arr_youtube_video_url2[1];
							}
						}

						 ?>
						 <?php if($youtube_video_url_end != ''){  ?>
						 <iframe width="534" height="300" src="https://www.youtube.com/embed/<?php echo $youtube_video_url_end; ?>"
						title="YouTube video player" frameborder="0"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
						allowfullscreen>
						</iframe>
						<?php } ?>

				<?php }else{ ?>
					<?php if($intro->image != ''){ ?>
						<?php
						$image_url = '';
						if ($_SERVER['HTTP_HOST'] == "localhost"){
							if($intro->image != ''){
								$image_url = 'http://localhost/biznetweb/images/landingpage/'.$intro->image;
							}else{
								$image_url = 'http://localhost/biznetweb/images/members.jpg';
							}
						}else{
							if($intro->image != ''){
								$image_url = BIZNET_WEB . 'images/landingpage/'.$intro->image;
							}else{
								$image_url = BIZNET_WEB . 'images/members.jpg';
							}

						}

						?>
							<a target="_blank" class="image-popup-fit-width" href="<?php echo $image_url; ?>">
								<img width="100%" src="<?php echo $image_url;  ?>" alt="<?php echo $intro->title; ?>" />
							</a>
					<?php }else{ ?>

						<!-- <div class="ladi-group">
							<div id="BOX727" class="ladi-element">
								<div class="ladi-box"></div>
							</div>
							<div id="VIDEO728" class="ladi-element">
								<div class="ladi-video">
									<div class="ladi-video-background"></div>
									<div id="SHAPE728" class="ladi-element">



										<div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" preserveAspectRatio="none" viewBox="0 0 408.7 408.7" fill="rgba(0, 0, 0, 0.5)">
												<polygon fill="#fff" points="163.5 296.3 286.1 204.3 163.5 112.4 163.5 296.3"></polygon>
												<path d="M204.3,0C91.5,0,0,91.5,0,204.3S91.5,408.7,204.3,408.7s204.3-91.5,204.3-204.3S316.7,0,204.3,0ZM163.5,296.3V112.4l122.6,91.9Z" transform="translate(0 0)"></path>
											</svg></div>

									</div>
								</div>
							</div>
						</div> -->
					<?php } ?>
				<?php } ?>



        </div>
      </div>
    </div>



		<!-- <div class="contact-now-container">
			<h2 class="font-tiempos">Giới thiệu bản thân</h2>
		<ul>
		<?php if($intro->image != ''){ ?>
		<li class="col-6 contact-left">
		<article class="image-contact">

			<?php
			$image_url = '';
			if ($_SERVER['HTTP_HOST'] == "localhost"){
				if($intro->image != ''){
					$image_url = 'http://localhost/biznetweb/images/landingpage/'.$intro->image;
				}else{
					$image_url = 'http://localhost/biznetweb/images/members.jpg';
				}
			}else{
				if($intro->image != ''){
					$image_url = BIZNET_WEB . 'images/landingpage/'.$intro->image;
				}else{
					$image_url = BIZNET_WEB . 'images/members.jpg';
				}

			}

			?>
				<a target="_blank" class="image-popup-fit-width" href="<?php echo $image_url; ?>">
					<img width="100%" src="<?php echo $image_url;  ?>" alt="<?php echo $intro->title; ?>" />
				</a>
		</article>
		</li>
		<?php } ?>

		<li class="<?php if($intro->image != ''){ ?>col-6<?php }else{ ?>col-12<?php } ?> contact-right">
		<h3 class="font-tiempos pb-20"><?php echo $intro->title; ?></h3>
		<p class="pb-60"><?php echo nl2br($intro->intro_text); ?></p>
		<?php if($contact->facebookpage != ''){ ?>
		<div class="facebook-button">
		<a class="facebook-link" href="<?php echo $contact->facebookpage; ?>" class="elButton elButtonSize1 elButtonColor1 elButtonRounded elButtonPadding2 elBtnVP_10 elButtonCorner3 elButtonFluid elBtnHP_25 elBTN_b_1 elButtonShadowN1 elButtonTxtColor1 ea-buttonRocking mfs_12" style="color: rgb(255, 255, 255);  background-color: rgb(66, 103, 178); font-size: 15px;" rel="noopener noreferrer" target="_blank" id="undefined-1065">
			<span class="elButtonMain"><i class="fa fa_prepended   fa-facebook-official"></i> KẾT BẠN VỚI TÔI</span>
			<span class="elButtonSub"></span>
		</a>
	</div>
<?php } ?>
<?php if($intro->youtube_video_url != ''){ ?>
<div class="youtube-button">
<a class="youtube-link" href="<?php echo $intro->youtube_video_url; ?>" class="elButton elButtonSize1 elButtonColor1 elButtonRounded elButtonPadding2 elBtnVP_10 elButtonCorner3 elButtonFluid elBtnHP_25 elBTN_b_1 elButtonShadowN1 elButtonTxtColor1 ea-buttonRocking mfs_12" style="color: rgb(255, 255, 255);  background-color: red; font-size: 15px;" rel="noopener noreferrer" target="_blank" id="undefined-1065">
	<span class="elButtonMain"><i class="fa fa-youtube-play" aria-hidden="true"></i> VIDEO GIỚI THIỆU</span>
	<span class="elButtonSub"></span>
</a>
</div>
<?php } ?>

		</li>

		</ul>
		</div> -->

		</div>
	<?php } ?>

	<?php if($images->image1 != '' || $images->image2 != '' || $images->image3 != '' || $images->image4 != ''){ ?>
	<div class="container team-member">
	<div class="container-fluid">
		<div id="SECTION606" class="ladi-section">
      <div class="ladi-section-background"></div>
      <div class="ladi-container">
        <div id="GROUP607" class="ladi-element">
          <div class="ladi-group">
            <div id="BOX608" class="ladi-element">
              <div class="ladi-box"></div>
            </div>
            <div id="GALLERY609" class="ladi-element" data-max-item="3" data-runtime-id="DepiHnkNQdcw" data-current="1" data-is-next="false" data-scrolled="true" data-loaded="true" data-next-time="1645186856407">
              <div class="ladi-gallery ladi-gallery-bottom landingpage-gallery">

                <div class="ladi-gallery-view">
                  <div class="ladi-gallery-view-arrow ladi-gallery-view-arrow-left" onclick="slidePrevius()"></div>
                  <div class="ladi-gallery-view-arrow ladi-gallery-view-arrow-right" onclick="slideNext()"></div>
									<div>
										<input type="hidden" name="current_slide" id="current_slide" value="1" />
									</div>
                  <div class="ladi-gallery-view-item" data-index="0" data-lazyload="true"></div>

                  <div class="ladi-gallery-view-item selected" data-index="1">

									<?php if($images->image1 != ''){ ?>
									<div class="gallery-item" id="gallery-item-1" style="display:block;">
										<?php
											if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
												<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image1; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image1; ?>" alt="" /></a>
										<?php	}else { ?>
											<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image1; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image1; ?>" alt="" /></a>
									<?php	}
										 ?>
									</div>
								<?php }else{ ?>
									<div class="gallery-item" id="gallery-item-1" style="display:block;">
										<a title="" class="image-popup-fit-width" href="<?php echo Uri::base(); ?>images/hinh-02-20211012140111.jpg" target="_blank"><img width="100%" height="100%" src="<?php echo Uri::base(); ?>images/hinh-02-20211012140111.jpg" alt="" /></a>
									</div>
									<?php } ?>

									<?php if($images->image2 != ''){ ?>
									<div class="gallery-item" id="gallery-item-2" style="display:none;">
										<?php
											if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
												<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" alt="" /></a>
										<?php	}else { ?>
											<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" alt="" /></a>
									<?php	}
										 ?>
									</div>
								<?php }else{ ?>
									<div class="gallery-item" id="gallery-item-2" style="display:none;">
										<a title="" class="image-popup-fit-width" href="<?php echo Uri::base(); ?>images/hinh-03-20211012140111.jpg" target="_blank"><img width="100%" height="100%" src="<?php echo Uri::base(); ?>images/hinh-03-20211012140111.jpg" alt="" /></a>
									</div>
									<?php } ?>

									<?php if($images->image3 != ''){ ?>
										<div class="gallery-item" id="gallery-item-3" style="display:none;">
										<?php
											if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
												<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image3; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image3; ?>" alt="" /></a>
										<?php	}else { ?>
											<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image3; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image3; ?>" alt="" /></a>
									<?php	}
										 ?>
									</div>
								<?php }else{ ?>
									<div class="gallery-item" id="gallery-item-3" style="display:none;">
										<a title="" class="image-popup-fit-width" href="<?php echo Uri::base(); ?>images/hinh-04-20211012140111.jpg" target="_blank"><img width="100%" height="100%" src="<?php echo Uri::base(); ?>images/hinh-04-20211012140111.jpg" alt="" /></a>
									</div>
									<?php } ?>

									<?php if($images->image4 != ''){ ?>
									<div class="gallery-item" id="gallery-item-4" style="display:none;">
										<?php
											if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
												<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image4; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image4; ?>" alt="" /></a>
										<?php	}else { ?>
											<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image4; ?>" target="_blank"><img width="100%" height="100%" src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image4; ?>" alt="" /></a>
									<?php	}
										 ?>
									</div>
									<?php } ?>

									</div>
                  <div class="ladi-gallery-view-item" data-index="2"></div>
                </div>

                <!-- <div class="ladi-gallery-control">
                  <div class="ladi-gallery-control-box" style="left: 0px;">
                    <div class="ladi-gallery-control-item" data-index="0"></div>
                    <div class="ladi-gallery-control-item selected" data-index="1">
											<?php if($images->image2 != ''){ ?>
											<div class="gallery-item">
												<?php
													if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
														<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" target="_blank"><img src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" alt="" /></a>
												<?php	}else { ?>
													<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" target="_blank"><img src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" alt="" /></a>
											<?php	}
												 ?>
											</div>
											<?php } ?>
                    </div>

                    <div class="ladi-gallery-control-item" data-index="2"></div>
                  </div>
                  <div class="ladi-gallery-control-arrow ladi-gallery-control-arrow-left opacity-0"></div>
                  <div class="ladi-gallery-control-arrow ladi-gallery-control-arrow-right opacity-0"></div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
        <div id="HEADLINE610" class="ladi-element">
          <h3 class="ladi-headline">CHÚNG TÔI Ở ĐÂY, CHÀO ĐÓN BẠN, NHỮNG NGƯỜI KHAO KHÁT THÀNH CÔNG VÀ MONG MUỐN PHÁT TRIỂN BẢN THÂN MÌNH!<br></h3>
        </div>
        <div id="LINE611" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="IMAGE612" class="ladi-element">
          <div class="ladi-image">
            <div class="ladi-image-background"></div>
          </div>
        </div>
        <div id="LINE613" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="LINE614" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="IMAGE615" class="ladi-element">
          <div class="ladi-image">
            <div class="ladi-image-background"></div>
          </div>
        </div>
        <div id="LINE616" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="HEADLINE695" class="ladi-element">
          <h3 class="ladi-headline">
						<?php if($userinfo->id > 0){
							$user_name = $userinfo->name;

							$arr_user_name = explode(" ",$user_name);
							$number_name = count($arr_user_name);
							$name_end = '';
							if($number_name > 0){
								$name_end = $arr_user_name[$number_name - 1];
								$name_end = mb_strtoupper($name_end);
							}else{
								$name_end = 'TÔI';
							}
						}
						?>
						BẠN CÓ MUỐN CÙNG <?php echo ($name_end); ?> XÂY DỰNG SỰ NGHIỆP KINH DOANH CHO RIÊNG MÌNH<br></h3>
        </div>
      </div>
    </div>
</div>

<!-- <div class="culture-abundance-inner">
<h2 class="font-tiempos">Đội nhóm của tôi</h2>
<p>Điều chúng tôi muốn hướng đến là phát triển đội nhóm bền vững, hoạt động hiệu quả.</p>
<div class="culture-abundance-gallery html-code grid-of-images">

	<ul class="landingpage-gallery">
	<?php if($images->image1 != ''){ ?>
	<li>
		<?php
			if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
				<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image1; ?>" target="_blank"><img src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image1; ?>" alt="" /></a>
		<?php	}else { ?>
			<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image1; ?>" target="_blank"><img src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image1; ?>" alt="" /></a>
	<?php	}
		 ?>

	</li>
	<?php } ?>

	<?php if($images->image2 != ''){ ?>
	<li>
		<?php
			if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
				<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" target="_blank"><img src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image2; ?>" alt="" /></a>
		<?php	}else { ?>
			<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" target="_blank"><img src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image2; ?>" alt="" /></a>
	<?php	}
		 ?>
	</li>
	<?php } ?>

	<?php if($images->image3 != ''){ ?>
	<li>
		<?php
			if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
				<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image3; ?>" target="_blank"><img src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image3; ?>" alt="" /></a>
		<?php	}else { ?>
			<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image3; ?>" target="_blank"><img src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image3; ?>" alt="" /></a>
	<?php	}
		 ?>
	</li>
	<?php } ?>

	<?php if($images->image4 != ''){ ?>
	<li>
		<?php
			if ($_SERVER['HTTP_HOST'] == "localhost"){ ?>
				<a title="" class="image-popup-fit-width" href="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image4; ?>" target="_blank"><img src="<?php echo 'http://localhost/biznetweb/images/landingpage/'.$images->image4; ?>" alt="" /></a>
		<?php	}else { ?>
			<a title="" class="image-popup-fit-width" href="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image4; ?>" target="_blank"><img src="<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image4; ?>" alt="" /></a>
	<?php	}
		 ?>
	</li>
	<?php } ?>



	</ul>
</div>
</div> -->
</div>
<?php } ?>

<div id="SECTION803" class="ladi-section">
	<?php if($contact->id > 0 && $_REQUEST['layout'] != 'edit'){

		if($contact->phone != ''){
			$c_phone = $contact->phone;
		}else{
			$c_phone = $userinfo->username;
		}
		if($contact->email != ''){
			$c_email = $contact->email;
		}else{
			$c_email = 'bcavietnam.insurance@gmail.com';
		}
		if($contact->address !=''){
			$c_address = $contact->address;
		}else{
			$c_address = '55 Trương Quốc Dung, P.10, Q. Phú Nhuận, TP.HCM';
		}

	} ?>
  <div class="ladi-section-background"></div>
  <div class="ladi-overlay"></div>
  <div class="ladi-container">
    <div id="LINE804" class="ladi-element">
      <div class="ladi-line">
        <div class="ladi-line-container"></div>
      </div>
    </div>
    <div id="GROUP805" class="ladi-element">
      <div class="ladi-group">
        <div id="HEADLINE806" class="ladi-element">
          <h4 class="ladi-headline">Thông Tin Liên Hệ</h4>
        </div>
        <div id="GROUP807" class="ladi-element">
          <div class="ladi-group">
            <div id="SHAPE808" class="ladi-element">
              <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                  <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"></path>
                </svg></div>
            </div>
            <div id="HEADLINE809" class="ladi-element">
              <p class="ladi-headline">Địa chỉ: <?php echo $c_address; ?><br></p>
            </div>
          </div>
        </div>
        <div id="GROUP810" class="ladi-element CGROUP8101">
          <div class="ladi-group">
            <div id="SHAPE811" class="ladi-element">
              <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                  <path
                    d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z">
                  </path>
                </svg></div>
            </div>
            <div id="HEADLINE812" class="ladi-element">
              <p class="ladi-headline">Điện thoại: <a class="hotline" href="tel:<?php echo $contact->phone; ?>"><?php echo $c_phone; ?></a></p>
            </div>
          </div>
        </div>

				<div id="GROUP810" class="ladi-element CGROUP8102">
          <div class="ladi-group">
            <div id="SHAPE811" class="ladi-element">
              <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
									<path d="M20,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V8L12,13L20,8V18M20,6L12,11L4,6V6H20V6Z"></path>
								</svg></div>
            </div>
            <div id="HEADLINE812" class="ladi-element">
              <p class="ladi-headline">Email: <a href="mailto:<?php echo $contact->email; ?>"><?php echo $c_email; ?></a></p>
            </div>
          </div>
        </div>

				<?php if($contact->id != 9999999999){ ?>
				<div id="GROUP810" class="ladi-element CGROUP8103">
					<div class="ladi-group">
						<div id="SHAPE5992" class="ladi-element">
							<div class="ladi-shape">
								<i class="fa fa-globe" aria-hidden="true"></i>
							</div>
						</div>
						<div id="HEADLINE6002" class="ladi-element">
							<div class="ladi-headline">

									<span>Kết nối qua:</span>
									<a href="<?php echo $contact->facebookpage; ?>" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
									<a href="<?php echo $contact->youtubepage; ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
									<a href="http://zalo.me/<?php echo $contact->phone; ?>" target="_blank"><i class="fa fa-zalo" aria-hidden="true"><img width="23" src="<?php echo JUri::root() . 'images/zalo.png'; ?>" /></i></a>

							</div>

						</div>
					</div>
				</div>
			<?php } ?>
			<div id="hour_start" style="display:none;"><?php if($contact->hour_start != ''){ echo $contact->hour_start; }else{ echo "19:00"; }  ?></div>
			<div id="day_start" style="display:none;"><?php if($contact->day_start != ''){ echo $contact->day_start; }else{ echo date('d/m/Y'); }  ?></div>
			<div id="monday_start" style="display:none;"><?php if($contact->day_start != ''){
				$day_start_array = explode("/",$contact->day_start);
				$new_date = $day_start_array[2]."-".$day_start_array[1]."-".$day_start_array[0];

				$timestamp_start = strtotime($new_date);
				$monday_start = date('l', $timestamp_start);

				switch ($monday_start) {
					case 'Monday':
						echo 'THỨ 2';
						break;
					case 'Tuesday':
						echo 'THỨ 3';
						break;
					case 'Wednesday':
						echo 'THỨ 4';
						break;
					case 'Thursday':
						echo 'THỨ 5';
						break;
					case 'Friday':
						echo 'THỨ 6';
						break;
					case 'Saturday':
						echo 'THỨ 7';
						break;
					case 'Sunday':
						echo 'CHỦ NHẬT';
						break;
					default:
						echo 'THỨ #';
						break;
				}
			}else{
				echo 'THỨ #';
			} ?></div>
      </div>
    </div>
    <div id="GROUP816" class="ladi-element">
      <div class="ladi-group">
        <div id="PARAGRAPH817" class="ladi-element ladi-animation">
          <p class="ladi-paragraph">B-Alpha là đội ngũ đầu tiên tại Việt Nam, ứng dụng công nghệ trực tuyến vào ngành bảo hiểm để hỗ trợ việc tìm kiếm khách hàng.<br></p>
        </div>
        <div id="HEADLINE819" class="ladi-element">
          <h3 class="ladi-headline">WORKSHOP ĐƯỢC TỔ CHỨC BỞI B-ALPHA</h3>
        </div>
      </div>
    </div>

		<div id="IMAGE823" class="ladi-element"><div class="ladi-image"><div class="ladi-image-background"></div></div></div>
  </div>
</div>

<div id="SECTION588" class="ladi-section">
	<?php if($contact->id > 0 && $_REQUEST['layout'] != 'edit'){
		if($contact->phone != ''){
			$c_phone = $contact->phone;
		}else{
			$c_phone = $userinfo->username;
		}
		if($contact->email != ''){
			$c_email = $contact->email;
		}else{
			$c_email = 'bcavietnam.insurance@gmail.com';
		}
		if($contact->address !=''){
			$c_address = $contact->address;
		}else{
			$c_address = '55 Trương Quốc Dung, P.10, Q. Phú Nhuận, TP.HCM';
		}

	} ?>
      <div class="ladi-section-background"></div>
      <div class="ladi-overlay"></div>
      <div class="ladi-container">
        <div id="LINE589" class="ladi-element">
          <div class="ladi-line">
            <div class="ladi-line-container"></div>
          </div>
        </div>
        <div id="GROUP590" class="ladi-element">
          <div class="ladi-group">
            <div id="HEADLINE591" class="ladi-element">
              <h4 class="ladi-headline">Thông Tin Liên Hệ</h4>
            </div>
            <div id="GROUP592" class="ladi-element">
              <div class="ladi-group">
                <div id="SHAPE593" class="ladi-element">
                  <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                      <path d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z"></path>
                    </svg></div>
                </div>
                <div id="HEADLINE594" class="ladi-element">
                  <p class="ladi-headline">Địa chỉ: <?php echo $c_address; ?><br></p>
                </div>
              </div>
            </div>
            <div id="GROUP595" class="ladi-element">
              <div class="ladi-group">
                <div id="SHAPE596" class="ladi-element">
                  <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                      <path d="M6.62,10.79C8.06,13.62 10.38,15.94 13.21,17.38L15.41,15.18C15.69,14.9 16.08,14.82 16.43,14.93C17.55,15.3 18.75,15.5 20,15.5A1,1 0 0,1 21,16.5V20A1,1 0 0,1 20,21A17,17 0 0,1 3,4A1,1 0 0,1 4,3H7.5A1,1 0 0,1 8.5,4C8.5,5.25 8.7,6.45 9.07,7.57C9.18,7.92 9.1,8.31 8.82,8.59L6.62,10.79Z">
                      </path>
                    </svg></div>
                </div>
                <div id="HEADLINE597" class="ladi-element">
                  <p class="ladi-headline">&nbsp;Điện thoại: <a class="hotline" href="tel:<?php echo $contact->phone; ?>"><?php echo $c_phone; ?></a></p>
                </div>
              </div>
            </div>
            <div id="GROUP598" class="ladi-element">
              <div class="ladi-group">
                <div id="SHAPE599" class="ladi-element">
                  <div class="ladi-shape"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100%" height="100%" viewBox="0 0 24 24" fill="rgba(255,255,255,1)">
                      <path d="M20,4H4A2,2 0 0,0 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6A2,2 0 0,0 20,4M20,18H4V8L12,13L20,8V18M20,6L12,11L4,6V6H20V6Z"></path>
                    </svg></div>
                </div>
                <div id="HEADLINE600" class="ladi-element">
                  <p class="ladi-headline">Email: <a href="mailto:<?php echo $contact->email; ?>"><?php echo $c_email; ?></a></p>


                </div>
              </div>
            </div>
						<?php if($contact->id != 9999999999){ ?>
						<div id="GROUP5982" class="ladi-element">
              <div class="ladi-group">
                <div id="SHAPE5992" class="ladi-element">
                  <div class="ladi-shape">
										<i class="fa fa-globe" aria-hidden="true"></i>
									</div>
                </div>
                <div id="HEADLINE6002" class="ladi-element">
									<div class="ladi-headline">

											<span>Kết nối qua:</span>
											<a href="<?php echo $contact->facebookpage; ?>" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
											<a href="<?php echo $contact->youtubepage; ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
											<a href="http://zalo.me/<?php echo $contact->phone; ?>" target="_blank"><i class="fa fa-zalo" aria-hidden="true"><img width="23" src="<?php echo JUri::root() . 'images/zalo.png'; ?>" /></i></a>

									</div>

                </div>
              </div>
            </div>
					<?php } ?>
          </div>
        </div>
        <div id="GROUP602" class="ladi-element">
          <div class="ladi-group">
            <div id="PARAGRAPH603" class="ladi-element ladi-animation">
              <p class="ladi-paragraph">B-Alpha là đội ngũ đầu tiên tại Việt Nam, ứng dụng công nghệ trực tuyến vào ngành bảo hiểm để hỗ trợ việc tìm kiếm khách hàng.&nbsp;<br></p>
            </div>
            <div id="PARAGRAPH604" class="ladi-element ladi-animation">
              <p class="ladi-paragraph">B-Alpha hướng đến xây dựng một “ngôi nhà” thứ hai sẻ chia và nâng đỡ dành cho cộng đồng các tư vấn viên bảo hiểm. Đồng thời phát triển nền tảng trung gian kết nối khách hàng tiềm năng với các tư vấn viên bảo
                hiểm đầu tiên tại Việt Nam.&nbsp;<br></p>
            </div>

            <div id="HEADLINE605" class="ladi-element">
              <h3 class="ladi-headline">GIỚI THIỆU VỀ B-Alpha</h3>
            </div>
          </div>
        </div>
        <div id="IMAGE623" class="ladi-element">
          <div class="ladi-image">
            <div class="ladi-image-background"></div>
          </div>
        </div>
      </div>
    </div>

<div class="join-us row-fluid">
	<?php if($this->item->id == 0){ ?>
	<!-- <div class="span6 widget-span widget-type-custom_widget " style="" data-widget-type="custom_widget" data-x="0" data-w="6">
		<div id="hs_cos_wrapper_module_1539233706554673" class="hs_cos_wrapper hs_cos_wrapper_widget hs_cos_wrapper_type_module" style="" data-hs-cos-general-type="widget" data-hs-cos-type="module"><div class="logo">
			<img width="100" src="<?php echo JUri::root() . 'templates/protostar/images/logo-footer.png?'.time(); ?>">
			<h2 class="join-with-us font-tiempos">Đăng ký phát triển sự nghiệp cùng BCA Insurance</h2>

		</div></div>
	</div> -->
<?php } ?>
	<div class="span6 widget-span widget-type-custom_widget " style="" data-widget-type="custom_widget" data-x="6" data-w="6">
		<div class="registration-edit front-end-edit" id="registration-edit">
			<?php if (!$canEdit) : ?>
				<h3>
					<?php throw new Exception(Text::_('COM_REGISTRATION_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
				</h3>
			<?php else : ?>
					<!-- <?php if (!empty($this->item->id)): ?>
						<h1><?php echo Text::sprintf('COM_REGISTRATION_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
					<?php else: ?>
						<h1><?php echo Text::_('COM_REGISTRATION_ADD_ITEM_TITLE'); ?></h1>
					<?php endif; ?> -->

				<form id="form-registration"
					  action="<?php echo Route::_('index.php?option=com_registration&task=registration.save'); ?>"
					  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

			<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />

			<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />

			<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

			<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />

			<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

						<?php echo $this->form->getInput('created_by'); ?>
						<?php echo $this->form->getInput('modified_by'); ?>
			<div class="name">
			<?php echo $this->form->renderField('name'); ?>
			</div>
			<div class="email">
			<?php echo $this->form->renderField('email'); ?>
			</div>
			<div class="phone">
			<?php echo $this->form->renderField('phone'); ?>
			</div>
			<div class="utm_source">
			<?php
				$uri = JUri::getInstance();
				$url_utm =  $uri->toString();
				$array_url_utm =  explode("?",$url_utm);

				if($array_url_utm[1] != ''){
					$this->item->utm_source = $array_url_utm[1];
				}
			?>
			<input type="hidden" name="utm_source_hidden" id="utm_source_hidden" value="<?php echo $this->item->utm_source; ?>" />
			<!-- <?php //echo $this->form->renderField('utm_source'); ?> -->
			</div>
			<!-- <div class="job class-hidden">
			<?php echo $this->form->renderField('job'); ?>
			</div> -->
			<div class="address">
			<?php echo $this->form->renderField('address'); ?>
			</div>
			<div class="note">
			<?php echo $this->form->renderField('note'); ?>
			</div>

			<div class="year_old">
			<?php echo $this->form->renderField('year_old'); ?>
			</div>

			<div class="province" style="display:none;">
			<?php echo $this->form->renderField('province'); ?>
			</div>

			<!-- <div class="is_exist">
			<?php //echo $this->form->renderField('is_exist'); ?>
			</div> -->

			<div class="status class-hidden">
			<?php echo $this->form->renderField('status'); ?>
			</div>
						<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','registration')): ?> style="display:none;" <?php endif; ?> >
		                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
		                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
		                <fieldset class="panelform">
		                    <?php echo $this->form->getLabel('rules'); ?>
		                    <?php echo $this->form->getInput('rules'); ?>
		                </fieldset>
		                <?php echo JHtml::_('sliders.end'); ?>
		            </div>
						<?php if (!JFactory::getUser()->authorise('core.admin','registration')): ?>
		                <script type="text/javascript">
		                    jQuery.noConflict();
		                    jQuery('.tab-pane select').each(function(){
		                       var option_selected = jQuery(this).find(':selected');
		                       var input = document.createElement("input");
		                       input.setAttribute("type", "hidden");
		                       input.setAttribute("name", jQuery(this).attr('name'));
		                       input.setAttribute("value", option_selected.val());
		                       document.getElementById("form-registration").appendChild(input);
		                    });
		                </script>
		             <?php endif; ?>
					<div class="control-group registrationform">
						<div class="controls">
							<div class="hs_submit" >
							<?php if ($this->canSave): ?>
								<div class="actions">
									<button id="submit-button-landingpage" onclick="return checkForm1();" type="submit" class="btn-primary1 <?php if($userid == 0){ ?>btn-primary<?php } ?> btn-red-outline">ĐĂNG KÝ NHẬN TƯ VẤN!</button>
									<button id="submit-button-landingpage-processing" style="display:none;" type="button" class="btn-primary1 <?php if($userid == 0){ ?>btn-primary<?php } ?> btn-red-outline">Đang xử lý...</button>
								</div>
								<!-- <button type="submit" class="hs-button primary large">
									<?php echo Text::_('JSUBMIT'); ?>

								</button> -->
							<?php endif; ?>
							</div>
							<!-- <a class="btn"
							   href="<?php echo Route::_('index.php?option=com_registration&task=registrationform.cancel'); ?>"
							   title="<?php echo Text::_('JCANCEL'); ?>">
								<?php echo Text::_('JCANCEL'); ?>
							</a> -->
						</div>
					</div>

					<input type="hidden" name="option" value="com_registration"/>
					<?php if($userid > 0){ ?>
						<input type="hidden" name="landingpage_uid" value="<?php echo $userid; ?>"/>
						<input type="hidden" name="landingpage_uname" value="<?php echo $username; ?>"/>
						<input type="hidden" name="landingpage_name" value="<?php echo $pageid; ?>"/>
					<?php } ?>
					<input type="hidden" name="task"
						   value="registrationform.save"/>
					<?php echo HTMLHelper::_('form.token'); ?>
				</form>
			<?php endif; ?>
		</div>
	</div>

</div>

<?php if($contact->id > 0 && $_REQUEST['layout'] != 'edit'){ ?>
<!-- <div class="contact-agent" >
	<ul class="hotline-info">
	<li>
	<i class="fa fa-phone" aria-hidden="true"></i>
	<p class="wt">Hotline (24/7)</p>
	<p class="cyt"><a class="hotline" href="tel:<?php echo $contact->phone; ?>"><?php echo $contact->phone; ?></a></p>
	</li>

	<li>
	<i class="fa fa-envelope-o" aria-hidden="true"></i>
	<p class="wt">Hợp tác với BCA Insurance</p>
	<p class="cyt"><a href="mailto:<?php echo $contact->email; ?>"><?php echo $contact->email; ?></a></p>
	</li>
	<li>
	<i class="fa fa-map-marker" aria-hidden="true"></i>
	<p class="wt">Địa chỉ liên hệ</p>
	<p class="cyt"><?php echo $contact->address; ?></p>
	</li>

	<?php if($contact->id != 9999999999){ ?>
	<li>
		<i class="fa fa-globe" aria-hidden="true"></i>
	<p class="wt">Kết nối qua</p>
	<p class="cyt social">
		<a href="<?php echo $contact->facebookpage; ?>" target="_blank"><i class="fa fa-facebook-official" aria-hidden="true"></i></a>
		<a href="<?php echo $contact->youtubepage; ?>" target="_blank"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
		<a href="http://zalo.me/<?php echo $contact->phone; ?>" target="_blank"><i class="fa fa-zalo" aria-hidden="true"><img width="30" src="<?php echo JUri::root() . 'images/zalo.png'; ?>" /></i></a>
		 </p>
	</li>
<?php } ?>

	</ul>
</div> -->
<?php } ?>

</div>


<!-- <div class="ykien-paging">
<div class="divs">
    <div class="cls1">1</div>
    <div class="cls2">2</div>
    <div class="cls3">3</div>
    <div class="cls4">4</div>
    <div class="cls5">5</div>
    <div class="cls6">6</div>
    <div class="cls7">7</div>
</div>

<a class="previous round" id="prev">&#8249;</a>
<a class="next round" id="next">&#8250;</a>
</div> -->
<script>

jQuery('li.item-719 a').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#sp-built-brand").offset().top
	}, 1500);
});
jQuery('a.gotoform').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#sp-built-brand").offset().top
	}, 1500);
});

jQuery('#HEADLINE484').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION553").offset().top
	}, 1500);
});
jQuery('#HEADLINE403').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION553").offset().top
	}, 1500);
});

jQuery('#HEADLINE517').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION553").offset().top
	}, 1500);
});

jQuery('#GROUP694').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION553").offset().top
	}, 1500);
});

jQuery('#VIDEO454').on('click', function(ev) {
	setTimeout(function() {
		jQuery("#VIDEO454_player")[0].src += "&autoplay=1";
	 	ev.preventDefault();
 	}, 500);
 });

 jQuery('#VIDEO456').on('click', function(ev) {
 	setTimeout(function() {
 		jQuery("#VIDEO456_player")[0].src += "&autoplay=1";
 	 	ev.preventDefault();
  	}, 500);
  });

jQuery('#VIDEO459').on('click', function(ev) {
	setTimeout(function() {
		jQuery("#VIDEO459_player")[0].src += "&autoplay=1";
	 	ev.preventDefault();
 	}, 500);
 });

jQuery('#VIDEO462').on('click', function(ev) {
	setTimeout(function() {
		jQuery("#VIDEO462_player")[0].src += "&autoplay=1";
	 	ev.preventDefault();
	}, 500);
});

jQuery('#BUTTON777').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION356").offset().top
	}, 1500);
});

jQuery('#BUTTON378').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION387").offset().top
	}, 1500);
});

jQuery('#GROUP662').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});

jQuery('#BUTTON563').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});

jQuery('#GROUP663').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});

jQuery('#IMAGE678').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});

jQuery('#BUTTON769').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});

jQuery('#BUTTON782').click(function() {
	jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery("#SECTION679").offset().top
	}, 1500);
});



















jQuery(document).ready(function(){
		jQuery('#form-FORM582').submit(false);
		jQuery('#form-FORM342').submit(false);

		var hour_start = '';
		hour_start = jQuery('#hour_start').text();
		jQuery('#HEADLINE401 h3').text(hour_start);

		var day_start = '';
		day_start = jQuery('#day_start').text();
		jQuery('#HEADLINE404 h3').text(day_start);

		var monday_start = '';
		monday_start = jQuery('#monday_start').text();
		jQuery('#HEADLINE403 h3').text(monday_start);

		<?php if($images->image5 != ''): ?>
		jQuery('#IMAGE822 .ladi-image-background').css({
				'background-image': 'url(<?php echo BIZNET_WEB . 'images/landingpage/'.$images->image5; ?>)'
		});
		<?php endif; ?>

		jQuery('#form-FORM582 :submit').attr("disabled", "disabled");
		jQuery('#form-FORM342 :submit').attr("disabled", "disabled");

		jQuery('#form-FORM582').unbind('submit');
		jQuery('#form-FORM342').unbind('submit');

		jQuery('li.item-719 a').removeAttr('href');
		jQuery('a.gotoform').removeAttr('href');
		var utm_source = jQuery('#utm_source_hidden').val();
		if(utm_source != ''){
			jQuery('#jform_utm_source').val(utm_source);
		}

		jQuery('.detail-ykien').click(function() {
			jQuery('.ykien-paging .ladi-paragraph').css({
					'height': 'auto'
			})
		});

		jQuery('.ykien-paging .ladi-paragraph').css({
				'height': '120px'
		})
    jQuery(".divs div.all-cls").each(function(e) {
        if (e != 0)
            jQuery(this).hide();
    });

    jQuery("#next").click(function(){
				jQuery('.ykien-paging .ladi-paragraph').css({
						'height': '120px'
				});
				jQuery('.ykien-paging img').css({
						'display': 'block'
				});


        if (jQuery(".divs div.all-cls:visible").next().length != 0)
            jQuery(".divs div.all-cls:visible").next().show().prev().hide();
        else {
            jQuery(".divs div.all-cls:visible").hide();
            jQuery(".divs div.all-cls:first").show();
        }
				var display433 = jQuery('#GROUP433').css('display');
				var display454 = jQuery('#GROUP454').css('display');
				var display460 = jQuery('#GROUP460').css('display');
				var display466 = jQuery('#GROUP466').css('display');
				var source = '';
				if(display433 == 'block'){
					jQuery('#GROUP433 img').css('display','block');
					source = jQuery('#GROUP433 img').attr('data-src');
					jQuery('#GROUP433 img').attr('src',source);
				}
				if(display454 == 'block'){
					jQuery('#GROUP454 img').css('display','block');
					source = jQuery('#GROUP454 img').attr('data-src');
					jQuery('#GROUP454 img').attr('src',source);
				}
				if(display460 == 'block'){
					jQuery('#GROUP460 img').css('display','block');
					source = jQuery('#GROUP460 img').attr('data-src');
					jQuery('#GROUP460 img').attr('src',source);
				}
				if(display466 == 'block'){
					jQuery('#GROUP466 img').css('display','block');
					source = jQuery('#GROUP466 img').attr('data-src');
					jQuery('#GROUP466 img').attr('src',source);
				}


        return false;
    });

    jQuery("#prev").click(function(){
				jQuery('.ykien-paging .ladi-paragraph').css({
						'height': '120px'
				});
				jQuery('.ykien-paging img').css({
						'display': 'block'
				});


        if (jQuery(".divs div.all-cls:visible").prev().length != 0)
            jQuery(".divs div.all-cls:visible").prev().show().next().hide();
        else {
            jQuery(".divs div.all-cls:visible").hide();
            jQuery(".divs div.all-cls:last").show();
        }

				var display433 = jQuery('#GROUP433').css('display');
				var display454 = jQuery('#GROUP454').css('display');
				var display460 = jQuery('#GROUP460').css('display');
				var display466 = jQuery('#GROUP466').css('display');
				var source = '';
				if(display433 == 'block'){
					jQuery('#GROUP433 img').css('display','block');
					source = jQuery('#GROUP433 img').attr('data-src');
					jQuery('#GROUP433 img').attr('src',source);
				}
				if(display454 == 'block'){
					jQuery('#GROUP454 img').css('display','block');
					source = jQuery('#GROUP454 img').attr('data-src');
					jQuery('#GROUP454 img').attr('src',source);
				}
				if(display460 == 'block'){
					jQuery('#GROUP460 img').css('display','block');
					source = jQuery('#GROUP460 img').attr('data-src');
					jQuery('#GROUP460 img').attr('src',source);
				}
				if(display466 == 'block'){
					jQuery('#GROUP466 img').css('display','block');
					source = jQuery('#GROUP466 img').attr('data-src');
					jQuery('#GROUP466 img').attr('src',source);
				}
        return false;
    });

		jQuery('.image-popup-vertical-fit').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			mainClass: 'mfp-img-mobile',
			image: {
				verticalFit: true
			}

		});

		jQuery('.image-popup-fit-width').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			image: {
				verticalFit: false
			}
		});

		jQuery('.image-popup-no-margins').magnificPopup({
			type: 'image',
			closeOnContentClick: true,
			closeBtnInside: false,
			fixedContentPos: true,
			mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
			image: {
				verticalFit: true
			},
			zoom: {
				enabled: true,
				duration: 300 // don't foget to change the duration also in CSS
			}
		});

});


function slideNext(){
	var current_slide = jQuery('#current_slide').val();
	if(current_slide >= 1 && current_slide <= 3){
		current_slide = parseInt(current_slide) + 1;
		jQuery('#current_slide').val(current_slide);
	}else{
		jQuery('#current_slide').val(1);
	}
	var new_current_slide = jQuery('#current_slide').val();
	jQuery('.gallery-item').css('display','none');
	jQuery('#gallery-item-'+new_current_slide).css('display','block');

}

function slidePrevius(){
	var current_slide = jQuery('#current_slide').val();
	if(current_slide > 1 ){
		current_slide = parseInt(current_slide) - 1;
		jQuery('#current_slide').val(current_slide);
	}else{
		jQuery('#current_slide').val(1);
	}
	var new_current_slide = jQuery('#current_slide').val();
	jQuery('.gallery-item').css('display','none');
	jQuery('#gallery-item-'+new_current_slide).css('display','block');

}

function onlyDigits(s) {
  for (let i = s.length - 1; i >= 0; i--) {
    const d = s.charCodeAt(i);
    if (d < 48 || d > 57) return false
  }
  return true
}

function checkForm1(){
	var phone = jQuery('#jform_phone').val();

	var name = jQuery('#jform_name').val();
	var email = jQuery('#jform_email').val();
	var province = jQuery('#jform_province').val();

	if(phone == '' || phone.length != 10){
		alert("Vui lòng nhập Số điện thoại 10 số!");
		return false;
	}else{
		if(onlyDigits(phone)){
			//jQuery('#submit-button-landingpage').attr('onclick','');
			if(name != '' && email != '' && province != ''){
				jQuery('#submit-button-landingpage-processing').css('display','block');
				jQuery('#submit-button-landingpage-processing').css('float','left');
				jQuery('#submit-button-landingpage').css('display','none');
			}else{
				jQuery('#submit-button-landingpage').css('display','block');
				jQuery('#submit-button-landingpage-processing').css('display','none');
			}


			return true;
		}else{
			alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			return false;
		}
	}

}



function submitForm2(){
	var name = jQuery('#jform_name2').val();
	var phone = jQuery('#jform_phone2').val();
	var email = jQuery('#jform_email2').val();
	//var province = jQuery('#jform_province2').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(validateEmail(email)){
				if(onlyDigits(phone)){
					//if(province != ''){
						jQuery('#form-registration-2 .hs_submit button').attr('onclick','');
						jQuery('#form-registration-2 .hs_submit button').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						//jQuery('#jform_province').val(province);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }

				}else{
					alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
				}
			}else{
				alert("Vui lòng nhập đúng dạng Email!");
			}

		}
	}
}


function checkSubmitForm1(){
	var name = jQuery('#form-name').val();
	var phone = jQuery('#form-phone').val();
	var email = jQuery('#form-email').val();
	//var province = jQuery('#form-province').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
					//if(province != ''){
						jQuery('#BUTTON_TEXT462').attr('onclick','');
						jQuery('#BUTTON_TEXT462 p').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						//jQuery('#jform_province').val(province);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}
			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}

function checkSubmitForm2(){
	var name = jQuery('#form-name2').val();
	var phone = jQuery('#form-phone2').val();
	var email = jQuery('#form-email2').val();
	//var province = jQuery('#form-province2').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
					//if(province != ''){
						jQuery('#BUTTON_TEXT697').attr('onclick','');
						jQuery('#BUTTON_TEXT697 p').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						//jQuery('#jform_province').val(province);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}

			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}


function checkSubmitForm3(){
	var name = jQuery('#form-name3').val();
	var phone = jQuery('#form-phone3').val();
	var email = jQuery('#form-email3').val();
	//var province = jQuery('#form-province3').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
					//if(province != ''){
						jQuery('#BUTTON_TEXT227').attr('onclick','');
						jQuery('#BUTTON_TEXT227 p span').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						//jQuery('#jform_province').val(province);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}

			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}



function checkSubmitFormNew1(){
	var name = jQuery('#form-name').val();
	var phone = jQuery('#form-phone').val();
	var email = jQuery('#form-email').val();
	var year_old = jQuery('#form-year_old').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
					//if(province != ''){
						jQuery('#BUTTON_TEXT346').attr('onclick','');
						jQuery('#BUTTON_TEXT346 p').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						jQuery('#jform_year_old').val(year_old);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}
			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}

function checkSubmitFormNew2(){
	var name = jQuery('#form-name2').val();
	var phone = jQuery('#form-phone2').val();
	var email = jQuery('#form-email2').val();
	var year_old = jQuery('#form-year_old2').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
					//if(province != ''){
						jQuery('#BUTTON_TEXT583').attr('onclick','');
						jQuery('#BUTTON_TEXT583 p').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						jQuery('#jform_year_old').val(year_old);
						jQuery('#form-registration').submit();
					// }else{
					// 	alert("Vui lòng nhập Tỉnh/TP!");
					// }
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}
			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}

function checkSubmitFormNew3(){
	var name = jQuery('#form-name3').val();
	var phone = jQuery('#form-phone3').val();
	var email = jQuery('#form-email3').val();
	var year_old = jQuery('#form-year_old3').val();
	if(name == ''){
		alert("Vui lòng nhập Họ tên!");
	}else{
		if(phone == '' || phone.length != 10){
			alert("Vui lòng nhập Số điện thoại 10 số!");
		}else{
			if(onlyDigits(phone)){
				if(validateEmail(email)){
						jQuery('#BUTTON_TEXT687').attr('onclick','');
						jQuery('#BUTTON_TEXT687 p').html('Đang xử lý...');
						jQuery('#jform_phone').val(phone);
						jQuery('#jform_name').val(name);
						jQuery('#jform_email').val(email);
						jQuery('#jform_year_old').val(year_old);
						jQuery('#form-registration').submit();
				}else{
					alert("Vui lòng nhập đúng dạng Email!");
				}
			}else{
				alert("Vui lòng nhập Số điện thoại dạng 10 số, ví dụ: 0999888777 .");
			}
		}
	}
}

function validateEmail(email) {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}




</script>
<style>
/* padding-bottom and top for image */
.mfp-no-margins img.mfp-img {
	padding: 0;
}
/* position of shadow behind the image */
.mfp-no-margins .mfp-figure:after {
	top: 0;
	bottom: 0;
}
/* padding for main container */
.mfp-no-margins .mfp-container {
	padding: 0;
}

/* .mfp-with-zoom .mfp-container,
.mfp-with-zoom.mfp-bg {
	opacity: 0;
	-webkit-backface-visibility: hidden;
	-webkit-transition: all 0.3s ease-out;
	-moz-transition: all 0.3s ease-out;
	-o-transition: all 0.3s ease-out;
	transition: all 0.3s ease-out;
}

.mfp-with-zoom.mfp-ready .mfp-container {
		opacity: 1;
}
.mfp-with-zoom.mfp-ready.mfp-bg {
		opacity: 0.8;
}

.mfp-with-zoom.mfp-removing .mfp-container,
.mfp-with-zoom.mfp-removing.mfp-bg {
	opacity: 0;
} */
.mfp-bg {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1042;
    overflow: hidden;
    position: fixed;
    background: #0b0b0b;
    opacity: .8;
    filter: alpha(opacity=80);
}
.mfp-wrap {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1043;
    position: fixed;
    outline: 0!important;
    -webkit-backface-visibility: hidden;
}

.mfp-container {
    text-align: center;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    padding: 0 8px;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}

.mfp-image-holder .mfp-content {
    max-width: 100%;
}

.mfp-content {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    margin: 0 auto;
    text-align: left;
    z-index: 1045;
}

.mfp-iframe-holder .mfp-close, .mfp-image-holder .mfp-close {
    color: #FFF;
    right: -6px;
    text-align: right;
    padding-right: 6px;
    width: 100%;
}

.mfp-zoom-out-cur, .mfp-zoom-out-cur .mfp-image-holder .mfp-close {
    cursor: -moz-zoom-out;
    cursor: -webkit-zoom-out;
    cursor: zoom-out;
}

img.mfp-img {
    width: auto;
    max-width: 100%;
    height: auto;
    display: block;
    line-height: 0;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
    padding: 40px 0;
    margin: 0 auto;
}


.mfp-bottom-bar {
    margin-top: -36px;
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    cursor: auto;
}
.mfp-preloader{
	display: none;
}

.hs_submit{
	float:left;
}
<?php if($contact->id == 9999999999){ ?>

	.hotline-info li {
	    width: 32%!important;
	    text-align: center;
	}
	@media screen and (max-width: 768px){
		.hotline-info li {
		    width: 100%!important;
		    text-align: center;
		}
	}
<?php } ?>

<?php if($_REQUEST['Itemid'] != AGENT ){ ?>
.body-wapper{
  display: none!important;
}
<?php } ?>

<?php if($_REQUEST['Itemid'] == AGENT ){ ?>
.body-wapper{
  display: block!important;
}
<?php } ?>
/* .container-fluid.landingpage{
	display:none;
} */
</style>
