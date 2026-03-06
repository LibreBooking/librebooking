<p><strong>Πληροφορίες κράτησης:</strong></p>

<p>
	<strong>Χρήστης:</strong> {$UserName}<br/>
	{if !empty($CreatedBy)}
		<strong>Δημιουργήθηκε από:</strong>
		{$CreatedBy}
		<br/>
	{/if}
	<strong>Έναρξη:</strong> {formatdate date=$StartDate key=reservation_email}<br/>
	<strong>Λήξη:</strong> {formatdate date=$EndDate key=reservation_email}<br/>
	<strong>Τίτλος:</strong> {$Title}<br/>
	<strong>Περιγραφή:</strong> {$Description|nl2br}
    {if $Attributes|default:array()|count > 0}
		<br/>
	    {foreach from=$Attributes item=attribute}
			<div>{control type="AttributeControl" attribute=$attribute readonly=true}</div>
	    {/foreach}
	{/if}
</p>

<p>

    {if $Resources|default:array()|count > 1}
    <strong>Πόροι ({$Resources|default:array()|count}):</strong>
    <br/>
    {else}
    <strong>Πόρος:</strong><br/>
    {/if}
    {foreach from=$Resources item=resource name=resourceLoop}
        <strong>{$resource.name|escape}</strong><br/>
        {if $resource.scheduleName}<strong>Χρονοδιάγραμμα:</strong> {$resource.scheduleName|escape}<br/>{/if}
        <strong>Αναγνωριστικό πόρου:</strong> {$resource.id}<br/>
        {if $resource.location}<strong>Τοποθεσία:</strong> {$resource.location|escape}<br/>{/if}
        {if $resource.contact}<strong>Επικοινωνία:</strong> {$resource.contact|escape}<br/>{/if}
        {if $resource.description}<strong>Περιγραφή:</strong> {$resource.description|escape|nl2br}<br/>{/if}
        {if $resource.notes}<strong>Σημειώσεις:</strong> {$resource.notes|escape|nl2br}<br/>{/if}
        {if $resource.resourceAdministrator}<strong>Διαχειριστής πόρου:</strong> {$resource.resourceAdministrator|escape}<br/>{/if}

        {if $resource.attributeRows|default:array()|count > 0}
            <strong>Λεπτομέρειες πόρου:</strong><br/>
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
	<p>* Τουλάχιστον ένας από τους κρατημένους πόρους απαιτεί έγκριση πριν τη χρήση του. Παρακαλώ βεβαιωθείτε ότι η αίτηση για την κράτηση θα εγκριθεί ή απορριφθεί. *</p>
{/if}

{if $CheckInEnabled}
	<p>
		Τουλάχιστον ένας από τους κρατημένους πόρους απαιτεί από το χρήστη να κάνει check in και check out στην κράτηση.
        {if $AutoReleaseMinutes != null}
			Η κράτηση θα ακυρωθεί αυτόματα εκτός αν ο χρήστης δεν κάνει check in μέσα σε διάστημα {$AutoReleaseMinutes} λεπτών μετά την προγραμματισμένη έναρξη.
        {/if}
	</p>
{/if}

{if count($RepeatRanges) gt 0}
	<p>
		Η κράτηση θα ισχύει για τις εξής ημερομηνίες ({$RepeatRanges|default:array()|count}):
		<br/>
        {foreach from=$RepeatRanges item=date name=dates}
            {formatdate date=$date->GetBegin()}
            {if !$date->IsSameDate()} - {formatdate date=$date->GetEnd()}{/if}
			<br/>
        {/foreach}
	</p>
{/if}

{if $Participants|default:array()|count >0}
	<br/>
	<strong>Συμμετέχοντες ({$Participants|default:array()|count + $ParticipatingGuests|default:array()|count}):</strong>
	<br/>
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
	<br/>
	<strong>Προσκαλεσμένοι ({$Invitees|default:array()|count + $InvitedGuests|default:array()|count}):</strong>
	<br/>
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
	<br/>
	<strong>Εξοπλισμός ({$Accessories|default:array()|count}):</strong>
	<br/>
    {foreach from=$Accessories item=accessory}
		({$accessory->QuantityReserved}) {$accessory->Name}
		<br/>
    {/foreach}
{/if}

<p><strong>Αριθμός Αναφοράς:</strong> {$ReferenceNumber}</p>

<p>
	<a href="{$ScriptUrl}/{$ReservationUrl}">Δείτε την κράτηση</a> | <a href="{$ScriptUrl}">Κάνετε είσοδο στο {$AppTitle}</a>
</p>
