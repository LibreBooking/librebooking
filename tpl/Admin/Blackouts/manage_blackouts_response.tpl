<div>
	{if $Successful}
		<h2>{translate key=$SuccessKey}</h2>
		<button class="reload btn btn-primary">{translate key="OK"}</button>
	{else}
		<h2>{translate key=$FailKey}</h2>
		<button class="unblock btn btn-primary">{translate key="OK"}</button>
	{/if}

	{if !empty($Message)}
		<h5>{$Message|escape:'html'}</h5>
	{/if}

	{if !empty($Blackouts)}
		<h5 class="mt-2">{translate key=BlackoutConflicts}</h5>
		<div class="table-responsive">
			<table class="table" id="blackoutConflictsTable">
				<thead>
					<tr>
						<th>{translate key='Title'}</th>
						<th>{translate key='BeginDate'}</th>
						<th>{translate key='EndDate'}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$Blackouts item=blackout}
						<tr>
							<td>{if !empty($blackout->Title)}{$blackout->Title|escape:'html'}{else}{translate key='NoTitleLabel'}{/if}
							</td>
							<td>{formatdate date=$blackout->StartDate timezone=$Timezone key=res_popup}</td>
							<td>{formatdate date=$blackout->EndDate timezone=$Timezone key=res_popup}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	{/if}

	{if !empty($Reservations)}
		<h5 class="mt-2">{translate key=ReservationConflicts}</h5>
		<div class="table-responsive">
			<table class="table" id="reservationTable">
				<thead>
					<tr>
						<th>{translate key='User'}</th>
						<th>{translate key='Title'}</th>
						<th>{translate key='Resource'}</th>
						<th>{translate key='BeginDate'}</th>
						<th>{translate key='EndDate'}</th>
						<th>{translate key='ReferenceNumber'}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$Reservations item=reservation}
						<tr class="editable" data-refnum="{$reservation->ReferenceNumber|escape:'html'}">
							<td>{$reservation->FirstName|escape:'html'} {$reservation->LastName|escape:'html'}</td>
							<td>
								<span class="reservationTitle" data-bs-custom-class="respopup-tooltip" data-bs-html="true">
									{if !empty($reservation->Title)}{$reservation->Title|escape:'html'}{else}{translate key='NoTitleLabel'}{/if}
								</span>
							</td>
							<td>{$reservation->ResourceName|escape:'html'}</td>
							<td>{formatdate date=$reservation->StartDate timezone=$Timezone key=res_popup}</td>
							<td>{formatdate date=$reservation->EndDate timezone=$Timezone key=res_popup}</td>
							<td class="referenceNumber">{$reservation->ReferenceNumber|escape:'html'}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	{/if}
</div>
