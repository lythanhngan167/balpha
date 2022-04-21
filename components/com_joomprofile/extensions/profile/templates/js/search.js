if(typeof(joomprofile.search) == 'undefined'){
	joomprofile.search = {};
}

(function($){
	joomprofile.search.update = function(fieldid, value){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		
		var data = {};
		var sortby = $('#jps-sort-by').val();
		var sortin = $('#jps-sort-in').val();

		if(fieldid != 0){
			data = {'joomprofile-searchfield' :  [{ 'fieldid' : fieldid, 'value' : value}]};
		}
		
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=search.update&sortby="+sortby+"&sortin="+sortin+"&format=json&_="+(new Date()).getTime(),
			data: data,
			type:'POST'
			}).done(function(data) {
				data = joomprofile.parseResponse(data);
				$('.jp-search-userlist').html(data.html);
				$('#jp-search-criteria-accord').html(data.conditions);
				$('.jp-search-filters').html(data.filters);
				var formSearch = $('.search-name').clone().removeClass('d-none');
				console.log(formSearch);
				$('#jp-search-criteria-accord').append(formSearch.html());
				if(data.show_button){
					$('.jp-search-loadmore').show();
					$('.jp-search-loadmore').prop('data-page', 2);
				}
				else{
					$('.jp-search-loadmore').hide();
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});
		
		return true;
	};
		
	joomprofile.search.loadmore = function(){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		
		var page = $('.jp-search-loadmore').prop('data-page');
		
		var data = {};
		var sortby = $('#jps-sort-by').val();
		var sortin = $('#jps-sort-in').val();
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=search.loadMore&page="+page+"&sortby="+sortby+"&sortin="+sortin+"&format=json&_="+(new Date()).getTime(),
			data: {},
			type:'POST'
			}).done(function(data) {
				data = joomprofile.parseResponse(data);
				$('.jp-search-userlist').append(data.html);
				if(data.show_button){
					$('.jp-search-loadmore').show();
					$('.jp-search-loadmore').prop('data-page', page+1);
				}
				else{
					$('.jp-search-loadmore').hide();
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});
		
		return true;
	};
	
	joomprofile.search.sort = function(){
		if(f90_ajax_req_in_progress == true){
			return false;
		}
		
		var sortby = $('#jps-sort-by').val();
		var sortin = $('#jps-sort-in').val();
		
		var data = {};
		isJoomprofileRequesting	 = true;

		$.ajax({
			url: joomprofile.url.base+"index.php?option=com_joomprofile&view=profile&task=search.sort&sortby="+sortby+"&sortin="+sortin+"&format=json&_="+(new Date()).getTime(),
			data: {},
			type:'POST'
			}).done(function(data) {
				data = joomprofile.parseResponse(data);
				$('.jp-search-userlist').html(data.html);
				if(data.show_button){
					$('.jp-search-loadmore').show();
					$('.jp-search-loadmore').prop('data-page', 2);
				}
				else{
					$('.jp-search-loadmore').hide();
				}
			}).fail(function() {
				// TODO : model popup
				alert('Error in connection.');
			});
		
		return true;
	};
	
	$(document).ready(function(){
		
		$('.jp-search-loadmore').click(function(){
			joomprofile.search.loadmore();
			return false;
		});

		$('.form-select-search').on('change', 'select', function(){
			var fieldid = $(this).attr('data-f90-field-id');
			var value = $(this).val();
			joomprofile.search.update(fieldid, value);
		});

		$('#jp-search-form').on('blur', 'input[type="text"], input[type="email"], input[type="number"]', function(){
			
			if ($(this).hasClass('no-search')) {
				return true;
			}
			
			var fieldid = $(this).attr('data-f90-field-id');
			var value = $(this).val();
			joomprofile.search.update(fieldid, value);
		});
		
		$('#jp-search-form').on('change', ':checkbox', function(){
			if ($(this).hasClass('no-search')) {
				return true;
			}
			
			var fieldid = $(this).attr('data-f90-field-id');
			var value = [];
			$.each($(':checkbox[data-f90-field-id="'+fieldid+'"]'), function(i, l){
				if(l.checked){
					value.push($(l).val());
				}
			});
			joomprofile.search.update(fieldid, value);
		});
		
		$('#jp-search-form').on('click', 'button', function(){
			if ($(this).hasClass('no-search')) {
				return true;
			}
			
			var fieldid = $(this).attr('data-f90-field-id');
			var value = [];
			$.each($('input[data-f90-field-id="'+fieldid+'"], select[data-f90-field-id="'+fieldid+'"]'), function(i, l){
				value.push($(l).val());
			});
			
			// IMP : If only one value then do not send as array
			if(value.length == 1){
				value = value[0];
			}
			
			joomprofile.search.update(fieldid, value);
		});
		
		$('#jp-search-form').on('change', '#jps-sort-by', function(){
			joomprofile.search.sort();
		});
		
		$('#jp-search-form').on('change', '#jps-sort-in', function(){
			joomprofile.search.sort();
		});
		
		$('#jp-search-form').on('click', 'a.jps-clear-search', function(){
			joomprofile.search.update($(this).attr('data-f90-field-id'), '');
		});
		
	});
})(jQuery);