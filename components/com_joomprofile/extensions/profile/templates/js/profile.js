if(typeof(joomprofile.profile) == 'undefined'){
	joomprofile.profile = {};
}
(function($){
	joomprofile.profile.field = {};
	joomprofile.profile.field.getParameters = function(field){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		if(field == ''){
			$('#joomprofile-field-params').html('');
			return true;
		}

		var id = $('#com_joomprofile_id').val();
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=field.config&field="+field+"&id="+id+"&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(typeof(data.success) != 'undefined' && data.success){
					$('#joomprofile-field-params').html(data.html);
					$(".hasPopover").popover({"html": true,"trigger": "hover focus","container": "body"});
                    $('#joomprofile-field-params select.chosen').chosen();
				joomprofile.radio.init();
				}
				else{
					alert('Error in loading configuration of field type : '+field);
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};
	joomprofile.profile.getfieldGroupEditHtml = function(user_id, fieldgroup_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&task=user.editfieldgrouphtml&id='+user_id+'&fieldgroup_id='+fieldgroup_id+'&format=json&_='+(new Date()).getTime();
		isJoomprofileRequesting	 = true;

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
		}).done(function(data) {
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				$('#joomprofile-fieldgroup-'+fieldgroup_id).fadeOut(500, function() {
			        $(this).html(data.html).fadeIn(500);
			        $('.hasTooltip').tooltip();
			        // apply validation
			        $(this).find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
			    });
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	joomprofile.profile.getfieldGroupViewHtml = function(user_id, fieldgroup_id){
		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&task=user.viewfieldgrouphtml&id='+user_id+'&fieldgroup_id='+fieldgroup_id+'&format=json&_='+(new Date()).getTime();
		isJoomprofileRequesting	 = true;

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
		}).done(function(data) {
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				$('#f90pro').fadeOut(500, function() {
			        $(this).html(data.html).fadeIn(500);
			        // apply validation
			        $(this).find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
			    });
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	// joomprofile.profile.saveisconsultinger = function(user_id, is_consultinger){
	// 	if(f90_ajax_req_in_progress == true){
	// 		return false;
	// 	}
	// 	var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&task=user.saveisconsultinger&id='+user_id+'&is_consultinger='+is_consultinger+'&format=json&_='+(new Date()).getTime();
	// 	$.ajax({
	// 		type: 'POST',
	// 		async: false,
	// 		url: url,
	// 		iframe: true,
	// 		processData: false
	// 	}).done(function(data) {
	// 		//data = joomprofile.parseResponse(data);
	// 		if(data == '1'){
	// 			alert("Cập nhật thành công!");
	// 		}else{
	// 			alert("Cập nhật thất bại, vui lòng thử lại.");
	// 		}
	// 	});
	// };


	joomprofile.profile.savefieldgroup = function(user_id, fieldgroup_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var $form 	= $('#joomprofile-fieldgroup-'+fieldgroup_id+' form');
		if ($form.find("input:visible,textarea:visible,select:visible").jqBootstrapValidation("hasErrors")) {
			// @TODO : show popup or smething else
			$form.submit();
			return false;
		}

		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&task=user.savefieldgroup&id='+user_id+'&fieldgroup_id='+fieldgroup_id+'&format=json&_='+(new Date()).getTime();
		var data 	= $form.serialize();
		if($(":file", $form).length){
			url += "&transport=1";
			data 	= $form.serializeArray();
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
			files: $(":file", $form),
			iframe: true,
			data: data,
			processData: false
		}).done(function(data) {
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				return joomprofile.profile.getfieldGroupViewHtml(user_id, fieldgroup_id);
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	joomprofile.profile.fieldgroup = {};
	joomprofile.profile.fieldgroup.addField = function(fieldgroup){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var fieldid = $('#joomprofile-fieldgroup-field-id').val();
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=fieldgroup.addField&id="+fieldgroup+"&field_id="+fieldid+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.fieldgroup.loadfields(fieldgroup);
				}
				else{
					alert('Error in adding field');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.fieldgroup.removeField = function(fieldgroup, field_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=fieldgroup.removeField&id="+fieldgroup+"&mapping_id="+field_id+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.fieldgroup.loadfields(fieldgroup);
				}
				else{
					alert('Error in removing field.');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.fieldgroup.loadfields = function(fieldgroup){
		if(fieldgroup == ''){
			$('#joomprofile-fieldgroup-fields').html('');
			return true;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=fieldgroup.loadfields&id="+fieldgroup+"&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					$('#joomprofile-fieldgroup-fields').html(data.html);
				}
				else{
					alert('Error in loading fileds of field');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.fieldgroup.changeFieldOrder = function(fieldgroup, field_id, otherfield_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=fieldgroup.changeFieldOrder&id="+fieldgroup+"&mapping_id="+field_id+"&otherid="+otherfield_id+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.fieldgroup.loadfields(fieldgroup);
				}
				else{
					alert('Error in loading fileds of field');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.fieldgroup.fieldboolean = function(fieldgroup, field_id, access_parameter){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=fieldgroup.boolean&id="+fieldgroup+"&mapping_id="+field_id+"&access_parameter="+access_parameter+"&"+jpFormToken+"=1&format=json"
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.fieldgroup.loadfields(fieldgroup);
				}
				else{
					alert('Error in loading fields.');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.usergroup = {};
	joomprofile.profile.usergroup.loadSearchFields = function(usergroup){
		if(usergroup == ''){
			$('#joomprofile-search-fields').html('');
			return true;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=usergroup.loadSearchFields&id="+usergroup+"&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					$('#joomprofile-search-fields').html(data.html);
				}
				else{
					alert('Error in loading search fields of usergroup');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.usergroup.addSearchField = function(usergroup){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var fieldid = $('#joomprofile-usergroup-searchfield-id').val();
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=usergroup.addSearchField&id="+usergroup+"&field_id="+fieldid+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.usergroup.loadSearchFields(usergroup);
				}
				else{
					alert('Error in adding field.');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.usergroup.removeSearchField = function(usergroup, field_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=usergroup.removeSearchField&id="+usergroup+"&mapping_id="+field_id+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.usergroup.loadSearchFields(usergroup);
				}
				else{
					alert('Error in removing field.');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.usergroup.changeSearchFieldOrder = function(usergroup, field_id, otherfield_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=usergroup.changeSearchFieldOrder&id="+usergroup+"&mapping_id="+field_id+"&otherid="+otherfield_id+"&"+jpFormToken+"=1&format=json&_="+(new Date()).getTime()
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.usergroup.loadSearchFields(usergroup);
				}
				else{
					alert('Error in loading fileds of field');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	joomprofile.profile.usergroup.fieldboolean = function(usergroup, field_id, access_parameter){
		if(f90_ajax_req_in_progress == true){
			return false;
		}

		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=usergroup.boolean&id="+usergroup+"&mapping_id="+field_id+"&parameter="+access_parameter+"&"+jpFormToken+"=1&format=json"
			}).done(function(data) {
				data = joomprofile.parseResponse(data);

				if(data.error == false){
					return joomprofile.profile.usergroup.loadSearchFields(usergroup);
				}
				else{
					alert('Error in loading fields.');
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});

		return true;
	};

	// REGISTRATION
	joomprofile.registration = {};
	joomprofile.registration.back = function(step){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var $form 	= $('#joomprofile-site-profile-registration-form');
		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&format=json&task=user.registration&_='+(new Date()).getTime();
		isJoomprofileRequesting	 = true;

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
			data: 'step='+step,
		}).done(function(data) {
			data = joomprofile.parseResponse(data);
			if(data.error == false){
				$form.fadeOut(500, function() {
			        $(this).html(data.html).fadeIn(500);
			        $('.hasTooltip').tooltip();
				    // apply validation
			        $(this).find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
			    });
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	joomprofile.registration.next = function(){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var $form 	= $('#joomprofile-site-profile-registration-form');
		if ($form.find("input:visible,textarea:visible,select:visible").jqBootstrapValidation("hasErrors")) {
			// @TODO : show popup or smething else
			$form.submit();
			return false;
		}

		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&format=json&_='+(new Date()).getTime();
		var data 	= $form.serialize();
		if($(":file", $form).length){
			url += "&transport=1";
			data 	= $form.serializeArray();
		}

		isJoomprofileRequesting	 = true;

		$('.jp-btn-next').attr('disabled', 'disabled');

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
			files: $(":file", $form),
			iframe: true,
			data: data,
			processData: false
		}).done(function(data) {
			$('.jp-btn-next').attr('disabled', 'false');
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				$form.fadeOut(500, function() {
			        $(this).html(data.html).fadeIn(500);
			        $('.hasTooltip').tooltip();
			        // apply validation
			        $(this).find("input,textarea,select").not('.no-validate').jqBootstrapValidation();
			    });
			}
			else{
				$('.jp-btn-next').attr('disabled', 'false');
				// @TODO ALERT
				return false;
			}
		});
	};

	joomprofile.registration.update_jusergroup = function(task, usergroup_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&format=json&task=user.'+task+'&usergroup_id='+parseInt(usergroup_id)+'&_='+(new Date()).getTime();
		isJoomprofileRequesting	 = true;

		return $.ajax({
			type: 'POST',
			async: false,
			url: url,
			data: ''
		}).done(function(data) {
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				return true;
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	joomprofile.registration.set_jusergroup = function(usergroup_id){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		var url 	= joomprofile.url.base+'index.php?option=com_joomprofile&view=profile&format=json&task=user.set_usergroup&usergroup_id='+parseInt(usergroup_id)+'&_='+(new Date()).getTime();
		isJoomprofileRequesting	 = true;

		return $.ajax({
			type: 'POST',
			async: false,
			url: url,
			data: ''
		}).done(function(data) {
			data = joomprofile.parseResponse(data);

			if(data.error == false){
				return true;
			}
			else{
				// @TODO ALERT
				return false;
			}
		});
	};

	$(document).ready(function(){
		// admin get field params html
		$('#joomprofile_form_type').change(function(){
			joomprofile.profile.field.getParameters($(this).val());
			return true;
		});

		// site : on selection of usergroups
		$('#joomprofile-registration-usergroup :checkbox').live('click', function(){
			var $this = $(this);
		    if ($this.is(':checked')) {
		        // the checkbox was checked
		    	if(joomprofile.registration.update_jusergroup('add_registration_usergroup', $this.val())){
		    		joomprofile.registration.back(1);
		    	}
		    } else {
		        // the checkbox was unchecked
		    	if(joomprofile.registration.update_jusergroup('remove_registration_usergroup', $this.val())){
		    		joomprofile.registration.back(1);
		    	}
		    	else{
		    		// @TODO: Error
		    	}
		    }
		});

		// site : on selection of usergroups : single select
		$('#joomprofile-registration-usergroup select').live('change', function(){
			var $this = $(this);
		    	if(joomprofile.registration.set_jusergroup($this.val())){
		    		joomprofile.registration.back(1);
		    	}
		    	else{
		    		// @TODO: Error
		    	}
		    });
	});
})(jQuery);
