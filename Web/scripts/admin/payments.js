function Payments(opts) {
  var elements = {
    updateCreditsForm: $('#updateCreditsForm'),
    deleteCreditsForms: $('.deleteCreditsForm'),
    updateGatewayForm: $('#updateGatewayForm'),
    transactionLog: $('#transaction-log-content'),
    transactionLogIndicator: $('#transactionLogIndicator'),
    refundDialog: $('#refundDialog'),
    issueRefundForm: $('#issueRefundForm'),
    refundId: $('#refundId'),
    refundAmount: $('#refundAmount'),
  };

  Payments.prototype.init = function () {
    $('.save').click(function (e) {
      e.preventDefault();
      $(e.target).closest('form').submit();
    });

    $('.toggleDisabled').on('click', function (e) {
      var checkbox = $(e.target);
      var classToToggle = checkbox.attr('data-target');
      if (checkbox.is(':checked')) {
        $('.' + classToToggle).removeAttr('disabled');
      } else {
        $('.' + classToToggle).prop('disabled', 'disabled');
      }
    });

    elements.transactionLog.on('click', '.refund', function (e) {
      var element = $(e.target);
      var id = element.data('id');
      ajaxGet(opts.transactionDetailsUrl.replace('[id]', id), null, function (data) {
        var amount = data.Total - data.AmountRefunded;
        elements.refundAmount.prop('max', amount);
        elements.refundAmount.val(amount);
        elements.refundId.val(id);
        elements.refundDialog.modal('show');
      });
    });

    loadTransactionLog(0, 0);

    ConfigureAsyncForm(
      elements.updateCreditsForm,
      defaultSubmitCallback,
      function () {
        showMessage('updatedCreditsMessage').animate(
          { height: 0 },
          {
            complete: function () {
              location.reload();
            },
          }
        ); // TODO: cleaner table reload
      },
      function () {}
    );
    elements.deleteCreditsForms.each(function () {
      ConfigureAsyncForm(
        $(this),
        defaultSubmitCallback,
        function () {
          showMessage('updatedCreditsMessage').animate(
            { height: 0 },
            {
              complete: function () {
                location.reload();
              },
            }
          ); // TODO: cleaner table reload
        },
        function () {}
      );
    });
    ConfigureAsyncForm(
      elements.updateGatewayForm,
      defaultSubmitCallback,
      function () {
        showMessage('updatedGatewayMessage');
      },
      function () {}
    );
    ConfigureAsyncForm(
      elements.issueRefundForm,
      defaultSubmitCallback,
      function () {
        showMessage('refundIssuedMessage');
        elements.refundDialog.modal('hide');
        loadTransactionLog(0, 0);
      },
      function () {}
    );
  };

  Payments.prototype.initGateways = function (paypalEnabled, stripeEnabled) {
    if (paypalEnabled) {
      $('#paypalEnabled').click();
    }
    if (stripeEnabled) {
      $('#stripeEnabled').click();
    }
  };

  function loadTransactionLog(page, pageSize) {
    elements.transactionLogIndicator.removeClass('no-show');

    ajaxGet(opts.transactionLogUrl.replace('[page]', page).replace('[pageSize]', pageSize), null, function (data) {
      elements.transactionLogIndicator.addClass('no-show');
      elements.transactionLog.html(data);

      ajaxPagination(elements.transactionLog, function (page, size) {
        loadTransactionLog(page, size);
      });
    });
  }

  var defaultSubmitCallback = function (form) {
    return form.attr('action') + '?action=' + form.attr('ajaxAction');
  };

  var showMessage = function (id) {
    return $('#' + id)
      .show()
      .delay(2000)
      .fadeOut(200);
  };
}
