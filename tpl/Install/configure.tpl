{include file='globalheader.tpl'}

<div class="card shadow">
    <div class="card-body">
        <h1 class="border-bottom text-center mb-3">{translate key=ConfigureApplication}</h1>

        <form class="register" method="post" action="{$smarty.server.SCRIPT_NAME}">

            {if $ShowInvalidPassword}
                <div class="error alert alert-danger">{translate key=IncorrectInstallPassword}</div>
            {/if}

            {if $InstallPasswordMissing}
                <div class="error alert alert-info">
                    <p>{translate key=SetInstallPassword}</p>
                    <p>{translate key=InstallPasswordInstructions args="$ConfigPath,$ConfigSetting,$SuggestedInstallPassword"}
                    </p>
                </div>
            {/if}

            {if $ShowPasswordPrompt}
                <ul class="no-style">
                    <li>{translate key=ProvideInstallPassword}</li>
                    <li>{translate key=InstallPasswordLocation args="$ConfigPath,$ConfigSetting"}</li>
                    <li>{textbox type="password" name="INSTALL_PASSWORD" class="textbox" size="20"}
                        <button type="submit" name="" class="btn btn-outline-secondary mt-2"
                            value="submit">{translate key=Next}<i class="bi bi-arrow-right-circle-fill ms-1"></i></button>
                    </li>
                </ul>
            {/if}

            {if $ShowConfigSuccess}
                <h3>{translate key=ConfigUpdateSuccess} <a class="link-primary"
                        href="{$Path}{Pages::LOGIN}">{translate key=Login}</a></h3>
            {/if}

            {if $ShowManualConfig}
                {translate key=ConfigUpdateFailure}

                <div class="alert alert-secondary" style="font-family: courier;">
                    &lt;?php<br />
                    error_reporting(E_ALL & ~E_NOTICE);<br />
                    {$ManualConfig|nl2br}
                    ?&gt;
                </div>
            {/if}

        </form>
    </div>
</div>
{include file="javascript-includes.tpl"}
{include file='globalfooter.tpl'}