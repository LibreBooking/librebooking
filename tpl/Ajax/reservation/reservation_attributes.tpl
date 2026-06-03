{if $Attributes|default:array()|count > 0}
    <div class="customAttributes border-bottom py-2">
        <div class="row gy-2">
            {foreach from=$Attributes item=attribute name=attributes}
                <div class="customAttribute col-sm-4 col-12">
                    {control type="AttributeControl" attribute=$attribute readonly=$ReadOnly}
                </div>
            {/foreach}
        </div>
    </div>
{/if}
