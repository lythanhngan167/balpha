if(typeof(joomprofile) == 'undefined'){
	var joomprofile = {};
	joomprofile.url = {};
	joomprofile.jQuery = window.jQuery;
}

var f90_ajax_req_in_progress = false;
var isJoomprofileRequesting	 = false;


(function($){

	joomprofile.edit = {
		form : {
			submit : function(form_id, task){
						$('input[name="task"]').val(task);
						$('#' + form_id).submit();
						return true;
			}
		}
	};

	joomprofile.saveisconsultinger = function(user_id, is_consultinger,url){
		var url 	= url+'index.php?option=com_joomprofile&view=profile&task=user.saveisconsultinger&user_id='+user_id+'&is_consultinger='+is_consultinger+'&format=json&_='+(new Date()).getTime();

		$.ajax({
			type: 'POST',
			async: false,
			url: url,
			iframe: true,
			processData: false
		}).done(function(data) {
			//data = joomprofile.parseResponse(data);
			if(data == '1'){
				alert("Cập nhật thành công!");
				location.reload();
			}else{
				alert("Cập nhật thất bại, vui lòng thử lại.");
			}
		});
	};

	joomprofile.parseResponse = function(data){
		if(typeof(data) == 'object'){
			return data;
		}

		// remove tokens
		var valid_pos = data.indexOf('#F90JSON#');
		var valid_last_pos = data.lastIndexOf('#F90JSON#');
		if( valid_pos == -1 ) {
			// Valid data not found in the response
			data = 'Invalid AJAX data: ' + data;
			alert(data);
			return;
		}

		// get message between #F90JSON#<----->#F90JSON# second argument is length
		data = data.substr(valid_pos+9, valid_last_pos-(valid_pos+9));

		return $.parseJSON(data);
	};

	joomprofile.radio = {
			init : function(){
				 //needed some fix to show radio button properly in front-end
			    // Turn radios into btn-group
				$('.radio.btn-group label').addClass('btn');

				$(".btn-group input[checked=checked]").each(function()
				{
					if ($(this).val() == '') {
						$("label[for=" + $(this).attr('id') + "]").addClass('active btn-primary');
					} else if ($(this).val() == 0) {
						$("label[for=" + $(this).attr('id') + "]").addClass('active btn-danger');
					} else {
						$("label[for=" + $(this).attr('id') + "]").addClass('active btn-success');
					}
				});
			},

			applyBtnClass : function(btn){
				  var label = $(btn);
				  var input = $('#' + label.attr('for'));

				  if (!input.prop('checked')) {
					  label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
					  if (input.val() == '') {
						  label.addClass('active btn-primary');
					  } else if (input.val() == 0) {
						  label.addClass('active btn-danger');
					  } else {
						  label.addClass('active btn-success');
					  }
					  input.prop('checked', true);
				  }
			}
	};

	$(document).ready(function(){

		$(document).on('click','.btn-group label:not(.active)', function(){
			joomprofile.radio.applyBtnClass(this);
			});


		// remove header
		$('.admin header.header').remove();
		$('.admin .subhead-collapse').remove();

		// add overlay
		$('body').append('<div id="f90-overlay" style="display:none;">&nbsp;</div>');

		$('[name="filter_search"]').keypress(function(e) {
  			if (e.which == 13) {
    			$('form#joomprofile-filter-form').submit();
    		}
		});

		$(document).ajaxStart(function(){
			if(isJoomprofileRequesting != false){

				if(this.activeElement.hasAttribute('data-validation-ajax-ajax') == false){
					f90_ajax_req_in_progress = true;
					$('#f90-overlay').show();
					isJoomprofileRequesting = false;

				}
			}
		}).ajaxStop(function(){
			f90_ajax_req_in_progress = false;
			$('#f90-overlay').hide();
		});

		$('.hasTooltip').tooltip();
	});
})(joomprofile.jQuery);
