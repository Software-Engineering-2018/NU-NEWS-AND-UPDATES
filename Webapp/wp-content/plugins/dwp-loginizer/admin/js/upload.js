jQuery(function($){
	var frame;

	options_media_uploader();
	options_has_image();

	function options_has_image() {
		if ($('.image-preview').has('img').length) {
			$('.image-preview').has('img').parent().addClass('image-active');
			$('.image-preview').has('img').parent().removeClass('no-image');
		}
           /* $(this).parent().css('background', 'red');*/
            /*$(parent)
	        $('.action-button', parent).removeClass('upload-active');
			$('.action-button', parent).addClass('remove-active');*/

	}

	function options_media_uploader(){
		$('.option-upload-button').on('click', function(e){
			if($('.media-upload.upload-select').size()){
				$('.media-upload.upload-select').removeClass('upload-select');
			}

			if($(e.target).closest('.media-upload')){
				var parent = $(e.target).closest('.media-upload');
				parent = parent.addClass('upload-select');
				options_add_file(e, parent);
			}
		});

		$('.option-remove-button').on('click', function(e){
			if($(e.target).closest('.media-upload')){
				var parent = $(e.target).closest('.media-upload');
				options_remove_file(e, parent);
			}
		});
	}

	function options_add_file(e, parent){
		e.preventDefault();

		// If the media frame already exists, reopen it.
		if(frame){
			frame.open();
			return;
		}

		// Create the media frame.
		frame = wp.media({
			title: option_media_text.title,
			library: {
				type: 'image'
			},
			button: {
				text: option_media_text.button,
				close: false,
			},
			multiple: false,
		});

		// When an image is selected, run a callback.
		frame.on('select', function(){
			var image = frame.state().get('selection').first();
			frame.close();

            var image_url = image.toJSON().url;
            // parent id
			var parent_ID = $('.upload-select').attr('ID');
			parent = $('#'+parent_ID);

			/*if(image.attributes.type == 'image'){
				$('input[name*="_image"]', parent).val(image.attributes.url);
				$('.image-preview', parent).prepend('<img src="'+image.attributes.url+'" alt="" />');
				$(parent).addClass('image-active');
				$(parent).removeClass('no-image');
			}*/

			if(image.attributes.type == 'image'){
				$('input[name*="_image"]', parent).val(image.attributes.url);
				$('.image-preview', parent).prepend('<img src="'+image.attributes.url+'" alt="" />');
				$(parent).addClass('image-active');
				$(parent).removeClass('no-image');
			}

		});

		// Finally, open the modal.
		frame.open();
	}

	function options_remove_file(e, parent){
		$('.image-preview img', parent).remove();
		$('input[name*="_image"]', parent).val('');
		$(parent).removeClass('image-active');
		$(parent).addClass('no-image');
	}
});