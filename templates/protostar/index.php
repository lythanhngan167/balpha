<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/** @var JDocumentHtml $this */

?>

<?php

$app  = JFactory::getApplication();
$user = JFactory::getUser();
$document = JFactory::getDocument();
// Output as HTML5
$this->setHtml5(true);

// Getting params from template
$params = $app->getTemplate(true)->params;


// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$copyright= $app->input->getCmd('copyright', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');

if ($task === 'edit' || $layout === 'form')
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
// Add Stylesheets
//JHtml::_('stylesheet', 'template.css', array('version' => 'auto', 'relative' => true));
$version = '1017052022';

if($_SERVER['HTTP_HOST'] == 'localhost'){
	$version = time();
}

$document->addStyleSheet($this->baseurl."/templates/protostar/less/template.css?".$version, array('version'=>'auto'));
$document->addStyleSheet($this->baseurl."/templates/protostar/less/custom.css?".$version, array('version'=>'auto'));
$document->addStyleSheet($this->baseurl."/templates/protostar/less/custom-mobile.css?".$version, array('version'=>'auto'));

//if($option != 'com_eshop'){
JHtml::_('stylesheet', 'bootstrap.min.css', array('version' => 'auto', 'relative' => true));
//}
JHtml::_('stylesheet', 'font-awesome/css/font-awesome.min.css', array('version' => 'auto', 'relative' => true));

//landingpage
if($_REQUEST['Itemid'] == TECH_INSURACNE){
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_272.css');
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css');
	$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js','text/javascript', false, false);
	//$document->addScript(JURI::base().'templates/protostar/js/ladipagepage_272.js','text/javascript', false, false);
}

if($_REQUEST['Itemid'] == FOUNDER_STORY){
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_273.css');
	$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css');
	$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js','text/javascript', false, false);
	//$document->addScript(JURI::base().'templates/protostar/js/ladipagepage_272.js','text/javascript', false, false);
}

if($_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE || $_REQUEST['Itemid'] == AGENT){
    $document->addStyleSheet(JURI::base() . '/templates/protostar/css/registrations_landingpage725.css?'.$version);
		$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_423.css?'.$version);
		//$document->addStyleSheet(JURI::base() . '/templates/protostar/css/landingpage_725.css?'.$version);
		$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min-2.css?'.$version);
    //$document->addStyleSheet(JURI::base() . '/templates/protostar/css/ladipage.min.css?'.$version);
		$document->addScript(JURI::base().'templates/protostar/js/jquery.magnific-popup.min.js','text/javascript', false, false);
		//$document->addScript(JURI::base().'templates/protostar/js/ladipage.vi.min.js','text/javascript', false, false);
		$document->addScript(JURI::base().'templates/protostar/js/ladipage.min.js?'.$version,'text/javascript', false, false);
}

//shop
if($option == 'com_eshop'){
	$document->addStyleSheet($this->baseurl."/templates/protostar/css/smart_wizard.min.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/css/smart_wizard_theme_dots.min.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/less/shop1.css?".$version, array('version'=>'auto'));
	$document->addStyleSheet($this->baseurl."/templates/protostar/less/shop2.css?".$version, array('version'=>'auto'));
	JHtml::_('script', 'jquery.smartWizard.min.js', array('version' => $version, 'relative' => true));
}

// Add template js
JHtml::_('script', 'template.js', array('version' => 'auto', 'relative' => true));
JHtml::_('script', 'main.js', array('version' => $version, 'relative' => true));

// Add html5 shiv
JHtml::_('script', 'jui/html5.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));

// Use of Google Font
if ($this->params->get('googleFont'))
{
	$font = $this->params->get('googleFontName');

	// Handle fonts with selected weights and styles, e.g. Source+Sans+Condensed:400,400i
	$fontStyle = str_replace('+', ' ', strstr($font, ':', true) ?: $font);

	JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=' . $font);
	$this->addStyleDeclaration("
	h1, h2, h3, h4, h5, h6, .site-title {
		font-family: '" . $fontStyle . "', sans-serif;
	}");
}

//Copyright
$copyright= $this->params->get('copyright');

// Template color
if ($this->params->get('templateColor'))
{
	$this->addStyleDeclaration('
	body.site {
		border-top: 3px solid ' . $this->params->get('templateColor') . ';
		background-color: ' . $this->params->get('templateBackgroundColor') . ';
	}
	a {
		color: ' . $this->params->get('templateColor') . ';
	}
	.nav-list > .active > a,
	.nav-list > .active > a:hover,
	.dropdown-menu li > a:hover,
	.dropdown-menu .active > a,
	.dropdown-menu .active > a:hover,
	.nav-pills > .active > a,
	.nav-pills > .active > a:hover,
	.btn-primary {
		background: ' . $this->params->get('templateColor') . ';
	}');
}

// Check for a custom CSS file
JHtml::_('stylesheet', 'user.css', array('version' => 'auto', 'relative' => true));

// Check for a custom js file
JHtml::_('script', 'user.js', array('version' => 'auto', 'relative' => true));

// Load optional RTL Bootstrap CSS
JHtml::_('bootstrap.loadCss', false, $this->direction);

// Adjusting content width
$position7ModuleCount = $this->countModules('position-7');
$position8ModuleCount = $this->countModules('position-8');
$positionBannerAds = $this->countModules('banner-ads');

if ($position7ModuleCount && $position8ModuleCount)
{
	$span = 'span6';
}
elseif ($position7ModuleCount && !$position8ModuleCount)
{
	$span = 'span9';
}
elseif (!$position7ModuleCount && $position8ModuleCount)
{
	$span = 'span9';
}
else
{
	$span = 'span12';
}

// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="' . htmlspecialchars(JUri::root() . $this->params->get('logoFile'), ENT_QUOTES) . '?t=0319012022" alt="' . $sitename . '" />';
	if($_REQUEST['is_app'] == 1){
		$logo = '<img src="' . JUri::root().'/images/logo-mxh.png'.'?t=0319012022" alt="' . $sitename . '" />';
	}
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . htmlspecialchars($this->params->get('sitetitle'), ENT_COMPAT, 'UTF-8') . '</span>';
}
else
{
	$logo = '<span class="site-title" title="' . $sitename . '">' . $sitename . '</span>';
}
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PBLV7QF');</script>
<!-- End Google Tag Manager -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<jdoc:include type="head" />
	<?php
	//homepage
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$is_home_page = 0;
	if ($menu->getActive() == $menu->getDefault()) {
		$is_home_page = 1;
	}
	$is_social = 0;
	if ($_REQUEST['option'] == 'com_community') {
		$is_social = 1;
	}
	//landingpage
	$is_landingpage = 0;
	switch ($_REQUEST['Itemid']) {
		case TECH_INSURACNE:
			$is_landingpage = 1;
			break;

		case FOUNDER_STORY:
			$is_landingpage = 1;
			break;

		case FOUR_ZERO_INSURACNE:
			$is_landingpage = 1;
			break;

		case AGENT:
			$is_landingpage = 1;
			break;
		case REFERRAL:
			$is_landingpage = 1;
			$position8ModuleCount = -1;
			break;
		default:
			$is_landingpage = 0;
			break;
	}
	?>

	<style>
	<?php if ($_REQUEST['Itemid'] == REFERRAL) { ?>
		#top_main_menu_mobile{display:none;}
		#mySidenav{display:none !important;}
		.desktop .body{margin-left: 0px;}
	<?php } ?>
	<?php
	//check Group User
	$groups = JAccess::getGroupsByUser($user->id, false);
	$group_id = $groups[0];

			if ($group_id == 2) { ?>
					#mySidenav2 .supporter-loged{
						display: none;
					}
			<?php }
			if ($group_id == 3) { ?>
					#mySidenav2 .register-loged{
						display: none;
					}
			<?php }
			if ($group_id == 4) { ?>
				#mySidenav2 .author-loged{
					display: block;
				}
				#mySidenav2 .supporter-loged{
					display: none!important;
				}

				#mySidenav2 .register-loged{
					display: none;
				}

		<?php }

	?>
	<?php
		if ($group_id == 3 &&
		($_REQUEST['Itemid'] == ACCOUNT_PROFILE_PAGE
		|| $_REQUEST['Itemid'] == ACCOUNT_PROJECT_PAGE)) { ?>
			.content-profile-noti{
				display: block;
			}
	<?php	}
	 ?>
	<?php
	//hide menu login
	if($user->id > 0){ ?>
		li.item-130{
			display:none;
		}
		li.item-335{
			display:block;
			padding-right: 8px;
    	padding-top: 10px;
			min-width: 100px;
    	text-align: center;
		}
		.logo-search-account .right-logo-search-account .mod-list li.item-335 a:before {
    	content: "\f08b";
		}
	<?php	}else{ ?>
		li.item-130{
			display:block;
		}
		li.item-335{
			display:none;
		}
		.logo-search-account .right-logo-search-account .mod-list li.item-335 a:before {
    	content: "\f08b";
		}
		<?php if($_REQUEST['option'] == 'com_k2' && $_REQUEST['view'] == 'item' && $_REQUEST['layout'] == 'item'): ?>
			#system-message-container{
				display: none;
			}
		<?php endif; ?>
	<?php } ?>

	<?php if(!$is_home_page){ ?>
		.footer-module {
		  padding-left: 103px!important;
		}
		div.k2filter-table {
			margin-top: 0px;
		}
		div.k2filter-table {
			width:auto;
			border-radius:none;
			box-shadow:none;
			height:auto;
		}
		.k2filter-cell1 input{
			height:auto!important;
		}
		.k2filter-cell1{
			padding:0px!important;
		}
	<?php } ?>

	<?php if($position8ModuleCount){ ?>
		<?php if(($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')): ?>
		.main-content .left-main-content {
		  flex: 0 0 100%!important;
		}
		<?php endif; ?>

		<?php if((!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')) && ($position7ModuleCount > 0)): ?>
		.main-content .left-main-content {
		  flex: 0 0 74%!important;
		}
		<?php endif; ?>

		.autobuy-data{
		  flex: 0 0 33.2%!important;
		}

		@media (max-width: 768px) {
			.main-content .left-main-content {
			  flex: 0 0 100%!important;
			}
			.autobuy-data{
			  flex: 0 0 100%!important;
				text-align: center!important;
			}
		}

	<?php } ?>
	<?php if($option == 'com_community'){ ?>

	.footer {
    position: inherit!important;
    z-index: 0;
	}

	<?php } ?>

	<?php if($option != 'com_community'){ ?>
		#joms-chatbar{
			display: none!important;
		}
	<?php } ?>


	<?php
		if($_REQUEST['is_app'] == 1){
		 ?>
		.main-menu{
			display: none!important;
		}
		.right-logo-search-account{
			display: none;
		}
		.center-logo-search-account{
			display: none;
		}
		.left-logo-search-account{
			padding-bottom: 5px;
    	padding-top: 0px;
		}
		.social-bca-vietnam{
			display: none;
		}
		.remind-link{
			display: none;
		}
		.reset-link{
			display: none;
		}
		.col-footer2{
			display: none;
		}
		.footer-module{
			display: none;
		}
		@media (max-width: 768px) {
			.remind-link{
				display: none!important;
			}
			.reset-link{
				display: none!important;
			}
			.body .container {
    		padding: 0px 0px 0px 0px;
			}
			.jomsocial-wrapper .jomsocial {
    		padding: 10px 0px 0px 0px;
			}
			.joms-landing__cover:before {
		    padding-top: 165px;
			}
			.joms-landing__content{
				background: none;
			}
			.jomsocial-wrapper .jomsocial {
    		padding: 0px 0px 0px 0px;
			}
			.left-main-content #content{
				padding-left: 0px;
    		padding-right: 0px;
			}
		}

	<?php } ?>

	<?php if($_REQUEST['Itemid'] == AGENT){ ?>
		.desktop .container {
		    max-width: 100%!important;
		}
		.body#top {
    	padding-top: 0px;
		}
	<?php } ?>


	<?php
		// hoidap FAQ
		if($_REQUEST['Itemid'] == 387 || $_REQUEST['Itemid'] == 382
		|| $_REQUEST['Itemid'] == 386 || $_REQUEST['Itemid'] == 383
		|| $_REQUEST['Itemid'] == 385 || $_REQUEST['Itemid'] == 384
		|| $_REQUEST['Itemid'] == 347){ ?>
			.main-content .container .row{
				display:block!important;
			}
			.right-main-content{
				display:block!important;
				float:left;
				margin-top: 80px;
			}
			.left-main-content{
				display:block!important;
				float:right;
			}
			.itemListCategory h2{
				margin-left:-35%;
			}
			#itemListPrimary .item .groupPrimary .catItemHeader{
				width: 100%;
			}

	<?php } ?>


	<?php
		// error 404
		if($_REQUEST['Itemid'] == 259){ ?>
			.itemRatingBlock{
				display:none;
			}
			.author-bottom{
				display:none;
			}
	<?php } ?>




	</style>

	<?php if($is_home_page == 1){
		$config = JFactory::getConfig();
		$og_title = $config->get('sitename');
		$og_meta_desc = $config->get('MetaDesc');
	?>
	<meta property="og:url" content="<?php echo JURI::base(); ?>" />
	<meta property="og:title" content="<?php echo $og_title; ?>" />
	<meta property="og:image" content="<?php echo JURI::base(); ?>images/bca-logo-blue2.png" />
	<meta property="og:description" content="<?php echo $og_meta_desc; ?>" />
	<meta property="og:type" content="home" />
	<meta property="og:image:width" content="600" />
	<meta property="og:image:height" content="400" />
	<?php } ?>
	<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
	<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async=""></script>
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
    OneSignal.init({
      appId: "e9060a2d-50ee-4195-927d-313fdff94973",
    });
		OneSignal.getUserId(function(userId) {
    	console.log("OneSignal User ID:", userId);
  	});
  });
