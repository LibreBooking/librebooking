{include file='globalheader.tpl'}

<div class="page-participation">
	{if !empty($result)}
		<div>{$result}</div>
	{/if}

	<div id="jsonResult" class="error "></div>

	<div id="participation-box" class="default-box card shadow">
		<div class="card-body">
			<h1 class="text-center border-bottom mb-3">{translate key=OpenInvitations}
				<span class="badge bg-primary">{$Reservations|default:array()|count}</span>
			</h1>

			<table class="table table-hover table-striped align-middle participation">
				<thead>
					<tr>
						<td>{translate key="ReservationTitle"}</td>
						<td>{translate key="Date"}</td>
						<td>{translate key="Owner"}</td>
						<td>{translate key="Resource"}</td>
						<td>{translate key="Accept"}/{translate key="Decline"}</td>
					</tr>
				</thead>
				<tbody>
					{foreach from=$Reservations item=reservation name=invitations}
						{assign var=referenceNumber value=$reservation->ReferenceNumber}
						<tr>
							<td>{$reservation->Title|default:{translate key="NoTitleLabel"}}
							</td>
							<td>
								<a href="{Pages::RESERVATION}?{QueryStringKeys::REFERENCE_NUMBER}={$referenceNumber}"
									class="reservation link-primary" referenceNumber="{$referenceNumber}">
									{formatdate date=$reservation->StartDate->ToTimezone($Timezone) key=dashboard}
									- {formatdate date=$reservation->EndDate->ToTimezone($Timezone) key=dashboard}</a>
							</td>
							<td>
								{$reservation->OwnerFirstName} {$reservation->OwnerLastName}
							</td>
							<td>
								{$reservation->ResourceName}
							</td>
							<td class="actions row{$smarty.foreach.invitations.index%2}">
								<input type="hidden" value="{$referenceNumber}" class="referenceNumber" />
								<button value="{InvitationAction::Accept}"
									class="btn btn-primary btn-sm participationAction"><i
										class="bi bi-check-circle-fill me-1"></i>{translate key="Accept"}</button>
								<button value="{InvitationAction::Decline}"
									class="btn btn-danger btn-sm participationAction"><i
										class="bi bi-x-circle-fill me-1"></i>{translate key="Decline"}</button>
							</td>
						</tr>
						{*<li class="actions row{$smarty.foreach.invitations.index%2}">
							<h3>{$reservation->Title}</h3>

							<h3><a href="{Pages::RESERVATION}?{QueryStringKeys::REFERENCE_NUMBER}={$referenceNumber}"
									class="reservation" referenceNumber="{$referenceNumber}">
									{formatdate date=$reservation->StartDate->ToTimezone($Timezone) key=dashboard}
									- {formatdate date=$reservation->EndDate->ToTimezone($Timezone) key=dashboard}</a>
							</h3>
							<input type="hidden" value="{$referenceNumber}" class="referenceNumber" />
							<button value="{InvitationAction::Accept}" class="btn btn-success participationAction"><i
									class="fa fa-check-circle"></i> {translate key="Accept"}</button>
							<button value="{InvitationAction::Decline}" class="btn btn-default participationAction"><i
									class="fa fa-times-circle"></i> {translate key="Decline"}</button>
						</li>*}
					{foreachelse}
						<tr class="no-data">
							<td colspan="5" class="noresults text-center fst-italic fs-5">{translate key='None'}</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>
	<div class="dialog" style="display:none;">

	</div>

	{include file="javascript-includes.tpl"}
	{jsfile src="reservationPopup.js"}
	{jsfile src="participation.js"}

	<script type="text/javascript">
		$(document).ready(function() {

			var participationOptions = {
				responseType: 'json'
			};

			var participation = new Participation(participationOptions);
			participation.initParticipation();
		});
	</script>

</div>
{include file='globalfooter.tpl'}