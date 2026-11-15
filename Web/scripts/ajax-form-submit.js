jQuery.fn.bindAjaxSubmit = function (updateButton, successElement, modalDiv) {
  var self = this;
  updateButton.click(function (e) {
    e.preventDefault();
    self.submit();
  });

  var defaultSubmitCallback = function (form) {
    return form.attr('action') + '?action=' + form.attr('ajaxAction');
  };

  function onValidationFailed(event, data) {
    hideModal();
  }

  function successHandler(response) {
    hideModal();
    successElement.removeClass('d-none').delay(5000).fadeOut();
  }

  function onBeforeAddSubmit(formData, jqForm, opts) {
    successElement.addClass('d-none');

    setDialogVisible('#waitModal', true);

    return true;
  }

  function hideModal() {
    setDialogVisible('#waitModal', false);

    var top = self.scrollTop();
    $('html, body').animate({ scrollTop: top }, 'slow');
  }

  self.bind('onValidationFailed', onValidationFailed);
  ConfigureAsyncForm(self, defaultSubmitCallback, successHandler, null, { onBeforeSubmit: onBeforeAddSubmit });
};
