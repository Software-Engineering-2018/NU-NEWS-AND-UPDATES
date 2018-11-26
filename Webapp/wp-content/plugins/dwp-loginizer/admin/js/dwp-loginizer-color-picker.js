jQuery(function($){
	options_colorpicker();

	function options_colorpicker(){
		$('.color-field').wpColorPicker({
			change: function(event, ui){
				$(this).val(ui.color.toString());
			},
		});
	}
});