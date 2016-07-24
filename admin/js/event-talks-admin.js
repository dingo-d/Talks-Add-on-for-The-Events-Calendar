(function( $ ) {
	'use strict';

	$(document).on('click', '.add_talk_image', upload_image_button)
			   .on('click', '.remove_talk_image', remove_image_button)
			   .on('click', '.add_talk', add_talk)
			   .on('click', '.remove_talk', remove_talk)
			   .on('click', '.tab', tab_switch);

	//Tab Switch

	function tab_switch(){
		var $this = $(this);
		var $tab = $('.tab');
		var $tab_content = $('.tab_content ');
		var tab_no = $this.data('tab');
		if (!$this.hasClass('active')) {
			$tab.removeClass('active');
			$tab_content.removeClass('active');
			$('#'+tab_no).addClass('active');
			$this.addClass('active');
		}
	}

	//Image Upload

	function upload_image_button(e) {
		e.preventDefault();
		var $this = $(this);
		var $input_field = $this.prev();
		var $image = $this.parent().find('.event_part_image');
		var custom_uploader = wp.media.frames.file_frame = wp.media({
			title: 'Add Image',
			button: {
				text: 'Add Image'
			},
			multiple: false
		});
		custom_uploader.on('select', function() {
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			$input_field.val(attachment.url);
			$image.attr('src', attachment.url);
		});
		custom_uploader.open();
	}

	function remove_image_button(e){
		e.preventDefault();
		var $this = $(this);
		var $input_field = $this.parents().find('.talk_image_url');
		var $image = $this.parent().find('.event_part_image');

		$input_field.val('');
		$image.attr('src', '');
	}

	//Field Add/Remove

	function add_talk(e){
		e.preventDefault();
		var $this = $(this);
		var tab_number = $this.closest('.tab_content').attr('id').split('_')[1];
		var talk_no = $this.closest('.tab_content').find('.number_of_talks').val();
		var $last_talk = $this.closest('.event_part_content');
		var empty_talk = $('.empty.event_part_content')[0].outerHTML;
		var new_empty = empty_talk.replace(/_N\[/g, '_'+tab_number+'[');
		var $talk = $(new_empty).clone(true);
		$talk.removeClass('empty');
		$talk.insertAfter($last_talk);
		$this.closest('.tab_content').find('.number_of_talks').val('').val(parseInt(talk_no,10)+1);
	}

	function remove_talk(e) {
		e.preventDefault();
		var $this = $(this);
		var talk_no_remove = $this.closest('.tab_content').find('.event_part_content').length;
		if (talk_no_remove > 1) {
			$this.closest('.tab_content').find('.number_of_talks').val('').val(parseInt(talk_no_remove,10)-1);
		}
		if ($this.closest('.tab_content').find('.event_part_content').length > 1) {
			$this.closest('.event_part_content').remove();
		}
	}

})( jQuery );