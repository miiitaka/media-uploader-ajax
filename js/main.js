(function($) {
	$(function() {
		var custom_uploader;

		$("#media-upload").on("click", function() {
			if (custom_uploader) {
				custom_uploader.open();
			} else {
				custom_uploader = wp.media({
					title: 'Choose Image',
					library: {
						type: 'image'
					},
					button: {
						text: 'Choose Image'
					},
					multiple: false
				});

				custom_uploader.on("select", function () {
					var images = custom_uploader.state().get('selection');

					images.each(function(file) {
						$("#image-url").val(file.toJSON().url);
						$("#image-view").attr("src", file.toJSON().url);
					});
				});

				custom_uploader.open();
			}
		});
	});
})(jQuery);