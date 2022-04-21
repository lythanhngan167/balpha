<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_joomprofile
 *
 * @copyright   Copyright (C) 2013 function90.com
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>

<?php if(empty($data->searchFields)):?>
	<div class="row-fluid">
		<div class="muted text-center">
			<i class="fa fa-ban fa-5x"></i>
			<h1><?php echo JText::_('COM_JOOMPROFILE_SEARCH_NOT_ALLWOED');?></h1>
		</div>
	</div>
<?php else:?>

<?php if($data->total == 0):?>
	<div class="row-fluid">
		<div class="muted text-center">
			<i class="fa fa-search fa-5x"></i>
			<?php if(!empty($searchConditions)) :?>
				<h1><?php echo JText::_('COM_JOOMPROFILE_SEARCH_NO_RECORD_FOUND');?></h1>
				<h3><?php echo JText::_('COM_JOOMPROFILE_SEARCH_AGAIN');?></h3>
			<?php else:?>
				<h1><?php echo JText::_('COM_JOOMPROFILE_SEARCH_FIND_USER');?></h1>
				<h5><?php echo JText::_('COM_JOOMPROFILE_SEARCH_INSTRUCTION');?></h5>
				<?php if(count($data->searchFields) > 1):?>
					<h5><?php echo JText::_('COM_JOOMPROFILE_SEARCH_MULTIPLE_SELECTION_ALLOWED');?></h5>
				<?php endif;?>
			<?php endif;?>
		</div>
	</div>
