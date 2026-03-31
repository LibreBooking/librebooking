<div class="form-group {$class}">
	{assign value="{$attribute->Value()}" var="attributeValue"}
	<label class="customAttribute d-block {if $readonly}readonly{elseif $searchmode}search{else}standard{/if} fw-bold"
		for="{$attributeId}">{$attribute->Label()}{if $attribute->Required() && !$searchmode}
			<i class="bi bi-asterisk text-danger align-top text-small"></i>
		{/if}</label>
	{if $readonly}
		<span class="attributeValue {$class}">{formatdate date=$attributeValue key=general_datetime}</span>
	{else}
		<input type="text" id="{$attributeId}" name="{$attributeName}"
			class="customAttribute form-control form-control-sm {if !$searchmode && $attribute->Required()}has-feedback{/if} {$class}" />
		{control type="DatePickerSetupControl" ControlId="{$attributeId}" DefaultDate=$attributeValue HasTimepicker=true}
	{/if}
</div>
