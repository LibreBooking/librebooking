<p><strong>Подробности бронирования:</strong></p>

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
    {if $Resources|default:array()|count > 1}
        <strong>Ресурсы ({$Resources|default:array()|count}):</strong> <br />
    {else}
        <strong>Ресурс:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Расписание:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>ID ресурса:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Место:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Как связаться:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Описание:</strong> {$resource.description|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Примечания:</strong> {$resource.notes|sanitize_rich_text|url2link|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Администратор ресурса:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Характеристики ресурса:</strong><br/>
            <table cellpadding="4" cellspacing="0" border="1" style="border-collapse: collapse; margin-top: 4px;">
                {foreach from=$resource.attributeRows item=row}
                    <tr>
                        <th scope="row" valign="top" style="text-align: left;"><strong>{$row.label|escape}</strong></th>
                        <td valign="top">{$row.displayValue|escape|nl2br}</td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $resource.image}
            <div class="resource-image"><img alt="{$resource.name|escape}" src="{$ScriptUrl}/{$resource.image|escape}"/></div>
        {/if}

        {if !$smarty.foreach.resourceLoop.last}<br/>{/if}
    {/foreach}
</p>

{if $RequiresApproval}
	<p>* Как минимум один из забронированных ресурсов требует одобрения перед использованием. Бронирование будет ожидать одобрения. *</p>
{/if}

{if $CheckInEnabled}
	<p>
	Как минимум для одного из забронированных ресурсов нужно отметить приход и уход.
    {if $AutoReleaseMinutes != null}
		Если вы не отметите приход в течение {$AutoReleaseMinutes} мин. после начала, бронирование будет отменено.
    {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
    <br/>
    <strong>Бронирование повторяется в следующие даты ({$RepeatRanges|default:array()|count}):</strong>
    <br/>
	{foreach from=$RepeatRanges item=date name=dates}
	    {formatdate date=$date->GetBegin()}
	    {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
	    <br/>
	{/foreach}
{/if}

{if $Participants|default:array()|count >0}
    <br />
    <strong>Участники ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
    <br />
    {foreach from=$Participants item=user}
        {$user->FullName()}
        <br/>
    {/foreach}
{/if}

{if $ParticipatingGuests|default:array()|count >0}
    {foreach from=$ParticipatingGuests item=email}
        {$email}
        <br/>
    {/foreach}
{/if}

{if $Invitees|default:array()|count >0}
    <br />
    <strong>Приглашённые ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
    <br />
    {foreach from=$Invitees item=user}
        {$user->FullName()}
        <br/>
    {/foreach}
{/if}

{if $InvitedGuests|default:array()|count >0}
    {foreach from=$InvitedGuests item=email}
        {$email}
        <br/>
    {/foreach}
{/if}

{if $Accessories|default:array()|count > 0}
    <br />
       <strong>Оборудование ({$Accessories|default:array()|count}):</strong>
       <br />
    {foreach from=$Accessories item=accessory}
        ({$accessory->QuantityReserved}) {$accessory->Name}
        <br/>
    {/foreach}
{/if}

{if $CreditsCurrent > 0}
	<br/>
	Стоимость бронирования в кредитах: {$CreditsCurrent}.
    {if $CreditsCurrent != $CreditsTotal}
		Стоимость всей серии в кредитах: {$CreditsTotal}.
    {/if}
{/if}


{if !empty($CreatedBy)}
	<p><strong>Кем создано:</strong> {$CreatedBy}</p>
{/if}

{if !empty($ApprovedBy)}
	<p><strong>Кем одобрено:</strong> {$ApprovedBy}</p>
{/if}

<p><strong>Номер бронирования:</strong> {$ReferenceNumber}</p>

{if !$Deleted}
	<a href="{$ScriptUrl}/{$ReservationUrl}">Посмотреть бронирование</a>
	|
	<a href="{$ScriptUrl}/{$ICalUrl}">Добавить в календарь</a>
	|
	<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Добавить в Google</a>
	|
{/if}
<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
