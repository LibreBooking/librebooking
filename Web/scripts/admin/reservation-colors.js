function ReservationColorManagement(opts) {
  var elements = {
    reservationColorTable: $('#reservationColorTable'),

    deleteRuleId: $('#deleteRuleId'),
    attributeOption: $('#attributeOption'),

    colorDialog: $('#colorDialog'),
    colorValue: $('#reservationColor'),
    colorForm: $('#colorForm'),

    addDialog: $('#addDialog'),
    addForm: $('#addForm'),
    deleteDialog: $('#deleteDialog'),
    deleteForm: $('#deleteForm'),
  };

  ReservationColorManagement.prototype.init = function () {
    elements.reservationColorTable.on('click', '.update', function (e) {
      e.preventDefault();
    });

    elements.reservationColorTable.on('click', '.delete', function () {
      elements.deleteRuleId.val($(this).attr('ruleId'));
      elements.deleteDialog.modal('show');
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('.cancel').click(function () {
      $(this).closest('.modal').modal('hide');
    });

    $('#addRuleButton').click(function (e) {
      var attrId = '#attribute' + elements.attributeOption.val();
      $('#attributeFillIn').empty();
      $('#attributeFillIn').append($(attrId).clone().removeClass('d-none'));
      elements.addDialog.modal('show');
    });

    ConfigureAsyncForm(elements.addForm);
    ConfigureAsyncForm(elements.deleteForm);
  };
}
