	Administrátorem byly smazány tyto rezervace:
	<br/>
	<br/>

	Uživatel: {$UserName}<br/>
	Začátek: {formatdate date=$StartDate key=reservation_email}<br/>
	Konec: {formatdate date=$EndDate key=reservation_email}<br/>
	{if $ResourceNames|default:array()|count > 1}
	Zdroje:<br/>
		{foreach from=$ResourceNames item=resourceName}
			{$resourceName}<br/>
		{/foreach}
		{else}
    Zdroj: {$ResourceName}<br/>
	{/if}

	{if $ResourceImage}
		<div class="resource-image"><img src="{$ScriptUrl}/{$ResourceImage}"/></div>
	{/if}

	Nadpis: {$Title}<br/>
	Popis: {$Description|nl2br}<br/>
{$DeleteReason|nl2br}<br/>



	{if count($RepeatDates) gt 0}
		<br/>
		Došlo ke smazání všech těchto rezervovaných termínů:
		<br/>
	{/if}

	{foreach from=$RepeatDates item=date name=dates}
		{formatdate date=$date}<br/>
	{/foreach}

	{if $Accessories|default:array()|count > 0}
		<br/>Příslušenství:<br/>
		{foreach from=$Accessories item=accessory}
			({$accessory->QuantityReserved}) {$accessory->Name}<br/>
		{/foreach}
	{/if}

	<br/>
        <br/>
	<a href="{$ScriptUrl}">Přihlásit se do rezervačního systému</a>