</script>
</head>
<body class="<?php if($is_home_page){ ?>homepage<?php } ?> site  <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '')
	. ($this->direction === 'rtl' ? ' rtl' : '');
?>">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PBLV7QF"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->


<div id="mySidenav" class="sidenav">
<a href="javascript:void(0)" class="closebtn">×</a>

<div class="nav-collapse-mobile">
	<div class="welcome-user">
	<jdoc:include type="modules" name="mobile-user" style="xhtml" />
	</div>

	<jdoc:include type="modules" name="position-1" style="none" />
	<jdoc:include type="modules" name="bottom-1" style="xhtml" />
	<jdoc:include type="modules" name="bottom-2" style="xhtml" />
	<jdoc:include type="modules" name="bottom-3" style="xhtml" />
	<jdoc:include type="modules" name="mobile-menu" style="xhtml" />
</div>
</div>


<?php if ($is_landingpage == 1) : ?>
<div class="container-fluid landingpage">
	<section id="sp-banner-body" class="banner-agents-top">
		<jdoc:include type="modules" name="landingpage" style="none" />
	</section>
</div>
<?php endif; ?>

<?php if ($is_landingpage == 0) : ?>
<!-- <div class="container-fluid banner-top">
</div> -->

<?php if ($this->countModules('position-1')) : ?>
	<div class="container-fluid main-menu">
		<div class="container">
			<div class="row">
				<div class="main-menu-wapper">
				<div class="col-xs-12 col-md-12 left-main-menu">
						<nav class="navigation" role="navigation">
							<div class="navbar pull-right">
								<a class="btn btn-navbar collapsed" id="toggle-menu" data-toggle="collapse" data-target=".nav-collapse">
									<span class="element-invisible"><?php echo JTEXT::_('TPL_PROTOSTAR_TOGGLE_MENU'); ?></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</a>
								<input type="hidden" id="toggle-menu-hidden" value="0" />
							</div>

							<div class="col-xs-12 col-md-2 left-logo-search-account">
								<a class="brand pull-left logo-desktop" href="<?php echo $this->baseurl; ?>/">
									<?php //echo $logo; ?>
									<img width="155" height="60" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-vertical.svg" alt="<?php echo $sitename ?>" />
									<?php if ($this->params->get('sitedescription')) : ?>
										<?php //echo '<div class="site-description">' . htmlspecialchars($this->params->get('sitedescription'), ENT_COMPAT, 'UTF-8') . '</div>'; ?>
									<?php endif; ?>
								</a>

							</div>
							<div class="nav-collapse">
								<jdoc:include type="modules" name="position-1" style="none" />
							</div>
						</nav>
				</div>
				<div id="mySidenav2" class="sidenav2">
					<div>
						<a href="#idcontact"><button type="button" class="btn btn-primary button-sidenav2" name="button" class="btn-menu-right-mobile">Liên hệ tư vấn</button></a>
						<!-- index.php?Itemid=196 <button type="button" name="button" class="btn-menu-right-mobile btn-primary" <a href="#">Gửi yêu cầu tư vấn</a> </button> -->

						<a href="javascript:void(0)" class="closebtn">×</a>
						<?php
						if($user->id > 0){?>
							<jdoc:include type="modules" name="mobile-right-loged" style="xhtml" />
						<?php }
						else {?>
							<jdoc:include type="modules" name="mobile-right-visiter" style="xhtml" />
						<?php } ?>

						<jdoc:include type="modules" name="mobile-menu" style="xhtml" />
					</div>
				</div>
			</div>
		</div>
	</div>

	</div>
	<?php endif; ?>

	<div class="container-fluid logo-search-account">
		<div class="container">
			<div class="row">

				<!-- <div class="col-xs-12 col-md-6 center-logo-search-account">
					<jdoc:include type="modules" name="position-157" style="xhtml" />
				</div> -->
				<div class="col-xs-12 col-md-4 right-logo-search-account">
					<jdoc:include type="modules" name="position-16" style="xhtml" />
				</div>
		</div>
	</div>
	</div>

	<!-- <div class="container-fluid address-phone-email">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-2 left-address-phone-email">
					<div class="navbars">
						<div id="insurance-category-text"><i class="fa fa-bars" aria-hidden="true"></i> <?php echo JText::_('CATEGORY-INSURANCE'); ?></div>
						<div id="insurance-category-hidden">
							<div class="insurance-category-hidden-wrapper" style="display:none;">
								<?php if(!$is_home_page){ ?>
									<jdoc:include type="modules" name="category" style="xhtml" />
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-md-8 center-address-phone-email">
					<jdoc:include type="modules" name="position-14" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-2 btn-request right-address-phone-email">
					<a href="index.php?Itemid=196"><button type="submit" class="btn btn-primary">
						<?php echo JText::_('SEND_REQUEST'); ?> <i class="fa fa-headphones" aria-hidden="true">&nbsp;</i>

					</button></a>
				</div>
		</div>
	</div>
	</div> -->

	<?php endif; ?>

	<?php if($_REQUEST['Itemid'] == AGENT){ ?>
	<div id="footer-landingpage" class="footer-landingpage">
		<jdoc:include type="modules" name="landingpage-footer" />
	</div>
	<?php } ?>

	<?php if(!$is_home_page){ ?>
	<div class="body-wapper">
	<div class="body" id="top">
		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid background-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 background-top-image">
						</div>
				</div>
			</div>
			</div>
		<?php } ?>
		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid loop-line-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 loop-line">
						</div>

				</div>
			</div>
			</div>
		<?php } ?>

		<?php if(!$is_home_page && $_REQUEST['Itemid'] != AGENT){ ?>
			<div class="container-fluid slide-categories">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 category category-no-homepage">
							<jdoc:include type="modules" name="position-15" style="xhtml" />
						</div>

				</div>
			</div>
			</div>
		<?php } ?>


		<?php if(!$is_home_page){ ?>
			<!-- <div class="container-fluid loop-line2-container">
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 loop-line2">
						</div>

				</div>
			</div>
			</div> -->
		<?php } ?>
		<?php if(!$is_home_page){ ?>
			<div class="container-fluid breadcrumbs">
				<div class="container">
					<div class="row">
						<div class="col-xs-12 col-md-12 left-breadcrumbs">
								<jdoc:include type="modules" name="position-3" style="xhtml" />
						</div>
				</div>
			</div>
			</div>
		<?php } ?>
		<?php if($_REQUEST['Itemid'] != REFERRAL){ ?>
		<div class="container-fluid main-content">
			<div class="container">
				<div class="row">
		<?php } ?>
					<?php
					if ($position8ModuleCount) :
						if(!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')): // profile consultinger
					?>
					<?php
						if ($group_id == 3 || $group_id == 4) { ?>
							<div class="col-xs-12 left1-main-content col-md-3">
							<div id="aside" class="<?php //echo span3; ?>">
								<jdoc:include type="modules" name="position-8" style="well" />
							</div>
							</div>
					<?php	}
					 ?>

						<?php endif; ?>
					<?php endif; ?>
					<div class="col-xs-12 left-main-content
					<?php if ($position7ModuleCount || $position8ModuleCount) : ?>
						<?php
							if((!($_REQUEST['option'] == 'com_joomprofile' && $_REQUEST['view'] == 'profile' && $_REQUEST['task'] == 'user.display')) && ($position7ModuleCount > 0 || $position8ModuleCount > 0)): // profile consultinger
						?> col-lg-9 col-md-12
						<?php else: ?>
						col-lg-12 col-md-12
					<?php endif; ?>
					<?php else: ?>col-md-12<?php endif; ?>">
						<!-- <?php if (!$position7ModuleCount) : ?>col-md-12<?php endif; ?> -->
						<main id="content" role="main" class=" <?php //echo $span; ?>">
							<!-- Begin Content -->

							<jdoc:include type="message" />

							<?php if($_REQUEST['option'] == 'com_community' || $is_home_page == 1){ ?>
							<div style="color:red;">
								Phiên bản thử nghiệm MXH BCA này sẽ bị xoá và thay thế bởi 1 phiên bản tốt hơn. <br>
								Và trang này sẽ bị đóng lại vào hết ngày 12/4/2021. <br>
								Anh chị Đại lý / TVV vui lòng lưu trử lại nhưng hình ảnh, thông tin cần thiết để cập nhật lên MXH BCA mới. <br>
								Anh chị có thể tự tạo tài khoản theo username yêu thích tại MXH BCA mới tại địa chỉ:<br>
								 <a style="color:red; font-weight:bold;" href="http://mxh.bcavietnam.com">http://mxh.bcavietnam.com</a>
								<br>Xin cảm ơn!

							</div>
							<a href="index.php?Itemid=455"><h3 class="social-bca-vietnam"><i class="fa fa-comments" aria-hidden="true"></i> Cộng đồng BCA</h3></a>
							<?php } ?>
							<?php if ($positionBannerAds): ?>
							<div class="banner-ads">
								<jdoc:include type="modules" name="banner-ads" style="xhtml" />
							</div>
							<?php endif; ?>
							<?php if(($option == 'com_k2' && $view == 'item')){ // for search k2 ?>

							<?php }else{  ?>
								<jdoc:include type="modules" name="search-k2" style="xhtml" />
							<?php } ?>

							<?php if($option == 'com_eshop' && $view == 'frontpage'): ?>
							<jdoc:include type="modules" name="banner-eshop" style="xhtml" />
							<?php endif; ?>

							<jdoc:include type="component" />

							<?php if($option == 'com_eshop' && $view == 'frontpage'): ?>
							<jdoc:include type="modules" name="category-eshop" style="xhtml" />
							<?php endif; ?>
							<div class="clearfix"></div>
							<jdoc:include type="modules" name="position-2" style="none" />
							<div class="content-com_users">
								<?php
									if ( $_REQUEST['Itemid'] == ACCOUNT_PROJECT_PAGE) { //$_REQUEST['Itemid'] == ACCOUNT_PROFILE_PAGE ||
										 ?>
										<jdoc:include type="modules" name="bottom-4" style="xhtml" />
								<?php	}
								 ?>
							</div>

							<!-- End Content -->
						</main>
					</div>

						<?php if ($position7ModuleCount) : ?>
							<div class="col-xs-12 right-main-content col-md-3">
							<div id="aside" class="<?php //echo span3; ?>">

								<jdoc:include type="modules" name="position-7" style="well" />
							</div>
							</div>
						<?php endif; ?>

		<?php if($_REQUEST['Itemid'] != REFERRAL){ ?>
			</div>
		</div>
		</div>
		<?php } ?>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid slide-categories">
		<div class="container-fluid">
			<div class="row">
				<?php if($is_home_page == 1){ ?>
				<!-- <div class="social-mobile"><a href="index.php?Itemid=455"><h3 class="social-bca-vietnam"><i class="fa fa-comments" aria-hidden="true"></i> Cộng đồng BCA</h3></a></div> -->
				<?php } ?>

				<div class="col-xs-12 col-md-12 col-lg-12 banner-1">
					<jdoc:include type="modules" name="banner-1" style="xhtml" />
				</div>


		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid loop-line-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 loop-line">
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid loop-line2-container">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 loop-line2">
				</div>

		</div>
	</div>
	</div>
<?php } ?>


