<div>
	{if $Successful}
		<h2>{translate key=$SuccessKey}</h2>
		<button class="reload btn btn-primary">{translate key="OK"}</button>
	{else}
		<h2>{translate key=$FailKey}</h2>
		<button class="unblock btn btn-primary">{translate key="OK"}</button>
	{/if}

	{if !empty($Message)}
		<h5>{$Message}</h5>
	{/if}

	{if !empty($Blackouts)}
		<h5>{translate key=BlackoutConflicts}</h5>
		{foreach from=$Blackouts item=blackout}
			{format_date date=$blackout->StartDate timezone=$Timezone}
		{/foreach}
	{/if}

	{if !empty($Reservations)}
		<h5>{translate key=ReservationConflicts}</h5>
		<table class="table" id="reservationTable" style="margin-left: auto; margin-right: auto;">
			<thead>
				<tr data-reservation-id="{$reservation->ReservationId}">
					<th>{translate key='User'}</th>
					<th>{translate key='Resource'}</th>
					<th>{translate key='BeginDate'}</th>
					<th>{translate key='EndDate'}</th>
					<th>{translate key='ReferenceNumber'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$Reservations item=reservation}
					<tr class="editable" data-reservation-id="{$reservation->ReferenceNumber}">
						<td>{$reservation->FirstName} {$reservation->LastName}</td>
						<td>{$reservation->ResourceName}</td>
						<td>{formatdate date=$reservation->StartDate timezone=$Timezone key=res_popup}</td>
						<td>{formatdate date=$reservation->EndDate timezone=$Timezone key=res_popup}</td>
						<td class="referenceNumber">{$reservation->ReferenceNumber}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	{/if}
</div>