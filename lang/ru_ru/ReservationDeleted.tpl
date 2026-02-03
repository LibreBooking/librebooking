<p><strong>Детали бронирования:</strong></p>

<p>
	<strong>Начало:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Окончание:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Название:</strong> {$Title}<br/>
	<strong>Описание:</strong> {$Description|nl2br}
    {if $Attributes|default:array()|count > 0}
	<br/>
    {foreach from=$Attributes item=attribute}
	<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
    {/foreach}
{/if}
</p>

<p>
    {if $ResourceNames|default:array()|count > 1}
		<strong>Ресурсы ({$ResourceNames|default:array()|count}):</strong>
		<br/>
        {foreach from=$ResourceNames item=resourceName}
            {$resourceName}
			<br/>
        {/foreach}
    {else}
		<strong>Ресурс:</strong>
        {$ResourceName}
		<br/>
    {/if}
</p>

{if $ResourceImage}
	<div class="resource-image">
		<img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/>
	</div>
{/if}

{if count($RepeatRanges) gt 0}
	<br/>
	<strong>Бронирование происходит в следующие даты ({$RepeatRanges|default:array()|count}):</strong>
	<br/>
    {foreach from=$RepeatRanges item=date name=dates}
        {formatdate date=$date->GetBegin()}
        {if !$date->IsSameDate()} – {formatdate date=$date->GetEnd()}{/if}
		<br/>
    {/foreach}
{/if}

<p>
    {if !empty($CreatedBy)}
		<strong>Удалено пользователем:</strong>
        {$CreatedBy}
		<br/>
		<strong>Причина удаления:</strong> {$DeleteReason|nl2br}
    {/if}
</p>

<p><strong>Номер ссылки:</strong> {$ReferenceNumber}</p>

<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
