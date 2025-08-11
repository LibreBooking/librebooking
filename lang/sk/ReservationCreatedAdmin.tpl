	Boli vytvorené tieto nové rezervácie:
	<br/>
	<br/>

    Užívateľ: {$UserName}<br/><br/>
	Nadpis: {$Title}<br/>
    Popis: {$Description|nl2br}<br/><br/>
	Začiatok: {formatdate date=$StartDate key=reservation_email}<br/>
	Koniec: {formatdate date=$EndDate key=reservation_email}<br/>
	{if $ResourceNames|default:array()|count > 1}
	Ihriská:<br/>
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}<br/>
		{/foreach}
		{else}
    Ihrisko: {$ResourceName}<br/>
	{/if}

	{if count($RepeatDates) gt 0}
		<br/>
		Boli rezervované všetky tieto termíny:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Príslušenstvo:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}
	{if $RequiresApproval}
		<br/>
		Jedna, alebo viac rezervácií si vyžaduje schválenie od administrátora. Do tej doby bude Vaša rezervácia v stave schvalovania.
	{/if}

	<br/>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Zobraziť rezerváciu v systéme</a> | <a href="{$ScriptUrl}">Prihlásiť sa do systému</a>

