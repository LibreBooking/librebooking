function Configuration() {
  var elements = {
    form: $('#frmConfigSettings'),
    configFileSelection: $('#cf'),
    configFileForm: $('#frmConfigFile'),
    updateHomepageForm: $('#updateHomepageForm'),
    updateHomepageButton: $('#applyHomepage'),
  };

  Configuration.prototype.init = function () {
    $('.save').click(function (e) {
      e.preventDefault();
      elements.form.submit();
    });

    elements.configFileSelection.change(function (e) {
      elements.configFileForm.submit();
    });

    elements.form.bind('onValidationFailed', onValidationFailed);

    elements.updateHomepageButton.click(function (e) {
      e.preventDefault();
      $('#homepage_id').val($('#default__homepage\\|').val());
      elements.updateHomepageForm.submit();
    });

    ConfigureAsyncForm(elements.form, defaultSubmitCallback, successHandler, null, {
      onBeforeSubmit: onBeforeAddSubmit,
    });
    ConfigureAsyncForm(
      elements.updateHomepageForm,
      defaultSubmitCallback,
      function () {},
      function () {}
    );
  };

  var defaultSubmitCallback = function (form) {
    return form.attr('action') + '?action=' + form.attr('ajaxAction') + '&cf=' + elements.configFileSelection.val();
  };

  function onValidationFailed(event, data) {
    hideModal();
  }

  function successHandler(response) {
    hideModal();
    $('#updatedMessage').show().delay('3000').fadeOut('slow');
  }

  function onBeforeAddSubmit(formData, jqForm, opts) {
    $('#updatedMessage').hide();
    $('#waitModal').modal('show');

    return true;
  }

  function hideModal() {
    $('#waitModal').modal('hide');

    var top = $('#updatedMessage').scrollTop();
    $('html, body').animate({ scrollTop: top }, 'slow');
  }
}
