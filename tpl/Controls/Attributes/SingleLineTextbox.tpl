<div class="form-group {if isset($class)}{$class}{/if}">
	{if isset($readonly) && $readonly && isset($tooltip) && $tooltip}
		<span class="customAttribute readonly fw-bold">{$attribute->Label()}{if $attribute->Required() && !$searchmode}
				<i class="bi bi-asterisk text-danger align-top text-small"></i>
			{/if}</span>
	{else}
		<label
			class="customAttribute {if isset($readonly) && $readonly}readonly{elseif isset($searchmode) && $searchmode}search{else}standard{/if} fw-bold"
			for="{$attributeId}">{$attribute->Label()}{if $attribute->Required() && !$searchmode}
				<i class="bi bi-asterisk text-danger align-top text-small"></i>
			{/if}</label>
	{/if}
	{if isset($readonly) && $readonly}
		<span class="attributeValue {$class}">{$attribute->Value()}</span>
	{else}
		<div class="position-relative">
			<input id="{$attributeId}" name="{$attributeName}" value="{$attribute->Value()}" class="customAttribute form-control {if isset($inputClass)}{$inputClass}{/if}
		{if !$searchmode && $attribute->Required()}has-feedback{/if}"
				{if $attribute->Required() && !$searchmode}required="required" {/if} />
			{if $searchmode}
				<span class="searchclear searchclear-label bi bi-x-circle-fill" ref="{$attributeId}"></span>
			{/if}
		</div>
	{/if}
</div>
