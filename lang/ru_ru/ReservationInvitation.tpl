{if $Deleted}
    <p>{$UserName} удалил(а) бронирование</p>
    {else}
    <p>{$UserName} добавил(а) вас в бронирование</p>
{/if}

{if !empty($DeleteReason)}
    <p><strong>Причина удаления:</strong> {$DeleteReason|nl2br}</p>
{/if}

<p><strong>Детали бронирования:</strong></p>

<p>
    <strong>Начало:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
    <strong>Окончание:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
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

{if $RequiresApproval && !$Deleted}
    <p>* Один или несколько забронированных ресурсов требуют подтверждения перед использованием. Бронирование будет находиться в ожидании до его подтверждения. *</p>
{/if}

<p>
    <strong>Название:</strong> {$Title}<br/>
    <strong>Описание:</strong> {$Description|nl2br}
</p>

{if count($RepeatRanges) gt 0}
    <br/>
    <strong>Бронирование происходит в следующие даты ({$RepeatRanges|default:array()|count}):</strong>
    <br/>
{/if}

{foreach from=$RepeatRanges item=date name=dates}
    {formatdate date=$date->GetBegin()}
    {if !$date->IsSameDate()} – {formatdate date=$date->GetEnd()}{/if}
    <br/>
{/foreach}

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

{if !$Deleted && !$Updated}
<p>
    <strong>Участвуете?</strong>
    <a href="{$ScriptUrl}/{$AcceptUrl}">Да</a>
    <a href="{$ScriptUrl}/{$DeclineUrl}">Нет</a>
</p>
{/if}

{if !$Deleted}
<a href="{$ScriptUrl}/{$ReservationUrl}">Посмотреть бронирование</a> |
<a href="{$ScriptUrl}/{$ICalUrl}">Добавить в календарь</a> |
<a href="{$GoogleCalendarUrl}" target="_blank" rel="nofollow">Добавить в Google Календарь</a> |
{/if}
<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
