function Participation(opts) {
  var options = opts;

  var elements = {
    invitationAction: $('.participationAction'),
    referenceNumber: $('#referenceNumber'),
    indicator: $('#participate-indicator'),
    jsonResult: $('#jsonResult'),
  };

  Participation.prototype.initReservation = function () {
    elements.invitationAction.click(function (e) {
      e.preventDefault();
      e.stopPropagation();
      elements.indicator.show();
      RespondToInvitation($(this).val(), elements.referenceNumber.val(), $(this));
    });
  };

  Participation.prototype.initParticipation = function () {
    elements.invitationAction.click(function () {
      elements.jsonResult.hide();

      var td = $(this).parents('td');
      td.last('button').append(elements.indicator);
      elements.indicator.show();
      var referenceNumber = td.find('.referenceNumber').val();
      RespondToInvitation($(this).val(), referenceNumber, $(this));
    });

    $('.reservation').each(function () {
      var refNum = $(this).attr('referenceNumber');
      window.attachReservationPopup(this, refNum);
    });
  };

  function RespondToInvitation(action, referenceNumber, element) {
    $('#invite-error').remove();
    $.ajax({
      url: 'participation.php',
      dataType: 'json',
      data: { ia: action, rn: referenceNumber, rs: options.responseType },
      success: function (data) {
        if (data && data != null) {
          elements.indicator.hide();
          element.hide();
          element.after('<div class="alert alert-danger" id="invite-error">' + data + '</span>');
        } else {
          window.location.reload();
        }
      },
    });
  }
}
