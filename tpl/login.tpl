{include file='globalheader.tpl'}

<div id="page-login">

    {if $EnableCaptcha}
        {validation_group class="alert alert-danger"}
        {validator id="captcha" key="CaptchaMustMatch"}
        {/validation_group}
    {/if}

    {if $Announcements|default:array()|count > 0}
        <div id="announcements" class="col-sm-8 col-12 card mx-auto shadow mt-5">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    {foreach from=$Announcements item=each}
                        <li class="announcement list-group-item">{$each->Text()|html_entity_decode}</li>
                    {/foreach}
                </ul>
            </div>
        </div>
    {/if}

    <div class="col-md-6 col-12 mx-auto mt-5">
        <form role="form" name="login" id="login" class="form-horizontal" method="post"
            action="{$smarty.server.SCRIPT_NAME}">
            <div class="card shadow mb-2">
                <div class="card-body mx-3">
                    <div id="login-box" class="default-box">
                        <div class="login-icon my-2">
                            <img src="{$Path}img/{$LogoUrl}?{$Version}" alt="{$Title}" class="mx-auto d-block w-50">
                        </div>

                        {if $ShowLoginError}
                            <div id="loginError" class="alert alert-danger">
                                {translate key='LoginError'}
                            </div>
                        {/if}

                        {if $ShowUsernamePrompt}
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" required="" class="form-control" id="email" {formname key=EMAIL}
                                    placeholder="{translate key=UsernameOrEmail}" />
                            </div>
                        {/if}

                        {if $ShowPasswordPrompt}
                            <div class="input-group mb-2">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" required="" id="password" {formname key=PASSWORD}
                                    class="form-control" value="" placeholder="{translate key=Password}" />
                            </div>
                        {/if}

                        {if $EnableCaptcha}
                            <div class="text-center mb-2">
                                {control type="CaptchaControl"}
                            </div>
                        {else}
                            <input type="hidden" {formname key=CAPTCHA} value="" />
                        {/if}

                        {if $ShowUsernamePrompt &&  $ShowPasswordPrompt}
                            <div class="d-grid mb-2 mt-3">
                                <button type="submit" class="btn btn-primary btn-block" name="{Actions::LOGIN}"
                                    value="submit">{translate key='LogIn'}</button>
                                <input type="hidden" {formname key=RESUME} value="{$ResumeUrl}" />
                            </div>
                        {/if}

                        <div class="clearfix">
                            {if $ShowUsernamePrompt &&  $ShowPasswordPrompt}
                                <div class="float-start">
                                    <div class="form-check">
                                        <input class="form-check-input" id="rememberMe" type="checkbox"
                                            {formname key=PERSIST_LOGIN}>
                                        <label class="form-check-label" for="rememberMe">{translate key=RememberMe}</label>
                                    </div>
                                </div>
                            {/if}

                            {if $ShowRegisterLink}
                                <div class="float-end register">
                                    <span class="fw-bold">{translate key="FirstTimeUser?"}
                                        <a class="link-primary" href="{$RegisterUrl}"
                                            {if isset($RegisterUrlNew)}{$RegisterUrlNew}{/if}
                                            title="{translate key=Register}">{translate key=Register}</a>
                                    </span>
                                </div>
                            {/if}
                        </div>

                        <section class="d-flex justify-content-center flex-wrap gap-2 my-3 social-login">
                            {if $AllowGoogleLogin}
                                <a type="button" href="{$GoogleUrl}" class="btn btn-outline-primary"><i
                                        class="bi bi-google me-1"></i>{translate key='SignInWith'}<span class="fw-medium">
                                        Google</span></a>
                            {/if}
                            {if $AllowMicrosoftLogin}
                                <a type="button" href="{$MicrosoftUrl}" class="btn btn-outline-primary"><i
                                        class="bi bi-microsoft me-1"></i>{translate key='SignInWith'}<span
                                        class="fw-medium"> Microsoft</span></a>
                            {/if}
                            {if $AllowFacebookLogin}
                                <a type="button" href="{$FacebookUrl}" class="btn btn-outline-primary"><i
                                        class="bi bi-facebook me-1"></i>{translate key='SignInWith'}<span class="fw-medium">
                                        Facebook</span></a>
                            {/if}
                            {if $AllowKeycloakLogin}
                                <a type="button" href="{$KeycloakUrl}"
                                    class="btn btn-outline-primary">{translate key='SignInWith'}<span class="fw-medium">
                                        Keycloak</span></a>
                            {/if}
                            {if $AllowOauth2Login}
                                <a type="button" href="{$Oauth2Url}"
                                    class="btn btn-outline-primary">{translate key='SignInWith'}<span class="fw-medium">
                                        {$Oauth2Name}</span></a>
                            {/if}
                        </section>
                        {if $facebookError}
                            <p class="text-center my-3">
                                {translate key="FacebookLoginErrorMessage"} </p>
                        {/if}
                    </div>
                </div>
                <div id="login-footer" class="card-footer d-flex align-items-start justify-content-between">
                    {if $ShowForgotPasswordPrompt}
                        <div id="forgot-password">
                            <a href="{$ForgotPasswordUrl}" {if isset($ForgotPasswordUrlNew)}{$ForgotPasswordUrlNew}{/if}
                                class="link-primary"><span><i class="bi bi-question-circle-fill"></i></span>
                                {translate key='ForgotMyPassword'}</a>
                        </div>
                    {/if}
                    <div id="change-language" class="text-end">
                        <a type="button" class="link-primary" data-bs-toggle="collapse"
                            data-bs-target="#change-language-options"><span><i class="bi bi-globe-americas"></i></span>
                            {translate key=ChangeLanguage}
                        </a>
                        <div id="change-language-options" class="collapse">
                            <select {formname key=LANGUAGE} class="form-select form-select-sm" id="languageDropDown">
                                {object_html_options options=$Languages key='GetLanguageCode' label='GetDisplayName' selected=$SelectedLanguage}
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

{setfocus key='EMAIL'}

{include file="javascript-includes.tpl"}

<script type="text/javascript">
    var url = 'index.php?{QueryStringKeys::LANGUAGE}=';
    $(document).ready(function() {
        $('#languageDropDown').change(function() {
            window.location.href = url + $(this).val();
        });

        var langCode = '{$smarty.cookies.language|escape:"javascript"}';

        if (!langCode) {
            langCode = (navigator.language + "").replace("-", "_").toLowerCase();

            var availableLanguages = [{foreach from=$Languages item=lang}"{$lang->GetLanguageCode()}",{/foreach}];
            if (langCode !== "" && langCode != '{$SelectedLanguage|lower}') {
            if (availableLanguages.indexOf(langCode) !== -1) {
                window.location.href = url + langCode;
            }
        }
    }
    });
</script>
{include file='globalfooter.tpl'}