<?php if($is_home_page){ ?>
	<div class="container-fluid slide-categories">
		<div class="container">
			<div class="row">


				<div class="col-xs-12 category">
					<jdoc:include type="modules" name="position-15" style="xhtml" />
					<jdoc:include type="modules" name="category" style="xhtml" />
				</div>


		</div>
	</div>
	</div>
<?php } ?>
<?php if($is_home_page){ ?>
	<!-- <div class="container-fluid loan">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-loan">
					<jdoc:include type="modules" name="position-9" style="xhtml" />
				</div>

		</div>
	</div>
	</div> -->
<?php } ?>
<?php if($is_home_page){ ?>
	<div class="container-fluid comparation">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-comparation left-comparation1">

					<div class="box-service">
						<div class="image"></div>
						<div class="info">
							<a href="#" class="item-center"><span class="special-item-center">BẢO HIỂM</span> <span class="special-item-center">CÔNG NGHỆ</span> <span class="special-item-center">4.0</span></a>
							<div class="item item-special-1">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="160.003" height="140" viewBox="0 0 160.003 140"><g transform="translate(98.785 59)"><g transform="translate(-96 -161)"><path d="M0,0V140H140V68.2l-20,20V120H20V20h71.8L111.79,0ZM140,0,79.993,60.007l-19.985-20-20,20L79.993,100,160,20Z" transform="translate(-2.785 102)" fill="#f8f8f8"/></g></g></svg>


									</div></a>
									<div class="box-service-item-info">
										<h3 class="">GIẢI PHÁP DATA CENTER</h3>
											<ul class="special-balpha one">
												<li>Tìm kiếm Data nóng, Khách hàng có nhu cầu</li>
												<li>Data không trùng lặp</li>
												<li>Data được Remarketing</li>
											</ul>
									</div>
								</div>
							</div> <div class="item item-special-2">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="122.22" height="140" viewBox="0 0 122.22 140"><defs><clipPath id="a"><rect width="122.22" height="140" fill="none"/></clipPath></defs><g transform="translate(89.785 59)"><g transform="translate(-89.785 -59)"><g transform="translate(0 0)" clip-path="url(#a)"><path d="M87.456,0a35.666,35.666,0,0,0-24.7,10.335l-48.7,47.648a48.061,48.061,0,0,0,67.968,67.968l21.9-21.9L91.835,91.967l-20.32,19.795-1.577,2.277a30.978,30.978,0,0,1-43.794,0c-11.894-11.912-11.562-31.181,0-43.268L74.843,22.6a18.085,18.085,0,0,1,25.225,0c6.832,6.832,6.481,17.693,0,24.7L56.275,90.566a4.707,4.707,0,0,1-6.657-6.657l1.051-.526L66.61,66.917,54.523,54.83,37.531,71.822a21.677,21.677,0,0,0,30.656,30.656L111.98,59.735A34.948,34.948,0,0,0,87.281.175Z" transform="translate(-0.008 -0.009)" fill="#f8f8f8"/></g></g></g></svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">QUY TRÌNH GIAO KẾT HỢP ĐỒNG ĐẶC BIỆT</h3>
										<ul class="special-balpha two">
											<li>Minh bạch, tinh gọn</li>
											<li>Có sự tham gia của bên thứ ba – đại diện về mặt pháp lý</li>
											<li>Quy trình được ghi âm, ghi hình</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="item item-special-3">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="140" height="140" viewBox="0 0 140 140"><defs><clipPath id="a"><rect width="140" height="140" fill="none"/></clipPath></defs><g transform="translate(98.785 59)"><g transform="translate(-98.785 -59)"><g clip-path="url(#a)"><path d="M52.5,0A17.723,17.723,0,0,0,39.9,4.9,17.723,17.723,0,0,0,35,17.5c0,4.9,3.15,8.4,4.9,12.6.525,1.05.525,2.8.525,4.9H0V140H40.425a17.281,17.281,0,0,0-.525-4.9c-1.75-4.2-4.9-7.7-4.9-12.6a17.723,17.723,0,0,1,4.9-12.6A17.723,17.723,0,0,1,52.5,105a17.723,17.723,0,0,1,12.6,4.9A17.723,17.723,0,0,1,70,122.5c0,4.9-3.15,8.4-4.9,12.6-.525,1.05-.525,2.8-.525,4.9H105V99.575a17.281,17.281,0,0,1,4.9.525c4.2,1.75,7.7,4.9,12.6,4.9a17.506,17.506,0,1,0,0-35c-4.9,0-8.4,3.15-12.6,4.9-1.05.525-2.8.525-4.9.525V35H64.575a17.281,17.281,0,0,1,.525-4.9c1.75-4.2,4.9-7.7,4.9-12.6A17.723,17.723,0,0,0,65.1,4.9,17.723,17.723,0,0,0,52.5,0" transform="translate(12 0)" fill="#f8f8f8"/></g></g></g></svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">SIÊU THỊ BẢO HIỂM ĐA DẠNG CÁC SẢN PHẨM</h3>
										<ul class="special-balpha two">
											<li>Hơn 200 sản phẩm bảo hiểm nhân thọ và phi nhân thọ</li>
											<li>Đối tác là các doanh nghiệp bảo hiểm uy tín</li>
											<li>Trao quyền chủ động lựa chọn sản phẩm cho khách hàng</li>
										</ul>

									</div>
								</div>
							</div>
							<div class="item item-special-4">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="142" height="142" viewBox="0 0 142 142"><defs><clipPath id="a123"><rect width="142" height="142" transform="translate(-0.24 -0.331)" fill="none"/></clipPath></defs><g transform="translate(99.785 60)"><g transform="translate(-99.545 -59.669)"><g clip-path="url(#a123)"><path d="M70.669,0C51.235,0,35.334,19.787,35.334,44.168s15.9,44.168,35.334,44.168S106,68.549,106,44.168,90.1,0,70.669,0M33.744,88.336A35.37,35.37,0,0,0,0,123.67v17.667H141.337V123.67a35.246,35.246,0,0,0-33.744-35.334C98.053,99.113,84.979,106,70.669,106s-27.384-6.89-36.924-17.667" fill="#f8f8f8"/></g></g></g></svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="">ĐỘI NGŨ TƯ VẤN TÀI CHÍNH</h3>
										<ul class="special-balpha two">
											<li>Kiến thức chuyên môn vững chắc</li>
											<li>Có bề dày kinh nghiệm</li>
											<li>Khách quan, trung thực, tận tâm</li>
										</ul>

									</div>
								</div>
							</div>
							<div class="item item-special-5">
								<div class="box-service-item">
									<a href="#" class="box-service-item-icon"><div class="item-icon">
										<svg xmlns="http://www.w3.org/2000/svg" width="139.978" height="140.004" viewBox="0 0 139.978 140.004"><g transform="translate(0 0)"><g transform="translate(98.785 60)"><g transform="translate(41.193 76.005)"><path d="M5.961.039A8.743,8.743,0,0,0,.013,8.787V96.265a8.751,8.751,0,0,0,8.748,8.748H52.5v17.5H35a17.547,17.547,0,0,0-17.5,17.5H122.482a17.547,17.547,0,0,0-17.5-17.5h-17.5v-17.5H131.23a8.751,8.751,0,0,0,8.748-8.748V8.787A8.751,8.751,0,0,0,131.23.039H8.761a7.893,7.893,0,0,0-1.575,0q-.525-.026-1.05,0Zm11.547,17.5H122.482V87.517H17.509Z" transform="translate(-139.978 -136.004)" fill="#f8f8f8"/></g></g></g></svg>

									</div></a>
									<div class="box-service-item-info">
										<h3 class="balpha-academy">HỌC VIỆN BẢO HIỂM SỐ B-Alpha ACADEMY</h3>
										<ul class="special-balpha two">
											<li>Tiên phong đào tạo nguồn nhân lực bảo hiểm</li>
											<li>Chuyển giao nền tảng công nghệ</li>
											<li>Trao kiến thức, niềm tin cho mọi người dân Việt Nam</li>
										</ul>

									</div>
								</div>
							</div>


						</div>
					</div>
				</div>
					<!-- <jdoc:include type="modules" name="position-13" style="xhtml" /> -->


		</div>
	</div>
	</div>
<?php } ?>
<?php if($is_home_page){ ?>
	<div class="container-fluid partner">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-partner">
					<jdoc:include type="modules" name="position-12" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>



<?php if($is_home_page){ ?>
	<div class="container-fluid customers">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-customers">
					<jdoc:include type="modules" name="position-18" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid comparation">
		<div class="container">
				<div class="row">
					<div class="col-xs-12 col-md-12 left-comparation">
						<jdoc:include type="modules" name="position-11" style="xhtml" />
					</div>
				</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-md-12 left-comparation">
					<div class="box-service-2">
						<div class="col-image1">
							<div class="content-dk content-image">
								<a class="btn-dk" href="https://insurance.bcavietnam.com/ctv-bao-hiem-online">Đăng ký ngay</a>
								<p>Hotline:<a class="hotline-balpha" href="tel:1900888647">1900888647</a></p>
							</div>

						</div>
						<div class="col-image11">
							<div class="corner-image">
								<img width="38" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/corner.png" alt="" />
							</div>
						</div>
						<div class="bg-info1"></div>
						<div class="col-info">
							<h2 class="">
							<img width="220" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-text-balpha.png?t=123" alt="<?php echo $sitename ?>" />
							</h2>
							<h3 class="">Nền tảng phân phối bảo hiểm online</h3>
							<ul>
								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" width="139.978" height="140.004" viewBox="0 0 139.978 140.004"><g transform="translate(0 0)"><g transform="translate(98.785 60)"><g transform="translate(41.193 76.005)"><path d="M5.961.039A8.743,8.743,0,0,0,.013,8.787V96.265a8.751,8.751,0,0,0,8.748,8.748H52.5v17.5H35a17.547,17.547,0,0,0-17.5,17.5H122.482a17.547,17.547,0,0,0-17.5-17.5h-17.5v-17.5H131.23a8.751,8.751,0,0,0,8.748-8.748V8.787A8.751,8.751,0,0,0,131.23.039H8.761a7.893,7.893,0,0,0-1.575,0q-.525-.026-1.05,0Zm11.547,17.5H122.482V87.517H17.509Z" transform="translate(-139.978 -136.004)" fill="#f8f8f8"/></g></g></g></svg>

								</span>Kinh doanh bảo hiểm online mọi lúc mọi nơi</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="142" height="142" viewBox="0 0 142 142"><defs><clipPath id="a124"><rect width="142" height="142" transform="translate(-0.24 -0.331)" fill="none"/></clipPath></defs><g transform="translate(99.785 60)"><g transform="translate(-99.545 -59.669)"><g transform="translate(0)" clip-path="url(#a124)"><path d="M70.669,0a70.669,70.669,0,1,0,70.669,70.669A70.649,70.649,0,0,0,70.669,0m0,17.667c5.83,0,11.307,1.59,16.607,3.357-3.71,3.533-7.95,6.714-7.244,9.894s12.19,2.3,12.19,8.834c0,4.77-7.42,6.184-2.3,11.66,6.184,6.184-11.307,17.314-11.66,25.441-.53,14.664,14.84,17.137,27.031,17.137,7.42,0,9.364,3.533,8.834,7.774-9.54,13.6-25.794,22.084-43.638,22.084a50.658,50.658,0,0,1-18.727-3.887c3.887-7.774-4.947-23.144-13.25-28.091-4.063-4.063-12.72-2.473-17.667-4.417-1.59-4.77-3.18-9.54-3.357-14.84a3.125,3.125,0,0,1,2.827-1.59c3.357,0,7.95,6.714,10.424,6.007,3.18-.707-13.074-23.144-5.477-27.561,3.533-2.12,10.6,6.89,8.3-2.827-2.12-9.01,6.36-4.947,11.66-7.244,4.593-1.943,7.95-7.244,2.3-10.424a18.885,18.885,0,0,1-3.887-3.357,52.438,52.438,0,0,1,27.031-7.774ZM111.48,36.924a58.43,58.43,0,0,1,7.774,12.72v.53c-.707,1.237-1.943,1.943-3.887,3.887-4.947,4.947-5.653-3.71-7.774-5.477-2.3-2.12-10.6.353-11.66-2.3-1.237-3.18,8.834-7.42,15.547-9.364" fill="#f8f8f8"/></g></g></g></svg>

								</span>B-Alpha - Nền tảng phân phối bảo hiểm online</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" width="140" height="140" viewBox="0 0 140 140"><g transform="translate(98.785 59)"><g transform="translate(-41.785 127)"><path d="M123.025,0,70,52.5,52.5,35,0,88.025l17.5,17.5L52.5,70,70,87.5l70-70L123.025,0M0,140H140V122.5H0Z" transform="translate(-57 -186)" fill="#f8f8f8"/></g></g></svg>

								</span>Nguồn khách hàng không giới hạn với Data Center</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="121.358" height="140" viewBox="0 0 121.358 140"><defs><clipPath id="a"><rect width="121.358" height="140" transform="translate(0 0)" fill="none"/></clipPath></defs><g transform="translate(89.785 59)"><g transform="translate(-67.785 -59)"><g transform="translate(-22 0)" clip-path="url(#a)"><path d="M102.592,72.5a37.91,37.91,0,0,0-5.38-2.5,37.909,37.909,0,0,0,5.38-2.5,37.609,37.609,0,0,0,18.767-32.466,37.579,37.579,0,0,0-37.534,0,39.613,39.613,0,0,0-4.879,3.378A37.222,37.222,0,0,0,60.679,0,37.222,37.222,0,0,0,42.413,38.409a34.139,34.139,0,0,0-4.879-3.441A37.581,37.581,0,0,0,0,34.969,37.346,37.346,0,0,0,18.767,67.435a37.91,37.91,0,0,0,5.38,2.5A37.02,37.02,0,0,0,0,104.906a37.579,37.579,0,0,0,37.534,0,39.613,39.613,0,0,0,4.879-3.378,38.893,38.893,0,0,0-.5,6.005A37.515,37.515,0,0,0,60.679,140a37.222,37.222,0,0,0,18.266-38.409,33.782,33.782,0,0,0,4.879,3.378,37.581,37.581,0,0,0,37.534,0A37.609,37.609,0,0,0,102.592,72.5M60.679,95.022A25.022,25.022,0,1,1,85.7,70,25.015,25.015,0,0,1,60.679,95.022" fill="#f8f8f8"/></g></g></g></svg>


								</span>Không áp lực doanh số, doanh số được tích luỹ theo thời gian</li>

								<li><span>
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="142" height="142" viewBox="0 0 142 142"><defs><clipPath id="a123"><rect width="142" height="142" transform="translate(-0.24 -0.331)" fill="none"/></clipPath></defs><g transform="translate(99.785 60)"><g transform="translate(-99.545 -59.669)"><g clip-path="url(#a123)"><path d="M70.669,0C51.235,0,35.334,19.787,35.334,44.168s15.9,44.168,35.334,44.168S106,68.549,106,44.168,90.1,0,70.669,0M33.744,88.336A35.37,35.37,0,0,0,0,123.67v17.667H141.337V123.67a35.246,35.246,0,0,0-33.744-35.334C98.053,99.113,84.979,106,70.669,106s-27.384-6.89-36.924-17.667" fill="#f8f8f8"/></g></g></g></svg>

								</span>Hơn 1000 chuyên gia sẵn sàng tư vấn hỗ trợ cho bạn</li>

							</ul>
							<div class="content-dk content-dk-mobile">
								<a class="btn-dk" href="https://insurance.bcavietnam.com/ctv-bao-hiem-online">Đăng ký ngay</a>
								<p class="holine-mobile">Hotline:<a class="hotline-balpha" href="tel:1900888647">1900888647</a></p>



							</div>
						</div>
					</div>
				</div>

		</div>
	</div>
	</div>
<?php } ?>


<?php if($is_home_page){ ?>
	<div class="container-fluid knowledge">
		<div class="container">
			<div class="row">
				<div class="knowledge-description">
					<jdoc:include type="modules" name="position-17" style="xhtml" />
				</div>
				<div class="col-xs-12 knowledge-wapper">
					<jdoc:include type="modules" name="position-10" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
<?php } ?>

<?php if($is_home_page){ ?>
	<div class="container-fluid request-consulting">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 left-consulting">
					<jdoc:include type="modules" name="position-4" style="xhtml" />
				</div>

		</div>
	</div>
	</div>
	<div class="container-fluid request-consulting">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 right-consulting">
					<jdoc:include type="modules" name="position-5" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
<?php } ?>
<?php if ($is_landingpage == 0) : ?>
	<div class="container-fluid footer-module">
		<div class="container">
			<div class="row">

				<div class="col-xs-12 right-footer-module">
					<jdoc:include type="modules" name="newsletter" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
	<div class="container-fluid footer-module footer2">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-4 col-footer2">
					<!-- <div class="col-xs-3 col-md-3 left-footer-module">
						<img src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/logo-footer.png?t=123" alt="<?php echo $sitename ?>" />
					</div> -->
					<jdoc:include type="modules" name="bottom-1" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-4 col-footer2">
					<jdoc:include type="modules" name="bottom-2" style="xhtml" />
				</div>
				<!-- <div class="col-xs-12 col-md-2 col-footer2">
					<jdoc:include type="modules" name="bottom-3" style="xhtml" />
				</div>
				<div class="col-xs-12 col-md-3 col-footer2">
					<jdoc:include type="modules" name="bottom-4" style="xhtml" />
				</div> -->
				<div class="col-xs-12 col-md-4 col-footer2 end">
					<jdoc:include type="modules" name="bottom-5" style="xhtml" />
				</div>
		</div>
	</div>
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container-fluid">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : ''); ?>">

			<jdoc:include type="modules" name="footer" style="xhtml" />
			<p class="pull-right">
				<!-- <a href="#top" id="back-top">
					<?php echo JText::_('TPL_PROTOSTAR_BACKTOTOP'); ?>
				</a> -->
				<a href="#" class="sp-scroll-up" aria-label="Scroll Up"><span class="fa fa-angle-up" aria-hidden="true"></span></a>
			</p>

			<p class="copyright">
				&copy; <?php //echo date('Y'); ?> <?php echo $copyright; ?>
			</p>
			<div class="table-relative-left" id="table-relative-left">
				<a href="#" class="sp-scroll-up-table" aria-label="Scroll Up" style="display: none;"><i class="fa fa-list-ul" aria-hidden="true"></i></a>
			</div>
		</div>
		</div>
	</footer>

	<?php endif; ?>
	<jdoc:include type="modules" name="debug" style="none" />
	<div id="overlay"></div>



	<?php
	//landingpage
	if($_REQUEST['Itemid'] == FOUNDER_STORY){ ?>
		<script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script>

		<script id="script_event_data" type="text/javascript">
			(function() {
				var run = function() {
					if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
						setTimeout(run, 100);
						return;
					}
					window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
					window.LadiPageScript.runtime.ladipage_id = '5ee0bc9a4005525abf796aaf';
					window.LadiPageScript.runtime.isMobileOnly = false;
					window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["xuhuongkinhdoanh.xyz"];
					window.LadiPageScript.runtime.DOMAIN_FREE = ["pagedemo.me", "demopage.me", "pro5.me", "procv.to", "ladi.me"];
					window.LadiPageScript.runtime.bodyFontSize = 12;
					window.LadiPageScript.runtime.time_zone = 7;
					window.LadiPageScript.runtime.eventData =
						"%7B%22BUTTON32%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22BUTTON118%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22GROUP123%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22BUTTON151%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22VIDEO163%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FeV8h9ROf1Ec%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO169%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F8IPFhDMY2f4%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO175%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FW8XoQSGalkw%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22BUTTON177%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION220%22%7D%7D%2C%22FORM226%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM229%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM230%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM231%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22BUTTON287%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION290%22%7D%7D%2C%22GROUP302%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22FORM_ITEM327%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%7D";
					window.LadiPageScript.run(true);
					window.LadiPageScript.runEventScroll();
				};
				run();
			})();
		</script>

	<?php } ?>


	<?php if($_REQUEST['Itemid'] == TECH_INSURACNE){ ?>
		<script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script>

		<script id="script_lazyload" type="text/javascript">
			(function() {
				var list_element_lazyload = document.querySelectorAll(
					'.ladi-section-background, .ladi-image-background, .ladi-button-background, .ladi-headline, .ladi-video-background, .ladi-countdown-background, .ladi-box, .ladi-frame, .ladi-form-item-background, .ladi-gallery-view-item, .ladi-gallery-control-item, .ladi-spin-lucky-screen, .ladi-spin-lucky-start, .ladi-list-paragraph ul li'
					);
				var style_lazyload = document.getElementById('style_lazyload');
				for (var i = 0; i < list_element_lazyload.length; i++) {
					var rect = list_element_lazyload[i].getBoundingClientRect();
					if (rect.x == "undefined" || rect.x == undefined || rect.y == "undefined" || rect.y == undefined) {
						rect.x = rect.left;
						rect.y = rect.top;
					}
					var offset_top = rect.y + window.scrollY;
					if (offset_top >= window.scrollY + window.innerHeight || window.scrollY >= offset_top + list_element_lazyload[i].offsetHeight) {
						list_element_lazyload[i].classList.add('ladi-lazyload');
					}
				}
				style_lazyload.parentElement.removeChild(style_lazyload);
				var currentScrollY = window.scrollY;
				var stopLazyload = function(event) {
					if (event.type == "scroll" && window.scrollY == currentScrollY) {
						currentScrollY = -1;
						return;
					}
					window.removeEventListener('scroll', stopLazyload);
					list_element_lazyload = document.getElementsByClassName('ladi-lazyload');
					while (list_element_lazyload.length > 0) {
						list_element_lazyload[0].classList.remove('ladi-lazyload');
					}
				};
				window.addEventListener('scroll', stopLazyload);
			})();
		</script>
		<script id="script_event_data" type="text/javascript">
			(function() {
				var run = function() {
					if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
						setTimeout(run, 100);
						return;
					}
					window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
					window.LadiPageScript.runtime.ladipage_id = '5eb11e84c310210e1b17f103';
					window.LadiPageScript.runtime.isMobileOnly = false;
					window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
					window.LadiPageScript.runtime.DOMAIN_FREE = ["pagedemo.me", "demopage.me", "ladi.me", "pro5.me", "procv.to"];
					window.LadiPageScript.runtime.bodyFontSize = 12;
					window.LadiPageScript.runtime.time_zone = 7;
					window.LadiPageScript.runtime.eventData =
						"%7B%22BUTTON199%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP203%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22FRAME204%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME209%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME214%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME219%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON228%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP266%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP272%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP278%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP284%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON297%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP299%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME341%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME347%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME353%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME359%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP479%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP484%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FORM_ITEM466%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM465%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM464%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22BUTTON462%22%3A%7B%22type%22%3A%22button%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FORM461%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM662%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%2C%22GROUP683%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO685%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FeV8h9ROf1Ec%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO686%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F8IPFhDMY2f4%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22VIDEO688%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FW8XoQSGalkw%22%2C%22option.video_type%22%3A%22youtube%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22FORM696%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Atrue%2C%22option.form_auto_complete%22%3Atrue%7D%2C%22FORM_ITEM699%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM700%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM701%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM724%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A4%7D%2C%22VIDEO726%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FqDEQrpiQFtI%22%2C%22option.video_type%22%3A%22youtube%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%2C%22mobile.option.video_autoplay%22%3Afalse%7D%2C%22BUTTON727%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22BOX190%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BOX191%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP730%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%7D";
					window.LadiPageScript.run(true);
					window.LadiPageScript.runEventScroll();
				};
				run();
			})();
		</script>

	<?php } ?>

	<?php
	if ($_REQUEST['test'] == 1) {
		print_r($_REQUEST);
	}
	if ($_REQUEST['Itemid'] == AGENT || $_REQUEST['Itemid'] == FOUR_ZERO_INSURACNE) { ?>

		<style>
		<?php
		if ($_REQUEST['Itemid'] == AGENT) {  ?>
			#SECTION625{
				display: none;
			}
			#footer-landingpage #SECTION625{
				display: block!important;
			}
		<?php } ?>
		</style>

	  <script type="text/javascript">
	    window.ladi_viewport = function() {
	      var screen_width = window.ladi_screen_width || window.screen.width;
	      var width = window.outerWidth > 0 ? window.outerWidth : screen_width;
	      var widthDevice = width;
	      var is_desktop = width >= 768;
	      var content = "";
	      if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
	        window.ladi_is_desktop = is_desktop;
	      }
	      if (!is_desktop) {
	        widthDevice = 420;
	      } else {
	        widthDevice = 960;
	      }
	      content = "width=" + widthDevice + ", user-scalable=no";
	      var scale = 1;
	      if (!is_desktop && widthDevice != screen_width && screen_width > 0) {
	        scale = screen_width / widthDevice;
	      }
	      if (scale != 1) {
	        content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
	      }
	      var docViewport = document.getElementById("viewport");
	      if (!docViewport) {
	        docViewport = document.createElement("meta");
	        docViewport.setAttribute("id", "viewport");
	        docViewport.setAttribute("name", "viewport");
	        document.head.appendChild(docViewport);
	      }
	      docViewport.setAttribute("content", content);
	    };
	    window.ladi_viewport();
	    window.ladi_fbq_data = [];
	    window.ladi_fbq = function(track_name, conversion_name, data, event_data) {
	      window.ladi_fbq_data.push([track_name, conversion_name, data, event_data]);
	    };
	  </script>

		<!-- <script id="script_viewport" type="text/javascript">
			window.ladi_viewport = function() {
				var width = window.outerWidth > 0 ? window.outerWidth : window.screen.width;
				var widthDevice = width;
				var is_desktop = width >= 768;
				var content = "";
				if (typeof window.ladi_is_desktop == "undefined" || window.ladi_is_desktop == undefined) {
					window.ladi_is_desktop = is_desktop;
				}
				if (!is_desktop) {
					widthDevice = 420;
				} else {
					widthDevice = 960;
				}
				content = "width=" + widthDevice + ", user-scalable=no";
				var scale = 1;
				if (!is_desktop && widthDevice != window.screen.width && window.screen.width > 0) {
					scale = window.screen.width / widthDevice;
				}
				if (scale != 1) {
					content += ", initial-scale=" + scale + ", minimum-scale=" + scale + ", maximum-scale=" + scale;
				}
				var docViewport = document.getElementById("viewport");
				if (!docViewport) {
					docViewport = document.createElement("meta");
					docViewport.setAttribute("id", "viewport");
					docViewport.setAttribute("name", "viewport");
					document.head.appendChild(docViewport);
				}
				docViewport.setAttribute("content", content);
			};
			window.ladi_viewport();
		</script> -->

		<script id="script_event_data" type="text/javascript">
	    (function() {
	      var run = function() {
	        if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
	          setTimeout(run, 100);
	          return;
	        }
	        window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
	        window.LadiPageScript.runtime.ladipage_id = '61e7ee18c71f2700381ed90d';
	        window.LadiPageScript.runtime.publish_platform = 'LADIPAGEDNS';
	        window.LadiPageScript.runtime.is_mobile_only = false;
	        window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
	        window.LadiPageScript.runtime.DOMAIN_FREE = [];
	        window.LadiPageScript.runtime.bodyFontSize = 12;
	        window.LadiPageScript.runtime.store_id = "5e4cf269a9c6692c79a6477c";
	        window.LadiPageScript.runtime.time_zone = 7;
	        window.LadiPageScript.runtime.currency = "VND";
	        window.LadiPageScript.runtime.convert_replace_str = true;
	        window.LadiPageScript.runtime.desktop_width = 960;
	        window.LadiPageScript.runtime.mobile_width = 420;
	        window.LadiPageScript.runtime.eventData =
	          "%7B%22SECTION_POPUP%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22SECTION369%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22IMAGE375%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE377%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE380%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE382%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE385%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP386%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE387%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE390%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP391%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE392%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE394%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE395%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION396%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE397%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE398%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE399%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceIn%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceIn%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22SHAPE400%22%3A%7B%22type%22%3A%22shape%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22GROUP401%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE402%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION1701%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE403%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION404%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22HEADLINE410%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH411%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE414%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH415%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE421%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH422%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE428%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH429%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE434%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE435%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION436%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22PARAGRAPH439%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE440%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH442%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE443%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH445%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE446%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE447%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE448%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE449%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION450%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE452%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22VIDEO454%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FjuUR1SEF-Ak%22%2C%22option.video_type%22%3A%22youtube%22%7D%2C%22VIDEO456%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FTFRJGdhvis0%22%2C%22option.video_type%22%3A%22youtube%22%7D%2C%22VIDEO459%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FynsHKzm5PH0%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO462%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDt1sMA6gXWo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22HEADLINE464%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE465%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE466%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE467%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE468%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE469%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH470%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH471%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH472%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH473%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION474%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22VIDEO477%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDS-7UDHY7xo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%2C%22desktop.option.video_autoplay%22%3Atrue%2C%22mobile.option.video_autoplay%22%3Atrue%7D%2C%22HEADLINE479%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE480%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE481%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP482%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE483%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE484%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION485%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22IMAGE486%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP488%22%3A%7B%22type%22%3A%22group%22%2C%22mobile.option.auto_scroll%22%3Atrue%7D%2C%22HEADLINE490%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH491%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE496%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH497%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE502%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH503%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE508%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH509%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE513%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE514%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22GROUP515%22%3A%7B%22type%22%3A%22group%22%2C%22option.data_event%22%3A%5B%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION550%22%2C%22action_type%22%3A%22action%22%7D%5D%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%220s%22%7D%2C%22IMAGE516%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE517%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION518%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22gradient%22%2C%22mobile.option.background-style%22%3A%22gradient%22%7D%2C%22HEADLINE519%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInLeft%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInLeft%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE520%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE521%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE522%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE523%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH528%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH533%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH538%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH543%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH548%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION550%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22HEADLINE552%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInLeft%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInLeft%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22SECTION553%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22HEADLINE558%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE559%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE563%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE564%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE569%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22COUNTDOWN573%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22countdown%22%2C%22option.countdown_minute%22%3A27%7D%2C%22COUNTDOWN_ITEM574%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM575%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM576%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM577%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%2C%22HEADLINE578%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE579%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE580%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE581%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM582%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%225eb50e7381e7d47b21eef07a%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.thankyou_value%22%3A%22https%3A%2F%2Finsurance.bcavietnam.com%2Fthank-you-page%22%2C%22option.form_captcha%22%3Atrue%2C%22option.is_form_coupon%22%3Afalse%2C%22option.is_form_login%22%3Afalse%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON583%22%3A%7B%22type%22%3A%22button%22%2C%22option.is_submit_form%22%3Afalse%2C%22option.is_buy_now%22%3Afalse%2C%22option.data_setting.type_dataset%22%3A%22COLLECTION%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22pulse%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22BUTTON_TEXT583%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM585%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM586%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM587%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION588%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22HEADLINE591%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE594%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE597%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE600%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH603%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22PARAGRAPH604%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22bounceInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE605%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION606%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22GALLERY609%22%3A%7B%22type%22%3A%22gallery%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.option.gallery_control.autoplay%22%3Atrue%2C%22desktop.option.gallery_control.autoplay_time%22%3A4%2C%22mobile.option.gallery_control.autoplay%22%3Atrue%2C%22mobile.option.gallery_control.autoplay_time%22%3A4%7D%2C%22HEADLINE610%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE612%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE615%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE619%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM620%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22select%22%2C%22option.input_tabindex%22%3A4%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM618%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22select%22%2C%22option.input_tabindex%22%3A5%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE617%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22NOTIFY368%22%3A%7B%22type%22%3A%22notify%22%2C%22option.data_setting.type%22%3A%22default%22%2C%22option.sheet_id%22%3A%221WXZsybVDWLghgDB7D7AbROmuwiHKYk_feIeDBVDZkKQ%22%2C%22option.time_show%22%3A0%2C%22option.time_delay%22%3A10%2C%22desktop.option.position%22%3A%22bottom_center%22%2C%22mobile.option.position%22%3A%22bottom_center%22%7D%2C%22IMAGE366%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE364%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE363%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE361%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE360%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE359%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE358%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE355%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE354%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE353%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInDown%22%2C%22desktop.style.animation-delay%22%3A%220s%22%2C%22mobile.style.animation-name%22%3A%22fadeInDown%22%2C%22mobile.style.animation-delay%22%3A%220s%22%7D%2C%22HEADLINE351%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE350%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE349%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM348%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_default_value%22%3A%221%22%2C%22option.input_tabindex%22%3A4%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON_TEXT346%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22BUTTON346%22%3A%7B%22type%22%3A%22button%22%2C%22option.is_submit_form%22%3Afalse%2C%22option.is_buy_now%22%3Afalse%2C%22option.data_setting.type_dataset%22%3A%22COLLECTION%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM345%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM344%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM_ITEM343%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22FORM342%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%225eb50e7381e7d47b21eef07a%22%2C%22option.form_send_ladipage%22%3Atrue%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.thankyou_value%22%3A%22https%3A%2F%2Finsurance.bcavietnam.com%2Fthank-you-page%22%2C%22option.form_captcha%22%3Atrue%2C%22option.is_form_coupon%22%3Afalse%2C%22option.is_form_login%22%3Afalse%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE339%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22IMAGE338%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE337%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22pulse%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22pulse%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22HEADLINE336%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE331%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE330%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE328%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22updated_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE323%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION322%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22image%22%2C%22mobile.option.background-style%22%3A%22image%22%7D%2C%22IMAGE621%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE623%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE636%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH637%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE644%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE645%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE648%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH649%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE656%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE657%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE669%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE670%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE671%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION664%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22IMAGE676%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH677%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE684%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE685%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE687%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH689%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE690%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH691%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH692%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22IMAGE693%22%3A%7B%22type%22%3A%22image%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22HEADLINE695%22%3A%7B%22type%22%3A%22headline%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22SECTION696%22%3A%7B%22type%22%3A%22section%22%2C%22desktop.option.background-style%22%3A%22solid%22%2C%22mobile.option.background-style%22%3A%22solid%22%7D%2C%22PARAGRAPH705%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInUp%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22fadeInUp%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22PARAGRAPH720%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%7D%2C%22PARAGRAPH722%22%3A%7B%22type%22%3A%22paragraph%22%2C%22option.data_setting.sort_name%22%3A%22created_at%22%2C%22option.data_setting.sort_by%22%3A%22desc%22%2C%22desktop.style.animation-name%22%3A%22fadeInUp%22%2C%22desktop.style.animation-delay%22%3A%220.2s%22%2C%22mobile.style.animation-name%22%3A%22fadeInUp%22%2C%22mobile.style.animation-delay%22%3A%220.2s%22%7D%2C%22VIDEO728%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fwww.youtube.com%2Fwatch%3Fv%3Deduh6m_O9Lg%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%7D";
	        window.LadiPageScript.run(true);
	        window.LadiPageScript.runEventScroll();
	      };
	      run();
	    })();
	  </script>

		<!-- <script id="script_event_data" type="text/javascript">
		  (function() {
		    var run = function() {
		      if (typeof window.LadiPageScript == "undefined" || window.LadiPageScript == undefined || typeof window.ladi == "undefined" || window.ladi == undefined) {
		        setTimeout(run, 100);
		        return;
		      }
		      window.LadiPageApp = window.LadiPageApp || new window.LadiPageAppV2();
		      window.LadiPageScript.runtime.ladipage_id = '5eb11e84c310210e1b17f103';
		      window.LadiPageScript.runtime.isMobileOnly = false;
		      window.LadiPageScript.runtime.DOMAIN_SET_COOKIE = ["bcavietnam.com"];
		      window.LadiPageScript.runtime.DOMAIN_FREE = [];
		      window.LadiPageScript.runtime.bodyFontSize = 12;
		      window.LadiPageScript.runtime.store_id = "5e4cf269a9c6692c79a6477c";
		      window.LadiPageScript.runtime.time_zone = 7;
		      window.LadiPageScript.runtime.currency = "VND";
		       window.LadiPageScript.runtime.eventData =
		         "%7B%22BUTTON199%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22FRAME204%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME209%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME214%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME219%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22bounceInUp%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInUp%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON228%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP266%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP272%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP278%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP284%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON297%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22GROUP299%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME341%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME347%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME353%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FRAME359%22%3A%7B%22type%22%3A%22frame%22%2C%22desktop.style.animation-name%22%3A%22fadeIn%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22fadeIn%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP479%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP484%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22FORM_ITEM466%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22FORM_ITEM465%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM464%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22BUTTON462%22%3A%7B%22type%22%3A%22button%22%2C%22desktop.style.animation-name%22%3A%22shake%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22shake%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22FORM461%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Afalse%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Afalse%2C%22option.form_auto_complete%22%3Afalse%7D%2C%22GROUP683%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO685%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2Feduh6m_O9Lg%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO686%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2Fd9Qs8BJhvu8%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22VIDEO688%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F5147exHSpWM%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22FORM696%22%3A%7B%22type%22%3A%22form%22%2C%22option.form_config_id%22%3A%22nganly%22%2C%22option.form_send_ladipage%22%3Afalse%2C%22option.thankyou_type%22%3A%22url%22%2C%22option.form_auto_funnel%22%3Afalse%2C%22option.form_auto_complete%22%3Afalse%7D%2C%22FORM_ITEM699%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22text%22%2C%22option.input_tabindex%22%3A1%7D%2C%22FORM_ITEM700%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22email%22%2C%22option.input_tabindex%22%3A2%7D%2C%22FORM_ITEM701%22%3A%7B%22type%22%3A%22form_item%22%2C%22option.input_type%22%3A%22tel%22%2C%22option.input_tabindex%22%3A3%7D%2C%22VIDEO726%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2F1uqsSlYZ7os%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BUTTON727%22%3A%7B%22type%22%3A%22button%22%2C%22option.data_action%22%3A%7B%22type%22%3A%22section%22%2C%22action%22%3A%22SECTION439%22%7D%7D%2C%22BOX190%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22BOX191%22%3A%7B%22type%22%3A%22box%22%2C%22desktop.style.animation-name%22%3A%22bounceInRight%22%2C%22desktop.style.animation-delay%22%3A%221s%22%2C%22mobile.style.animation-name%22%3A%22bounceInRight%22%2C%22mobile.style.animation-delay%22%3A%221s%22%7D%2C%22GROUP730%22%3A%7B%22type%22%3A%22group%22%2C%22desktop.style.animation-name%22%3A%22bounceInDown%22%2C%22desktop.style.animation-delay%22%3A%221s%22%7D%2C%22VIDEO734%22%3A%7B%22type%22%3A%22video%22%2C%22option.video_value%22%3A%22https%3A%2F%2Fyoutu.be%2FDS-7UDHY7xo%22%2C%22option.video_type%22%3A%22youtube%22%2C%22option.video_control%22%3Atrue%7D%2C%22COUNTDOWN743%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22daily%22%2C%22option.countdown_daily_start%22%3A%2200%3A00%3A00%22%2C%22option.countdown_daily_end%22%3A%2223%3A59%3A59%22%7D%2C%22COUNTDOWN_ITEM744%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM745%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM746%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM747%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%2C%22COUNTDOWN748%22%3A%7B%22type%22%3A%22countdown%22%2C%22option.countdown_type%22%3A%22daily%22%2C%22option.countdown_daily_start%22%3A%2200%3A00%3A00%22%2C%22option.countdown_daily_end%22%3A%2223%3A59%3A59%22%7D%2C%22COUNTDOWN_ITEM749%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22day%22%7D%2C%22COUNTDOWN_ITEM750%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22hour%22%7D%2C%22COUNTDOWN_ITEM751%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22minute%22%7D%2C%22COUNTDOWN_ITEM752%22%3A%7B%22type%22%3A%22countdown_item%22%2C%22option.countdown_item_type%22%3A%22seconds%22%7D%7D";
		      window.LadiPageScript.run(true);
		      window.LadiPageScript.runEventScroll();
		    };
		    run();
		  })();
		</script> -->

	<?php
		}
	?>

	<script>
	jQuery(document).ready(function(){
	    <?php if($_REQUEST['Itemid'] == 295 ||
	    $_REQUEST['Itemid'] == 296 ||
	    $_REQUEST['Itemid'] == 297 ||
	    $_REQUEST['Itemid'] == 298
	    ){
	    ?>
	    jQuery('.item-294.plus').trigger('click');
	    <?php
	    }
	    ?>

			<?php
			// fix Social for App mobile
			if($_REQUEST['is_app'] == 1){
			?>
	    jQuery('a').each(function() {
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					//this.href + '?is_app=1'
					jQuery(this).attr('href', res_end);
				}
	    });
			var logout_form_href = jQuery('#jomsocial-logout-form').attr('action');
			jQuery('#jomsocial-logout-form').attr('action',logout_form_href+'?is_app=1');
			jQuery('.left-logo-search-account a').attr('href','<?php echo $this->baseurl; ?>/mxh.html?is_app=1');

			<?php
	    }
	    ?>
	});
	</script>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">

        <iframe id="iframeYoutube" width="560" height="315"  src="https://www.youtube.com/embed/5147exHSpWM" frameborder="0" allowfullscreen></iframe>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
      </div>
    </div>
  </div>
