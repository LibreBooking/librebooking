$.fn.attachReservationPopup = function (refNum, detailsUrl) {
  var me = $(this);
  var hoverTimer = null;
  var ajaxRequest = null;
  var isHovering = false;
  var hoverDelayMs = 700;
  var cacheKey = 'reservationPopup' + refNum;

  if (detailsUrl == null) {
    detailsUrl = 'ajax/respopup.php';
  }

  function showTooltip(content) {
    // Recreate tooltip from current attributes so content always reflects latest state.
    me.tooltip('dispose');
    me.attr('data-bs-toggle', 'tooltip')
      .attr('data-bs-html', 'true')
      .attr('data-bs-custom-class', 'respopup-tooltip')
      .attr('data-bs-title', content)
      .tooltip({
        trigger: 'manual',
        html: true,
        customClass: 'respopup-tooltip',
      })
      .tooltip('show');
  }

  me.on('mouseenter', function () {
    isHovering = true;

    if (hoverTimer) {
      clearTimeout(hoverTimer);
      hoverTimer = null;
    }

    hoverTimer = setTimeout(function () {
      if (!isHovering) {
        return;
      }

      var cachedData = me.data(cacheKey);
      if (cachedData != null) {
        showTooltip(cachedData);
        return;
      }

      showTooltip('Loading...');

      ajaxRequest = $.ajax({ url: detailsUrl, data: { id: refNum } })
        .done(function (html) {
          me.data(cacheKey, html);
          if (!isHovering) {
            return;
          }
          showTooltip(html);
        })
        .fail(function (xhr, status, error) {
          if (status === 'abort' || !isHovering) {
            return;
          }
          showTooltip(status + ': ' + error);
        })
        .always(function () {
          ajaxRequest = null;
        });
    }, hoverDelayMs);
  });

  me.on('mouseleave', function () {
    isHovering = false;

    if (hoverTimer) {
      clearTimeout(hoverTimer);
      hoverTimer = null;
    }

    if (ajaxRequest) {
      ajaxRequest.abort();
      ajaxRequest = null;
    }

    var tooltipInstance = bootstrap.Tooltip.getInstance(me[0]);
    if (tooltipInstance) {
      tooltipInstance.hide();
    }
  });
};
