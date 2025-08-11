<div class="form-group {$class}">
    <label class="customAttribute {if $readonly}readonly{elseif $searchmode}search{else}standard{/if} fw-bold"
        for="{$attributeId}">{$attribute->Label()}{if $attribute->Required() && !$searchmode}
            <i class="bi bi-asterisk text-danger align-top text-small"></i>
        {/if}</label>
    {if $readonly}
        <span class="attributeValue {$class}">{$attribute->Value()}</span>
    {else}
        <select id="{$attributeId}" name="{$attributeName}"
            class="customAttribute form-select {if !$searchmode && $attribute->Required()}has-feedback{/if} {$inputClass}"
            {if $attribute->Required() && !$searchmode}required{/if}>
            <option value="">--</option>
            {foreach from=$attribute->PossibleValueList() item=value}
                <option value="{$value}" {if $attribute->Value() == $value}selected="selected" {/if}>{$value}</option>
            {/foreach}
        </select>
    {/if}
</div>