function Dashboard(opts) {
  var options = opts;

  var postForm = function (formElement, url, onAfter) {
    if (typeof BeforeSerialize === 'function') {
      BeforeSerialize(formElement);
    }

    var formData = new FormData(formElement);
    var params = new URLSearchParams();
    formData.forEach(function (value, key) {
      params.append(key, value);
    });

    return fetch(url || formElement.getAttribute('action'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
      },
      body: params.toString(),
      credentials: 'same-origin',
    })
      .then(function (response) {
        return response.text();
      })
      .then(function (data) {
        if (onAfter) {
          onAfter(data);
        }
      });
  };

  var setLoadingIcon = function (button, removeClass) {
    button.disabled = true;
    var icon = button.querySelector('i');
    if (icon) {
      icon.classList.remove(removeClass);
      icon.classList.add('spinner-border');
      icon.style.width = '1rem';
      icon.style.height = '1rem';
    }
  };

  var ShowReservationAjaxResponse = function () {
    var creatingNotification = document.getElementById('creatingNotification');
    var result = document.getElementById('result');
    if (creatingNotification) {
      creatingNotification.style.display = 'none';
    }
    if (result) {
      result.style.display = '';
    }
  };

  var CloseSaveDialog = function () {
    var waitBox = document.getElementById('wait-box');
    if (!waitBox) {
      return;
    }
    if (window.bootstrap && window.bootstrap.Modal) {
      window.bootstrap.Modal.getOrCreateInstance(waitBox).hide();
    }
  };
  Dashboard.prototype.init = function () {
    document.querySelectorAll('.resourceNameSelector').forEach(function (element) {
      bindResourceDetails(element, element.getAttribute('resource-id'));
    });

    var reservations = document.querySelectorAll('.reservation');

    document.querySelectorAll('.reservation .reservationTitle').forEach(function (titleElement) {
      var reservationElement = titleElement.closest('.reservation');
      var refNum = reservationElement ? reservationElement.id : null;
      window.attachReservationPopup(titleElement, refNum, options.summaryPopupUrl);
    });

    reservations.forEach(function (reservationElement) {
      reservationElement.addEventListener('mouseenter', function () {
        reservationElement.classList.add('hover');
      });

      reservationElement.addEventListener('mouseleave', function () {
        reservationElement.classList.remove('hover');
      });

      reservationElement.addEventListener('mousedown', function () {
        reservationElement.classList.add('clicked');
      });

      reservationElement.addEventListener('mouseup', function () {
        reservationElement.classList.remove('clicked');
      });

      reservationElement.addEventListener('click', function () {
        var refNum = reservationElement.id;
        window.location = options.reservationUrl + refNum;
      });
    });

    document.querySelectorAll('.btnCheckin').forEach(function (button) {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        setLoadingIcon(button, 'bi-box-arrow-in-right');

        var form = document.getElementById('form-checkin');
        var refNum = button.getAttribute('data-referencenumber');
        var referenceNumber = document.getElementById('referenceNumber');
        var waitBox = document.getElementById('wait-box');
        if (referenceNumber) {
          referenceNumber.value = refNum;
        }
        if (waitBox && window.bootstrap && window.bootstrap.Modal) {
          window.bootstrap.Modal.getOrCreateInstance(waitBox).show();
        }
        if (!form) {
          return;
        }

        postForm(form, button.getAttribute('data-url'), function (data) {
          document.querySelectorAll('button[data-referencenumber="' + refNum + '"]').forEach(function (buttonElement) {
            buttonElement.classList.add('d-none');
          });
          var result = document.getElementById('result');
          if (result) {
            result.innerHTML = data;
          }
          ShowReservationAjaxResponse();
        });
      });
    });

    document.querySelectorAll('.btnCheckout').forEach(function (button) {
      button.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        setLoadingIcon(button, 'bi-box-arrow-in-left');

        var form = document.getElementById('form-checkout');
        var refNum = button.getAttribute('data-referencenumber');
        var referenceNumber = document.getElementById('referenceNumber');
        if (referenceNumber) {
          referenceNumber.value = refNum;
        }
        if (!form) {
          return;
        }

        postForm(form, null, function (data) {
          document.querySelectorAll('button[data-referencenumber="' + refNum + '"]').forEach(function (buttonElement) {
            buttonElement.classList.add('d-none');
          });
          var result = document.getElementById('result');
          if (result) {
            result.innerHTML = data;
          }
          ShowReservationAjaxResponse();
        });
      });
    });

    var waitBox = document.getElementById('wait-box');
    if (waitBox) {
      waitBox.addEventListener('click', function (e) {
        var target = e.target;
        if (target && (target.closest('#btnSaveSuccessful') || target.closest('#btnSaveFailed'))) {
          CloseSaveDialog();
        }
      });
    }
  };
}