</div>
<script>
jQuery(document).ready(function(){
  jQuery("#myModal").on("hidden.bs.modal",function(){
    jQuery("#iframeYoutube").attr("src","#");
  })
	jQuery(".item-335 a").click(function(){
  	jQuery("#jomsocial-logout-form").submit();
		<?php if($is_social == 0){ ?>
		window.location.href = "<?php echo $this->baseurl; ?>/mxh.html";
		<?php } ?>
	});

	jQuery("#noti-general-a").click(function(){
		setTimeout(function() {
			jQuery('#noti-general-li ul a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});



	jQuery("#noti-friendrequest-a").click(function(){
		setTimeout(function() {
			jQuery('#noti-friendrequest-li ul a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});


	jQuery("#noti-mobile-friendrequest-a").click(function(){
		setTimeout(function() {
			jQuery('.mfp-wrap  a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});


	jQuery("#noti-mobile-general-a").click(function(){
		setTimeout(function() {
			jQuery('.mfp-wrap a').each(function() {
				//alert(this.href);
				if(this.href.substring(0,10) != 'javascript'){
					var href_link = this.href;
					var res = href_link.split("#");
				  var res_end = '';
				  if(res[1] != '' && res[1]!= undefined){
				  	 res_end = '?is_app=1#'+res[1];
				  }else{
				  	res_end = res[0]+'?is_app=1';
				  }
					jQuery(this).attr('href', res_end);
				}
	    });
 		}, 1000);
	});
})

function changeVideo(vId){
  var iframe=document.getElementById("iframeYoutube");
  iframe.src="https://www.youtube.com/embed/"+vId;

  jQuery("#myModal").modal("show");
}
</script>

<?php if($_REQUEST['option'] != 'com_community' && $_REQUEST['Itemid'] != TECH_INSURACNE && $_REQUEST['Itemid'] != FOUNDER_STORY && $_REQUEST['Itemid'] != FOUR_ZERO_INSURACNE && $_REQUEST['Itemid'] != AGENT){ ?>
				<!-- <div class="fb-customerchat" attribution=setup_tool page_id="362028714601667" logged_in_greeting="Xin chào! Bạn cần giúp đỡ?" logged_out_greeting="Xin chào! Bạn cần giúp đỡ?"></div> -->
				<!-- <script id='autoAdsMaxLead-widget-script' src='https://cdn.autoads.asia/scripts/autoads-maxlead-widget.js?business_id=C5905BB564BF40B29E8D19BC4EE69188' type='text/javascript' charset='UTF-8' async></script> -->

<?php } ?>

<!-- Global site tag (gtag.js) - Google Analytics --> <script async src="https://www.googletagmanager.com/gtag/js?id=UA-170614096-1"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-170614096-1'); </script>
<?php if($_REQUEST['Itemid'] != AGENT): ?>
	<!-- Messenger Plugin chat Code -->
    <div id="fb-root"></div>

    <!-- Your Plugin chat code -->
    <div id="fb-customer-chat" class="fb-customerchat">
    </div>

    <script>
      var chatbox = document.getElementById('fb-customer-chat');
      chatbox.setAttribute("page_id", "102967654972383");
      chatbox.setAttribute("attribution", "biz_inbox");

      window.fbAsyncInit = function() {
        FB.init({
          xfbml            : true,
          version          : 'v11.0'
        });
      };

      (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk'));
    </script>
<?php endif; ?>
<style>
.box-service-2 .col-image1{
    clip-path: polygon(0 0, calc(100% - 430px) 0, 100% 100%, 0 100%);
}
.box-service-2 .bg-info1{
	height: calc(100% - 150px);
}
.slide-categories .banner-1{
  height: calc(100vw * (560/(1280 + 0)))!important;
}



<?php if($is_home_page){ ?>
.loop-line{
  height: 13px;
  width: 100%;
  z-index: 99;
	margin-top: -95px;

}
.loop-line::after{
  content: "";
  display: block;
  bottom: 0;
  left: 0;
  height: 8vw;
  width: 100%;
  background: white;
  border-radius: calc(12% + 70px) 0 0 0;
  border-top: solid 8px #038A96;
  box-shadow: inset 0 5px 0 #F58C29;
}
<?php } ?>

<?php if(!$is_home_page){ ?>
.loop-line{
  height: 13px;
  width: 100%;
  z-index: 99;
	margin-top: -113px;

}
.loop-line::after{
  content: "";
  display: block;
  bottom: 0;
  left: 0;
  height: 8vw;
  width: 100%;
  background: white;
  border-radius: calc(15% + 100px) 0 0 0;
  border-top: solid 8px #038A96;
  box-shadow: inset 0 5px 0 #F58C29;
}
<?php } ?>


@media (max-width: 960px) {
	.box-service-2 .bg-info1{
		height: calc(100% - 0px);
	}
	.box-service-2 .col-image1{
	    clip-path: polygon(0 0, calc(100% - 0px) 0, 100% 100%, 0 100%);
	}
	.slide-categories .banner-1{
	  height: calc(100vw * (890/(1280 + 50)))!important;
	}
}


</style>
</body>
</html>
