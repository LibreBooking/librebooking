{if $Attributes|default:array()|count > 0}
    <div class="customAttributes">
        <div class="row">
            {foreach from=$Attributes item=attribute name=attributes}
                {if $smarty.foreach.attributes.index % 3 == 0}
                </div>
                <div class="row">
                {/if}
                <div class="customAttribute col-sm-4 col-12">
                    {control type="AttributeControl" attribute=$attribute readonly=$ReadOnly}
                </div>
            {/foreach}
        </div>
    </div>
    <div class="clear">&nbsp;</div>
{/if}