<?php else:?>
	<div class="row-fluid jps-user-row-container">
	<?php
	$counter = 1;
	foreach($data->users as $user):
		$obj = $this->app->getObject('user', 'joomprofileprofile', $user->id, array(), $user);
		?>
		<div class="row mx-auto jps-user">
			<div class="col-lg-3 row-fluid-avt text-center col-6">
				<div class="avt-img text-center">
					<div class="jps-user-image text-center">
						<a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$user->id);?>">
						<img class="img-circle" alt="<?php echo $user->username;?>" src="<?php echo $obj->getAvatar(100);?>">
						</a>
					</div>
				</div>
				<div class="jps-user-name text-center">
					<a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$user->id);?>">
						<?php echo $user->name;?>
					</a>
					<?php $app 	 = JoomprofileExtension::get('profile');?>
					<?php $userLib = $app->getObject('user', 'JoomprofileProfile', $user->id);?>
					<?php list($userFieldValues, $privacy) = $userLib->getFieldValues();?>
					<?php foreach($data->serachFieldsMapping as $serachFieldMapping): ?>
						<?php if(intval($serachFieldMapping->showOnProfile)): ?>
							<?php
								$field = $data->searchFields[$serachFieldMapping->field_id];
								$fieldObj =$field->toObject();
								$field_instance = JoomprofileLibField::get($fieldObj->type);
								$fieldValue = $field_instance->getMiniProfileViewHtml($fieldObj, isset($userFieldValues[$fieldObj->id]) ? $userFieldValues[$fieldObj->id] : '', $user->id)
							?>
							<?php if ($fieldObj->id == 2) { ?>
								<div class="review-start text-center">
									<div style="display: inline; position: relative; top: 1px;font-weight: 400;">
										<div class="number-star-user-wapper" id="number-star-user-wapper-<?php echo $user->id; ?>">
											<div style="display:none;" class="number-star-user" id="number-star-user-<?php echo $user->id; ?>">

												<?php
												$u = JFactory::getUser($user->id);
												echo $u->star;
												?>
											</div>
											<i class="fa fa-star-o star1" id="star-1" aria-hidden="true"></i>
											<i class="fa fa-star-o star2" id="star-2" aria-hidden="true"></i>
											<i class="fa fa-star-o star3" id="star-3" aria-hidden="true"></i>
											<i class="fa fa-star-o star4" id="star-4" aria-hidden="true"></i>
											<i class="fa fa-star-o star5" id="star-5" aria-hidden="true"></i>
											</div>
										</div>
										<script>
										jQuery(document).ready(function(){
											var number_star_user = jQuery('#number-star-user-<?php echo $user->id; ?>').html();
											if(number_star_user > 0){
												var i;
												for (i = 1; i <= number_star_user; i++) {
													//jQuery('#number-star-user-wapper-<?php echo $user->id; ?> #star-'+i).addClass('active-star');
													jQuery('#number-star-user-wapper-<?php echo $user->id; ?> #star-'+i).addClass('fa-star');
													jQuery('#number-star-user-wapper-<?php echo $user->id; ?> #star-'+i).removeClass('fa-star-o');
												}
											}
										});
										</script>
								</div>

						<?php	} ?>
						<?php if ($fieldObj->id == 6){
							echo"<span class = 'type-of-agent'>".$fieldValue."</span>";
						}
						?>
						<?php endif;?>
					<?php endforeach;?>
				</div>


			</div>
			<div class="col-lg-6 row-user-details">
				<div class="">
					<div class="jps-user-details">
						<p class="jps-user-add-info">
							<?php $app 	 = JoomprofileExtension::get('profile');?>
							<?php $userLib = $app->getObject('user', 'JoomprofileProfile', $user->id);?>
							<?php list($userFieldValues, $privacy) = $userLib->getFieldValues();?>
							<?php foreach($data->serachFieldsMapping as $serachFieldMapping): ?>
								<?php if(intval($serachFieldMapping->showOnProfile)): ?>
									<?php
										$field = $data->searchFields[$serachFieldMapping->field_id];
										$fieldObj =$field->toObject();
										$field_instance = JoomprofileLibField::get($fieldObj->type);
										$fieldValue = $field_instance->getMiniProfileViewHtml($fieldObj, isset($userFieldValues[$fieldObj->id]) ? $userFieldValues[$fieldObj->id] : '', $user->id)
									?>
									<?php
									// echo "<pre>";
									// print_r($fieldObj->id);
									// echo "</pre>";
									 if(trim($fieldValue) != ''):?>
										<div class="jps-mini-profile" id="profile-field-search-<?php echo $fieldObj->id; ?>">
											<div class="jps-mini-profile-title col-6">
												<?php echo JText::_($fieldObj->title);?>
											</div>
											<div class="jps-mini-profile-value col-6">
												<?php
													echo $fieldValue;
												?>

											</div>
										</div>
									<?php endif;?>
								<?php endif;?>
							<?php endforeach;?>
							<div class="jps-mini-profile">
								<div class="jps-mini-profile-title col-6">
									<?php echo "Ngày mở tài khoản: "?>
								</div>
								<div class="jps-mini-profile-value col-6">
									<?php
									echo date("d-m-Y", strtotime($user->registerDate));
									?>
								</div>
							</div>
						</p>
					</div>
				</div>
			</div>
            <div class="col-lg-3 row-user-contact d-flex justify-content-center flex-column">
                <a style="text-decoration: none" class="btn-contact-user ml-auto" type="button" name="button" href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$user->id);?>">Liên hệ</a>
            </div>
		</div>

		<!-- <div class="jps-user-details span6">
			<div class="row-fluid">
				<div class="">
					<div class="">
						<button href="#">Liên hệ</button>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="">
					<div class="jps-user-details">
			        	<p class="jps-user-name text-center">
							<a href="<?php echo JRoute::_('index.php?option=com_joomprofile&view=profile&task=user.display&id='.$user->id);?>">
								<?php echo $user->name;?>
							</a>
						</p>
						<p class="jps-user-add-info">
							<?php $app 	 = JoomprofileExtension::get('profile');?>
							<?php $userLib = $app->getObject('user', 'JoomprofileProfile', $user->id);?>
							<?php list($userFieldValues, $privacy) = $userLib->getFieldValues();?>
							<?php foreach($data->serachFieldsMapping as $serachFieldMapping): ?>
								<?php if(intval($serachFieldMapping->showOnProfile)): ?>
									<?php
										$field = $data->searchFields[$serachFieldMapping->field_id];
										$fieldObj =$field->toObject();
										$field_instance = JoomprofileLibField::get($fieldObj->type);
										$fieldValue = $field_instance->getMiniProfileViewHtml($fieldObj, isset($userFieldValues[$fieldObj->id]) ? $userFieldValues[$fieldObj->id] : '', $user->id)
									?>
									<?php if(trim($fieldValue) != ''):?>
										<div class="jps-mini-profile">
											<div class="jps-mini-profile-title">
												<?php echo JText::_($fieldObj->title);?>
											</div>
											<div class="jps-mini-profile-value">
												<?php
													echo $fieldValue;
												?>
											</div>
										</div>
									<?php endif;?>
								<?php endif;?>
							<?php endforeach;?>
						</p>
					</div>
				</div>
			</div>
		</div> -->
		<?php if($counter % 2 ==0):?>
			</div><div class="row-fluid jps-user-row-container">
		<?php endif;?>
		<?php $counter++;?>
	<?php endforeach;?>
	</div>
<?php endif; ?>

<?php endif;?>
<?php
