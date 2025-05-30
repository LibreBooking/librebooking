function EmailTemplateManagement(opts) {
    var options = opts;

    var elements = {
        languageOpts: $('#languageOpts'),
        templateOpts: $('#templateOpts'),
        templateContents: $('#templateContents'),
        updateEmailForm: $('#updateEmailForm'),
        reloadEmailContents: $('#reloadEmailContents'),
        templatePath: $('#templatePath'),
        selectedLanguage: $('#selectedLanguage'),
        updateSuccess: $('#updateSuccess'),
        updateFailed: $('#updateFailed'),


        editEmailSection: $('#editEmailSection')
    };

    EmailTemplateManagement.prototype.init = function () {

        elements.languageOpts.on('change', function (e) {
            document.location = options.scriptUrl + '?lang=' + elements.languageOpts.val();
        });

        elements.templateOpts.on('change', function (e) {
            var templateName = elements.templateOpts.val();
            if (templateName == '') {
                elements.editEmailSection.addClass('d-none');
            }
            else {
                elements.templatePath.val(templateName);
                elements.selectedLanguage.val(elements.languageOpts.val());
                ajaxGet(options.scriptUrl + '?dr=template&lang=' + elements.languageOpts.val() + '&tn=' + templateName, null, loadTemplate);
            }
        });

        elements.reloadEmailContents.on('click', function (e) {
            e.preventDefault();
            var templateName = elements.templateOpts.val();
            ajaxGet(options.scriptUrl + '?dr=originalTemplate&lang=' + elements.languageOpts.val() + '&tn=' + templateName, null, loadTemplate);
        });

        ConfigureAsyncForm(elements.updateEmailForm, null, updatedEmail);

    };

    function updatedEmail(data) {
        if (data.saveResult == true) {
            elements.updateSuccess.css('display', '').removeClass('d-none').delay(2000).fadeOut(200, function () {
                $(this).addClass('d-none');
            });
        }
        else {
            elements.updateFailed.css('display', '').removeClass('d-none').delay(2000).fadeOut(200, function () {
                $(this).addClass('d-none');
            });
        }
    }

    function loadTemplate(data) {
        elements.templateContents.val(data);
        elements.editEmailSection.removeClass('d-none');
    }
}
