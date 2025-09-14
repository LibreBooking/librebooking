function Profile() {
	var elements = {
		form: $('#form-profile')
	};

	Profile.prototype.init = function () {

		$("#btnUpdate").click(function (e) {
			e.preventDefault();
			elements.form.submit();
		});

		elements.form.bind('onValidationFailed', onValidationFailed);

		ConfigureAsyncForm(elements.form, defaultSubmitCallback, successHandler, null, { onBeforeSubmit: onBeforeSubmit });
	};

	var defaultSubmitCallback = function (form) {
		return form.attr('action') + "?action=" + form.attr('ajaxAction');
	};

	function onValidationFailed(event, data) {
		elements.form.find('button').removeAttr('disabled');
		hideModal();
		$('#validationErrors').removeClass('d-none');
	}

	function successHandler(response) {
		hideModal();
		$('#profileUpdatedMessage').removeClass('d-none');
	}

	function onBeforeSubmit(formData, jqForm, opts) {
		var bv = jqForm.data('bootstrapValidator');

		if (!bv.isValid() && bv.$invalidFields.length > 0) {
			return false;
		}
		jqForm.find('input[type="checkbox"][name^="psiattribute["]').each(function () {
			var $el = $(this);
			var name = $el.attr('name');
			var value = $el.is(':checked') ? 1 : 0;
			var index = formData.findIndex(function(item) { return item.name === name; });
			if (index >= 0) {
				formData[index].value = value;
			} else {
				formData.push({ name: name, value: value });
			}
		});

		$('#profileUpdatedMessage').addClass('d-none');

		$('#waitModal').modal('show');

		return true;
	}

	function hideModal() {
		$('#waitModal').modal('hide');

		var top = $("#profile-box").scrollTop();
		$('html, body').animate({ scrollTop: top }, 'slow');
	}

}
