{include file='globalheader.tpl' Validator=true}

<div id="page-register">

    <div id="registration-box" class="default-box col-12 col-sm-8 mx-auto">
        <div class="card shadow mb-3">
            <div class="card-body mx-3">
                <form method="post" ajaxAction="{RegisterActions::Register}" id="form-register"
                    action="{$smarty.server.SCRIPT_NAME}" role="form" data-bv-submitbuttons='button[type="submit"]'
                    data-bv-onerror="enableButton" data-bv-onsuccess="enableButton" data-bv-live="enabled">

                    <h1 class="text-center border-bottom">{translate key=RegisterANewAccount}</h1>

                    <div class="error d-none" id="registrationError">
                        {translate key=UnknownError}
                    </div>


                    <div class="validationSummary alert alert-danger d-none" id="validationErrors">
                        <ul>
                            {async_validator id="uniqueemail" key="UniqueEmailRequired"}
                            {async_validator id="uniqueusername" key="UniqueUsernameRequired"}
                            {async_validator id="username" key="UserNameRequired"}
                            {async_validator id="emailformat" key="ValidEmailRequired"}
                            {async_validator id="fname" key="FirstNameRequired"}
                            {async_validator id="lname" key="LastNameRequired"}
                            {async_validator id="passwordmatch" key="PwMustMatch"}
                            {async_validator id="passwordcomplexity" key=""}
                            {async_validator id="captcha" key="CaptchaMustMatch"}
                            {async_validator id="additionalattributes" key=""}
                            {async_validator id="requiredEmailDomain" key="InvalidEmailDomain"}
                            {async_validator id="termsOfService" key="TermsOfServiceError"}
                            {async_validator id="phoneRequired" key="PhoneRequired"}
                            {async_validator id="positionRequired" key="PositionRequired"}
                            {async_validator id="organizationRequired" key="OrganizationRequired"}
                        </ul>
                    </div>

                    <div class="row gy-2">
                        <div class="col-12 col-sm-6" id="username">
                            <div class="form-group">
                                <label class="reg fw-bold" for="login">{translate key="Username"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox name="LOGIN" value="Login" required="required" class="has-feedback"
                            data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=UserNameRequired}"}
                            </div>
                        </div>

                        <div class="col-12 col-sm-6" id="email">
                            <div class="form-group">
                                <label class="reg fw-bold" for="email">{translate key="Email"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox type="email" name="EMAIL" class="input" value="Email" required="required"
                            data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=ValidEmailRequired}"
                                data-bv-emailaddress="true"
                                data-bv-emailaddress-message="{translate key=ValidEmailRequired}" }
                            </div>
                        </div>

                        <div class="col-12 col-sm-6" id="password">
                            <div class="form-group">
                                <label class="reg fw-bold" for="password">{translate key="Password"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox type="password" name="PASSWORD" value="" required="required"
                            data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=PwMustMatch}"
                                data-bv-identical="true"
                                data-bv-identical-field="{FormKeys::PASSWORD_CONFIRM}"
                                data-bv-identical-message="{translate key=PwMustMatch}" }
                            </div>
                        </div>

                        <div class="col-12 col-sm-6" id="password-confirm">
                            <div class="form-group">
                                <label class="reg fw-bold"
                                    for="passwordConfirm">{translate key="PasswordConfirmation"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox type="password" name="PASSWORD_CONFIRM" value="" required="required"
                            data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=PwMustMatch}"
                                data-bv-identical="true"
                                data-bv-identical-field="{FormKeys::PASSWORD}"
                                data-bv-identical-message="{translate key=PwMustMatch}"}
                            </div>
                        </div>

                        <div class="col-12 col-sm-6" id="first-name">
                            <div class="form-group">
                                <label class="reg fw-bold" for="fname">{translate key="FirstName"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox name="FIRST_NAME" class="input" value="FirstName" required="required"
                            data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=FirstNameRequired}"}
                            </div>
                        </div>
                        <div class="col-12 col-sm-6" id="last-name">
                            <div class="form-group">
                                <label class="reg fw-bold" for="lname">{translate key="LastName"}<i
                                        class="bi bi-asterisk text-danger align-top"
                                        style="font-size: 0.5rem;"></i></label>
                                {textbox name="LAST_NAME" class="input" value="LastName" required="required" data-bv-notempty="true"
                            data-bv-notempty-message="{translate key=LastNameRequired}"}
                            </div>
                        </div>

                        <div class="col-12 col-sm-6" id="default-page">
                            <div class="form-group">
                                <label class="reg fw-bold" for="homepage">{translate key="DefaultPage"}</label>
                                <select {formname key='DEFAULT_HOMEPAGE'} id="homepage" class="form-select">
                                    {html_options values=$HomepageValues output=$HomepageOutput selected=$Homepage}
                                </select>
                            </div>

                        </div>
                        <div class="col-12 col-sm-6" id="timezone">
                            <label class="reg fw-bold" for="timezoneDropDown">{translate key="Timezone"}</label>

                            <div class="input-group">
                                <span class="input-group-text"><a href="#" id="detectTimezone" class="link-primary"
                                        title="{translate key=Detect}"><i class="bi bi-clock"></i></a></span>
                                <select {formname key='TIMEZONE'} class="form-select" id="timezoneDropDown">
                                    {html_options values=$TimezoneValues output=$TimezoneOutput selected=$Timezone}
                                </select>
                            </div>
                        </div>

                        {if $RequirePhone || !$HidePhone}
                            <div class="col-12 col-sm-6" id="phone">
                                <div class="form-group">
                                    <label class="reg fw-bold" for="phone">{translate key="Phone"}{if $RequirePhone}<i
                                                class="bi bi-asterisk text-danger align-top"
                                            style="font-size: 0.5rem;"></i>{/if}</label>
                                    <input type="text" id="phone" {formname key="PHONE"} class="form-control" size="20"
                                        {if $RequirePhone}required="required" data-bv-notempty="true"
                                        data-bv-notempty-message="{translate key=PhoneRequired}" {/if} />
                                </div>
                            </div>
                        {/if}

                        {if $RequireOrganization || !$HideOrganization}
                            <div class="col-12 col-sm-6" id="organization">
                                <div class="form-group">
                                    <label class="reg fw-bold"
                                        for="txtOrganization">{translate key="Organization"}{if $RequireOrganization}<i
                                                class="bi bi-asterisk text-danger align-top"
                                            style="font-size: 0.5rem;"></i>{/if}</label>
                                    <input type="text" id="txtOrganization" {formname key="ORGANIZATION"}
                                        class="form-control" size="20" {if $RequireOrganization}required="required"
                                            data-bv-notempty="true"
                                        data-bv-notempty-message="{translate key=OrganizationRequired}" {/if} />
                                </div>
                            </div>
                        {/if}

                        {if $RequirePosition || !$HidePosition}
                            <div class="col-12 col-sm-6 " id="position">
                                <div class="form-group">
                                    <label class="reg fw-bold"
                                        for="txtPosition">{translate key="Position"}{if $RequirePosition}<i
                                                class="bi bi-asterisk text-danger align-top"
                                            style="font-size: 0.5rem;"></i>{/if}</label>
                                    <input type="text" id="txtPosition" {formname key="POSITION"} class="form-control"
                                        size="20" {if $RequirePosition}required="required" data-bv-notempty="true"
                                        data-bv-notempty-message="{translate key=PositionRequired}" {/if} />
                                </div>
                            </div>
                        {/if}

                        <div class="col-12 col-sm-6">
                            {if $Attributes|default:array()|count > 0}
                                {control type="AttributeControl" attribute=$Attributes[0]}
                            {/if}
                        </div>

                    </div>

                    {if $Attributes|default:array()|count > 1}
                        <div class="col-12 col-sm-6">
                            {control type="AttributeControl" attribute=$Attributes[$i]}
                        </div>
                    {/if}

                    {if $EnableCaptcha}
                        <div class="">
                            <div class="mb-2">
                                <div class="form-group text-center">
                                    {control type="CaptchaControl"}
                                </div>
                            </div>
                        </div>
                    {else}
                        <input type="hidden" {formname key=CAPTCHA} value="" />
                    {/if}

                    {if $Terms != null}
                        <div class="" id=" termsAndConditions">
                            <div class="mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="termsAndConditionsAcknowledgement"
                                        {formname key=TOS_ACKNOWLEDGEMENT} />
                                    <label class="form-check-label"
                                        for="termsAndConditionsAcknowledgement">{translate key=IAccept}</label>
                                    <a href="{$Terms->DisplayUrl()}" class="link-primary"
                                        target="_blank">{translate key=TheTermsOfService}</a>
                                </div>
                            </div>
                        </div>
                    {/if}

                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" name="{Actions::REGISTER}" value="{translate key='Register'}"
                            class="btn btn-primary" id="btnUpdate">{translate key='Register'}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {include file="javascript-includes.tpl" Validator=true}

    {jsfile src="ajax-helpers.js"}
    {jsfile src="autocomplete.js"}
    {jsfile src="registration.js"}

    <script type="text/javascript">
        function enableButton() {
            $('#form-register').find('button').removeAttr('disabled');
        }

        $(document).ready(function() {
            $('#detectTimezone').on('click', function(e) {
                e.preventDefault();

                const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone || "UTC";

                $('#timezoneDropDown').val(timezone);
            });

            var registrationPage = new Registration();
            registrationPage.init();

            var $frmRegister = $('#form-register');

            $frmRegister.on('init.field.bv', function(e, data) {
                    var $parent = data.element.parents('.form-group');
                    var $icon = $parent.find('.form-control-feedback[data-bv-icon-for="' + data.field +
                        '"]');
                    var validators = data.bv.getOptions(data.field).validators;

                    if (validators.notEmpty) {
                        $icon.addClass('bi bi-asterisk');
                    }
                })
                .off('success.form.bv')
                .on('success.form.bv', function(e) {
                    e.preventDefault();
                });

            $frmRegister.bootstrapValidator();

            $('#txtOrganization').orgAutoComplete("ajax/autocomplete.php?type={AutoCompleteType::Organization}");

        });
    </script>

    <div class="modal" id="waitModal" tabindex="-1" role="dialog" aria-labelledby="waitModalLabel"
        data-bs-backdrop="static" aria-hidden="true">
        {include file="wait-box.tpl" translateKey='Working'}
    </div>

</div>
{include file='globalfooter.tpl'}