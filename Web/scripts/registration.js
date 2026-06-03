function Registration() {
  var elements = {
    form: $('#form-register'),
  };

  Registration.prototype.init = function () {
    // $("#btnUpdate").click(function (e)
    // {
    // 	e.preventDefault();
    // 	e.stopPropagation();
    // 	elements.form.submit();
    // });

    elements.form.bind('onValidationFailed', onValidationFailed);

    ConfigureAsyncForm(elements.form, defaultSubmitCallback, successHandler, null, { onBeforeSubmit: onBeforeSubmit });
  };

  var defaultSubmitCallback = function (form) {
    return form.attr('action') + '?action=' + form.attr('ajaxAction');
  };

  function onValidationFailed(event, data) {
    elements.form.find('button').removeAttr('disabled');
    refreshCaptcha();
    hideModal();
  }

  function successHandler(response) {
    if (response && response.url) {
      window.location = response.url;
    } else {
      onValidationFailed();
      $('#registrationError').removeClass('hidden');
    }
  }

  function onBeforeSubmit(formData, jqForm, opts) {
    var bv = jqForm.data('bootstrapValidator');

    if (!bv.isValid()) {
      jqForm.find('.form-control').each(function () {
        if (!bv.isValidField($(this).attr('name'))) {
          $(this).addClass('is-invalid');
        }
      });
      return false;
    }

    $('#profileUpdatedMessage').hide();

    $('#waitModal').modal('show');

    return true;
  }

  function hideModal() {
    $('#waitModal').modal('hide');

    var top = $('#registrationbox').scrollTop();
    $('html, body').animate({ scrollTop: top }, 'slow');
  }

  function refreshCaptcha() {
    var captchaImg = $('#captchaImg');
    if (captchaImg.length > 0) {
      var src = captchaImg.attr('src') + '?' + Math.random();
      captchaImg.attr('src', src);
      $('#captchaValue').val('');
    } else if (window.grecaptcha) {
      grecaptcha.execute(grecaptcha.public_key, { action: 'reload' }).then(function (token) {
        var captcha = document.getElementById('g-recaptcha-response');
        captcha.value = token;
      });
    }
  }
}
