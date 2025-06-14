{include file='globalheader.tpl'}

<div id="page-manage-resource-status" class="admin-page">

	{function name=displayReason}
		<div class="list-group-item list-group-item-action reason-item" reasonId="{$reason->Id()}">
			<div class="clearfix">
				<span class="float-start reason-description">{$reason->Description()}</span>
				<div class="float-end d-flex align-items-center gap-1">
					<a href="#" class="update edit link-primary"><i
							class="bi bi-pencil-square me-1"></i>{translate key=Edit}</a>
					<div class="vr"></div>
					<a href="#" class="update delete link-danger link-underline link-underline-opacity-0"><i
							class="bi bi-trash3-fill me-1"></i>{translate key=Delete}</a>
				</div>
			</div>
		</div>
	{/function}

	{include file='Admin/Resources/manage_resource_menu.tpl' ResourcePageTitleKey='ManageResourceStatus'}

	<div id="globalError" class="error" style="display:none"></div>

	<div class="accordion">
		<div>
			<div class="accordion-item shadow mb-3 resource-status-list" id="resource-status-list-available">
				<h2 class="accordion-header">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#panelAvailable" aria-expanded="true" aria-controls="panelAvailable">
						<i class="bi bi-check-circle-fill text-success me-1"></i>{translate key="Available"}
					</button>
				</h2>
				<div id="panelAvailable" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<a href="#" add-to="{ResourceStatus::AVAILABLE}" class="add-link btn btn-primary mb-2">
							<i class="bi bi-plus-circle-fill me-1 icon add"></i>{translate key="Add"}
						</a>
						<div class="list-group">
							{foreach from=$StatusReasons[{ResourceStatus::AVAILABLE}] item=reason}
								{displayReason reason=$reason}
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div>
			<div class="accordion-item shadow mb-3 resource-status-list" id="resource-status-list-unavailable">
				<h2 class="accordion-header">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#panelUnavailable" aria-expanded="true" aria-controls="panelUnavailable">
						<i class="bi bi-exclamation-circle-fill text-warning me-1"></i>{translate key="Unavailable"}
					</button>
				</h2>
				<div id="panelUnavailable" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<a href="#" add-to="{ResourceStatus::UNAVAILABLE}" class="add-link btn btn-primary mb-2"><i
								class="bi bi-plus-circle-fill me-1 icon add"></i>{translate key="Add"}
						</a>
						<div class="list-group">
							{foreach from=$StatusReasons[{ResourceStatus::UNAVAILABLE}] item=reason}
								{displayReason reason=$reason}
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		</div>

		<div>
			<div class="accordion-item shadow mb-3 resource-status-list" id="resource-status-list-hidden">
				<h2 class="accordion-header">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#panelHidden" aria-expanded="true" aria-controls="panelHidden">
						<i class="bi bi-x-circle-fill text-danger me-1"></i>{translate key="Hidden"}
					</button>
				</h2>
				<div id="panelHidden" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<a href="#" add-to="{ResourceStatus::HIDDEN}" class="add-link btn btn-primary mb-2">
							<i class="bi bi-plus-circle-fill me-1 icon add"></i>{translate key="Add"}
						</a>
						<div class="list-group">
							{foreach from=$StatusReasons[{ResourceStatus::HIDDEN}] item=reason}
								{displayReason reason=$reason}
							{/foreach}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" id="activeId" value="" />

	<div class="modal fade" id="addDialog" tabindex="-1" role="dialog" aria-labelledby="addDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="addForm" method="post" ajaxAction="{ManageResourceStatusActions::Add}" class="was-validated">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addDialogLabel">{translate key=Add}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label class="fw-bold" for="add-reason-description">{translate key=Name}</label><br />
							<input type="text" class="form-control required" required id="add-reason-description"
								{formname key=RESOURCE_STATUS_REASON} />
							<input type="hidden" id="add-reason-status" {formname key=RESOURCE_STATUS_ID} />
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{add_button}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="editDialog" tabindex="-1" role="dialog" aria-labelledby="editDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="editForm" method="post" ajaxAction="{ManageResourceStatusActions::Update}" class="was-validated">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editDialogLabel">{translate key=Edit}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label class="fw-bold" for="edit-reason-description">{translate key=Name}</label>
							<input type="text" class="form-control required" required id="edit-reason-description"
								{formname key=RESOURCE_STATUS_REASON} />
							<input type="hidden" id="add-reason-status" {formname key=RESOURCE_STATUS_ID} />
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteForm" method="post" ajaxAction="{ManageResourceStatusActions::Delete}">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteDialogLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>
						</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{delete_button}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	{csrf_token}
	{include file="javascript-includes.tpl"}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/resource-status.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		$(document).ready(function() {
			var opts = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				saveRedirect: '{$smarty.server.SCRIPT_NAME}'
			};

			var resourceStatus = new ResourceStatusManagement(opts);
			resourceStatus.init();

			/*$('#resource-status-list-available').showHidePanel();
			$('#resource-status-list-unavailable').showHidePanel();
			$('#resource-status-list-hidden').showHidePanel();*/
		})
	</script>

</div>

{include file='globalfooter.tpl'}