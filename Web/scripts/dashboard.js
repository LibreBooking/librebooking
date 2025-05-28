function Dashboard(opts) {
    var options = opts;

    var ShowReservationAjaxResponse = function () {
        $('#creatingNotification').hide();
        $('#result').show();
    };

    var CloseSaveDialog = function () {
        $('#wait-box').modal('hide');
    };
    Dashboard.prototype.init = function () {

        $('.resourceNameSelector').each(function () {
            $(this).bindResourceDetails($(this).attr('resource-id'));
        });

        var reservations = $(".reservation");

        function attachReservationTooltip(reservations, options) {
            reservations.on('mouseenter', function () {
                var me = $(this);
                var refNum = me.attr('id');

                me.attr('data-bs-toggle', 'tooltip')
                    .tooltip('show');

                $.ajax({
                    url: options.summaryPopupUrl,
                    data: { id: refNum }
                })
                    .done(function (html) {
                        me.attr('data-bs-original-title', html).tooltip('show');
                    })
                    .fail(function (xhr, status, error) {
                        me.attr('data-bs-original-title', status + ': ' + error).tooltip('show');
                    });
            });

            reservations.on('mouseleave', function () {
                $(this).tooltip('hide');
            });
        }

        $(document).ready(function () {
            var reservations = $('.reservation');
            var options = {
                summaryPopupUrl: 'ajax/respopup.php'
            };

            attachReservationTooltip(reservations, options);

            $('[data-bs-toggle="tooltip"]').tooltip();
        });

        reservations.hover(function () {
            $(this).addClass('hover');
        }, function () {
            $(this).removeClass('hover');
        });

        reservations.mousedown(function () {
            $(this).addClass('clicked');
        });

        reservations.mouseup(function () {
            $(this).removeClass('clicked');
        });

        reservations.click(function () {
            var refNum = $(this).attr('id');
            window.location = options.reservationUrl + refNum;
        });

        $('.btnCheckin').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var button = $(this);
            button.attr('disabled', 'disabled');
            button.find('i').removeClass('bi-box-arrow-in-right').addClass('spinner-border').css({
                'width': '1rem',
                'height': '1rem'
            });

            var form = $('#form-checkin');
            var refNum = $(this).attr('data-referencenumber');
            $('#referenceNumber').val(refNum);
            $('#wait-box').modal('show');
            ajaxPost(form, $(this).data('url'), null, function (data) {
                $('button[data-referencenumber="' + refNum + '"]').addClass('d-none');
                $('#result').html(data);
                ShowReservationAjaxResponse();
            });
        });

        $('.btnCheckout').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            var button = $(this);
            button.attr('disabled', 'disabled');
            button.find('i').removeClass('bi-box-arrow-in-left').addClass('spinner-border').css({
                'width': '1rem',
                'height': '1rem'
            });

            var form = $('#form-checkout');
            var refNum = $(this).attr('data-referencenumber');
            $('#referenceNumber').val(refNum);
            ajaxPost(form, null, null, function (data) {
                $('button[data-referencenumber="' + refNum + '"]').addClass('d-none');
                $('#result').html(data);
                ShowReservationAjaxResponse();
            });
        });

        $('#wait-box').on('click', '#btnSaveSuccessful', function (e) {

            CloseSaveDialog();
        });

        $('#wait-box').on('click', '#btnSaveFailed', function (e) {
            CloseSaveDialog();
        });
    };
}