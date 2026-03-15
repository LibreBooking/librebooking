$.fn.attachReservationPopup = function (refNum, detailsUrl) {
  var me = $(this);
  if (detailsUrl == null) {
    detailsUrl = 'ajax/respopup.php';
  }

  me.on('mouseenter', function () {
    $.ajax({ url: detailsUrl, data: { id: refNum } })
      .done(function (html) {
        me.attr('data-bs-toggle', 'tooltip')
          .attr('data-bs-html', 'true')
          .attr('data-bs-custom-class', 'respopup-tooltip')
          .attr('data-bs-title', html)
          .tooltip('show');
      })
      .fail(function (xhr, status, error) {
        me.attr('data-bs-toggle', 'tooltip')
          .attr('title', status + ': ' + error)
          .tooltip('show');
      });
  });

  me.on('mouseleave', function () {
    me.tooltip('hide');
  });
};
