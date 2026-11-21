<p>Вы не отметили приход на бронирование.</p>
<p><strong>Подробности бронирования:</strong></p>
<p>
	<strong>Начало:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Окончание:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Ресурс:</strong> {$ResourceName}<br/>
	<strong>Название:</strong> {$Title}<br/>
	<strong>Описание:</strong> {$Description|nl2br}
</p>

{if $IsAutoRelease}
	<p>Если вы не отметите приход, бронирование будет автоматически отменено {formatdate date=$AutoReleaseTime key=reservation_email}</p>
{/if}

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Посмотреть бронирование</a> |
	<a href="{$ScriptUrl}">Войти в {$AppTitle}</a>
</p>
