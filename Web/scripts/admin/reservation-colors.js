function ReservationColorManagement(opts) {
  var elements = {
    reservationColorTable: $('#reservationColorTable'),

    deleteRuleId: $('#deleteRuleId'),
    attributeOption: $('#attributeOption'),

    colorDialog: $('#colorDialog'),
    colorValue: $('#reservationColor'),
    colorForm: $('#colorForm'),

    addDialog: document.getElementById('addDialog'),
    addForm: $('#addForm'),
    deleteDialog: document.getElementById('deleteDialog'),
    deleteForm: $('#deleteForm'),
  };

  ReservationColorManagement.prototype.init = function () {
    elements.reservationColorTable.on('click', '.update', function (e) {
      e.preventDefault();
    });

    elements.reservationColorTable.on('click', '.delete', function () {
      elements.deleteRuleId.val($(this).attr('ruleId'));
      setDialogVisible(elements.deleteDialog, true);
    });

    $('.save').click(function () {
      $(this).closest('form').submit();
    });

    $('#addRuleButton').click(function (e) {
      var attrId = '#attribute' + elements.attributeOption.val();
      $('#attributeFillIn').empty();
      $('#attributeFillIn').append($(attrId).clone().removeClass('d-none'));
      setDialogVisible(elements.addDialog, true);
    });

    ConfigureAsyncForm(elements.addForm);
    ConfigureAsyncForm(elements.deleteForm);
  };
}
