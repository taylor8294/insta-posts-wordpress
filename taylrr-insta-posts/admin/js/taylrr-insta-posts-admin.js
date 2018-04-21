jQuery(function($){
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	var html = $('#autocomp-template')[0].innerText;
	var $autocomp = $(html);
	var $tpl_list_item = $autocomp.find('.autocomp-list-item').detach();
	$autocomp.hide().appendTo($('#taylrr-insta-posts-settings-field-username').parent());
	$('#taylrr-insta-posts-settings-field-username').attr('autocomplete','off');
	 var waitingToSend = false, latestTimestampReturned = 0;
	 $('#taylrr-insta-posts-settings-field-username').on('input',function(e){
	 	if(waitingToSend){
	 		clearTimeout(waitingToSend);
	 		waitingToSend = false;
	 	}
	 	var input = $(this).val();
	 	if(!input){
	 		$autocomp.hide();
	 		return;
	 	}
	 	waitingToSend = setTimeout(function(){
		 	waitingToSend = false;
	 		if(aboutToHide){
	 			clearTimeout(aboutToHide);
	 			aboutToHide = false;
	 		}
	 		$.ajax({
		 		method: 'POST',
		 		url: ajax_object.ajax_url,
		 		data: {
		 			action: 'insta_posts_autocomplete',
		 			input: input,
		 			wpnonce: ajax_object.ajax_nonce
		 		},
		 		success: function(data, textStatus, jqXHR){
		 			var timestampReturned = data[0];
		 			if(timestampReturned < latestTimestampReturned)
		 				return false;
		 			else
		 				latestTimestampReturned = timestampReturned;
		 			var tpl_list_item_string = $tpl_list_item[0].outerHTML;
		 			if(data.length > 1){
		 				$autocomp.find('.autocomp-list').empty();
		 				var list_item = '';
			 			for(var i = 1; i<data.length;i++){
				 			list_item = tpl_list_item_string.replace('{{user_link}}','http://instagram.com/'+data[i]['username']+'/');
				 			list_item = list_item.replace('{{user_image}}',data[i]['profile_pic_url']);
				 			list_item = list_item.replace('{{username}}',data[i]['username']);
				 			list_item = list_item.replace('{{user_name}}',data[i]['full_name']);
			 				$autocomp.find('.autocomp-list').append(list_item);
			 				$autocomp.show();
				 		}
			 		} else{
			 			$autocomp.find('.autocomp-list').empty().append('<p style="text-align:center;padding:1em 0;">No results found</p>');
			 			$autocomp.show();
			 		}
		 		},
		 		error: function(jqXHR, textStatus, errorThrown){
		 			console.log(errorThrown);
		 		}
		 	});
		}, 200);
		$autocomp.find('.autocomp-list').empty().html('<p style="text-align:center;padding:1em 0;">Loading...</p>');
		$autocomp.show();
	 });
	 var aboutToHide = false;
	 $('#taylrr-insta-posts-settings-field-username').on('blur',function(e){
	 	aboutToHide = setTimeout(function(){
	 		$('.autocomp').hide();
	 		aboutToHide = false;
	 	},500);
	 });
	 $('#taylrr-insta-posts-settings-field-username').on('focus',function(e){
	 	if(aboutToHide){
	 		clearTimeout(aboutToHide);
	 		aboutToHide = false;
	 	}
	 });
	 $(document).on('click','.autocomp-list-item',function(e){
	 	e.preventDefault();
	 	$('#taylrr-insta-posts-settings-field-username').val($(this).find('.autocomp-user-info-username').text());
	 	$('.autocomp').hide();
	 	return false;
	 });
});
