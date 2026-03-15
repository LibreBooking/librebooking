$.fn.bindUserDetails = function (userId, options) {
  return $(this).each(function (index, value) {
    var self = $(value);
    var opts = $.extend({ preventClick: true }, options);

    bind(self);

    function getDiv() {
      if ($('#userDetailsDiv').length <= 0) {
        return $('<div id="userDetailsDiv" class="card shadow-sm"/>').appendTo('body');
      } else {
        return $('#userDetailsDiv');
      }
    }

    function hideDiv() {
      var tag = getDiv();
      var timeoutId = setTimeout(function () {
        tag.hide();
      }, 500);
      tag.data('timeoutId', timeoutId);
    }

    function bind(userElement) {
      if (userElement.attr('user-details-bound') === '1') {
        return;
      }

      if (opts.preventClick) {
        userElement.click(function (e) {
          e.preventDefault();
        });
      }

      var tag = getDiv();

      tag
        .mouseenter(function () {
          clearTimeout(tag.data('timeoutId'));
        })
        .mouseleave(function () {
          hideDiv();
        });

      var hoverTimer;

      userElement
        .mouseenter(function () {
          if (hoverTimer) {
            clearTimeout(hoverTimer);
            hoverTimer = null;
          }

          hoverTimer = setTimeout(function () {
            var idToLoad = userId;
            if (!idToLoad) {
              idToLoad = userElement.attr('data-userid');
            }
            var tag = getDiv();
            clearTimeout(tag.data('timeoutId'));

            var data = tag.data('userPopup' + idToLoad);
            if (data != null) {
              showData(data);
            } else {
              $.ajax({
                url: 'ajax/user_details.php?uid=' + idToLoad,
                type: 'GET',
                cache: true,
                beforeSend: function () {
                  tag.html('Loading...').show();
                  tag.position({ my: 'left bottom', at: 'right top', of: userElement });
                },
                error: tag.html('Error loading user data!').show(),
                success: function (data, textStatus, jqXHR) {
                  tag.data('userPopup' + idToLoad, data);
                  showData(data);
                },
              });
            }

            function showData(data) {
              tag.html(data).show();
              tag.position({ my: 'left bottom', at: 'right top', of: userElement });
            }
          }, 500);
        })
        .mouseleave(function () {
          if (hoverTimer) {
            clearTimeout(hoverTimer);
            hoverTimer = null;
          }
          hideDiv();
        });

      userElement.attr('user-details-bound', '1');
    }
  });
};
