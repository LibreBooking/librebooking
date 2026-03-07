// Credit: https://codepen.io/jaysalvat/pen/agLyf //
function dropzone(element, opts) {
	var options = $.extend({
		autoSubmit: false
	}, opts);

	var originalDivContent = null;

	element.on('dragover', function () {
		$(this).addClass('border-secondary');
	});

	element.on('dragleave', function () {
		$(this).removeClass('border-secondary');
	});

	element.find('input').on('change', function (e) {
		var file = this.files[0];

		if (originalDivContent == null) {
			originalDivContent = element.find('div').html();
		}
		if (!file) {
			element.find('div').html(originalDivContent);
			return;
		}

		//element.removeClass('border-secondary');

		element.addClass('border-secondary');
		element.find('img').remove();

		if ((/^image\/(gif|png|jpeg)$/i).test(file.type)) {
			var reader = new FileReader(file);

			reader.readAsDataURL(file);

			reader.onload = function (e) {
				var data = e.target.result, $img = $('<img />').attr('src', data).fadeIn();

				element.find('div').html($img);

				if (options.autoSubmit) {
					element.closest('form').submit();
					setTimeout(function () { // Delay for Chrome
						element.find('div').html(originalDivContent);
					}, 700);
				}
			};
		}
		else {
			element.find('div').html(file.name);
		}
	});
}