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
    <strong>Ресурсы ({$ResourceNames|default:array()|count}):</strong> <br />
    {foreach from=$ResourceNames item=resourceName}
        {$resourceName}<br/>
    {/foreach}
{else}
    <strong>Ресурс:</strong> {$ResourceName}<br/>
{/if}
</p>

{if $ResourceImage}
    <div class="resource-image">
        <img alt="{$ResourceName|escape}" src="{$ScriptUrl}/{$ResourceImage}"/>
    </div>
{/if}

{if $RequiresApproval}
	<p>* По крайней мере один из забронированных ресурсов требует подтверждения перед использованием. Бронирование будет находиться в ожидании до его подтверждения. *</p>
{/if}

{if $CheckInEnabled}
	<p>
	По крайней мере один из забронированных ресурсов требует отметки о начале и окончании использования.
    {if $AutoReleaseMinutes != null}
		Бронирование будет отменено, если вы не отметитесь в течение {$AutoReleaseMinutes} минут после запланированного времени начала.
    {/if}
	</p>
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
    <strong>Аксессуары ({$Accessories|default:array()|count}):</strong>
    <br />
    {foreach from=$Accessories item=accessory}
        ({$accessory->QuantityReserved}) {$accessory->Name}
        <br/>
    {/foreach}
{/if}

{if $CreditsCurrent > 0}
	<br/>
	Стоимость бронирования: {$CreditsCurrent} кредитов.
    {if $CreditsCurrent != $CreditsTotal}
		Стоимость всей серии бронирований: {$CreditsTotal} кредитов.
    {/if}
{/if}

{if !empty($CreatedBy)}
	<p><strong>Создано пользователем:</strong> {$CreatedBy}</p>
{/if}

{if !empty($ApprovedBy)}
	<p><strong>Подтверждено пользователем:</strong> {$ApprovedBy}</p>
{/if}

<p><strong>Номер ссылки:</strong> {$ReferenceNumber}</p>

{if !$Deleted}
	<a href="{$ScriptUrl}/{$ReservationUrl}">Посмотреть бронирование</a>
	|
	<a href="{$ScriptUrl}/{$ICalUrl}">Добавить в календарь</a>
	|
	<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Добавить в Google Календарь</a>
	|
{/if}
<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
