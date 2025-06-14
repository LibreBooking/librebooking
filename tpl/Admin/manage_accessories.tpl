{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-accessories" class="admin-page">
	<h1 class="border-bottom mb-3">{translate key=ManageAccessories}</h1>

	<div class="accordion" id="accordionPanelsStayOpenExample">
		<form id="addForm" class="form-inline" role="form" method="post">
			<div class="accordion-item shadow mb-2 panel-default" id="add-accessory-panel">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed link-primary fw-bold" type="button"
						data-bs-toggle="collapse" data-bs-target="#AddAccessoryContent" aria-expanded="false"
						aria-controls="AddAccessoryContent">
						<i class="bi bi-plus-circle-fill me-1"></i>{translate key="AddAccessory"}
					</button>
				</h2>
				<div id="AddAccessoryContent" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<div class="row gy-2 mb-2">
							<div class="col-5">
								<div class="form-group d-flex align-items-center flex-wrap gap-3">
									<label class="fw-bold" for="accessoryName">{translate key=AccessoryName}<i
											class="bi bi-asterisk text-danger align-top form-control-feedback"
											data-bv-icon-for="accessoryName" style="font-size: 0.5rem;"></i></label>
									<input {formname key=ACCESSORY_NAME} type="text" autofocus id="accessoryName"
										required class="form-control has-feedback required w-auto" />

								</div>
							</div>
							<div class="col-7">
								<div class="form-group d-flex align-items-center flex-wrap gap-3">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" id="chkUnlimitedAdd"
											class="unlimited" name="chkUnlimited" checked="checked" />
										<label class="form-check-label" for="chkUnlimitedAdd">
											{translate key=Unlimited}</label>
									</div>

									<label class="fw-bold" for="addQuantity">{translate key='QuantityAvailable'}</label>
									<input type="number" id="addQuantity" class="form-control w-auto" min="0"
										disabled="disabled" {formname key=ACCESSORY_QUANTITY_AVAILABLE} />
								</div>
							</div>
						</div>
						<div class="accordion-footer border-top pt-3">
							{add_button class="btn-sm"}
							{reset_button class="btn-sm"}
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="card shadow">
		<div class="card-body">
			{assign var=tableId value=accessoriesTable}
			<table class="table table-striped table-hover border-top" id="{$tableId}">
				<thead>
					<tr>
						<th>{translate key='AccessoryName'}</th>
						<th>{translate key='QuantityAvailable'}</th>
						<th>{translate key='Resources'}</th>
						<th class="action">{translate key='Actions'}</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$accessories item=accessory}
						<tr data-accessory-id="{$accessory->Id}">
							<td>{$accessory->Name}</td>
							<td>{$accessory->QuantityAvailable|default:'&infin;'}</td>
							<td>
								<a href="#"
									class="update resources link-primary">{if $accessory->AssociatedResources == 0}{translate key=All}{else}{$accessory->AssociatedResources}{/if}</a>
							</td>
							<td class="action d-flex align-items-center gap-2">
								<a href="#" class="update edit link-primary">
									<span class="visually-hidden">{translate key=Edit}</span>
									<span class="bi bi-pencil-square icon"></a>
								<div class="vr"></div>
								<a href="#" class="update delete link-primary">
									<span class="visually-hidden">{translate key=Delete}</span>
									<span class="bi bi-trash3-fill text-danger icon remove"></span></a>
							</td>
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>

	<input type="hidden" id="activeId" />

	<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteDialogLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-warning">
							<div>{translate key=DeleteWarning}</div>
							<div>{translate key=DeleteAccessoryWarning}</div>
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

	<div class="modal fade" id="editDialog" tabindex="-1" role="dialog" aria-labelledby="editDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="editForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editDialogLabel">{translate key=Edit}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group has-feedback mb-2">
							<label class="fw-bold" for="editName">{translate key=AccessoryName}<i
									class="bi bi-asterisk text-danger align-top form-control-feedback"
									data-bv-icon-for="editName" style="font-size: 0.5rem;"></i></label>
							<input id="editName" type="text" class="form-control has-feedback required" autofocus
								maxlength="85" {formname key=ACCESSORY_NAME} />
						</div>
						<div class="form-group">
							<label class="fw-bold" for="editQuantity">{translate key='QuantityAvailable'}</label>
							<div class="d-flex align-items-center flex-wrap gap-3">
								<input id="editQuantity" type="number" min="0" class="form-control w-auto"
									disabled="disabled" {formname key=ACCESSORY_QUANTITY_AVAILABLE} />

								<div class="form-check">
									<input class="form-check-input" type="checkbox" id="chkUnlimitedEdit"
										class="unlimited" name="chkUnlimited" checked="checked" />
									<label class="form-check-label"
										for="chkUnlimitedEdit">{translate key=Unlimited}</label>
								</div>
							</div>
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


	<div class="modal fade" id="accessoryResourcesDialog" tabindex="-1" role="dialog"
		aria-labelledby="resourcesDialogLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="resourcesDialogLabel">{translate key=Resources}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<form id="accessoryResourcesForm" role="form"
						ajaxAction="{ManageAccessoriesActions::ChangeAccessoryResource}" method="post">
						<table class="table table-striped table-hover border-top">
							<tbody>
								{foreach from=$resources item=resource}
									{assign var="resourceId" value="{$resource->GetId()}"}
									<tr>
										<td>
											<div resource-id="{$resourceId}">
												<div class="form-check">
													<input id="accessoryResource{$resourceId}" type="checkbox"
														data-type="resource-id" class="resourceCheckbox form-check-input"
														name="{FormKeys::ACCESSORY_RESOURCE}[{$resource->GetId()}]"
														value="{$resource->GetId()}">
													<label class="form-check-label" for="accessoryResource{$resourceId}">
														{$resource->GetName()}
													</label>
												</div>
												<div class="quantities mb-2 ms-4 collapse"
													id="quantitiesaccessoryResource{$resourceId}">
													<label class="fw-bold">{translate key=MinimumQuantity}
														<input type="number" min="0" data-type="min-quantity"
															class="form-control form-control-sm" size="4" maxlength="4"
															name="{FormKeys::ACCESSORY_MIN_QUANTITY}[{$resource->GetId()}]">
													</label>
													<label class="fw-bold">{translate key=MaximumQuantity}
														<input type="number" min="0" data-type="max-quantity"
															class="form-control form-control-sm" size="4" maxlength="4"
															name="{FormKeys::ACCESSORY_MAX_QUANTITY}[{$resource->GetId()}]">
													</label>
												</div>
											</div>
										</td>
									</tr>
								{/foreach}
							</tbody>
						</table>
					</form>
				</div>
				<div class="modal-footer">
					{cancel_button}
					{update_button form="accessoryResourcesForm" submit="true"}
					{indicator}
				</div>
			</div>
		</div>
	</div>


	{csrf_token}

	{include file="javascript-includes.tpl" DataTable=true}
	{datatable tableId=$tableId}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/accessory.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		$(document).ready(function() {

			var actions = {
				add: '{ManageAccessoriesActions::Add}',
				edit: '{ManageAccessoriesActions::Change}',
				deleteAccessory: '{ManageAccessoriesActions::Delete}'
			};

			var accessoryOptions = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				saveRedirect: '{$smarty.server.SCRIPT_NAME}',
				actions: actions
			};

			var accessoryManagement = new AccessoryManagement(accessoryOptions);
			accessoryManagement.init();

			{foreach from=$accessories item=accessory}
				accessoryManagement.addAccessory('{$accessory->Id}', '{$accessory->Name|escape:javascript}', '{$accessory->QuantityAvailable}');
			{/foreach}

			//$('#add-accessory-panel').showHidePanel();
		});
	</script>
	<script>
		$(document).ready(function() {
			$('.resourceCheckbox').on('change', function() {
				var collapseTarget = $('#quantitiesaccessoryResource' + $(this).val());

				if ($(this).is(':checked')) {
					collapseTarget.collapse('show');
				} else {
					collapseTarget.collapse('hide');
				}
			});
		});
	</script>
</div>
{include file='globalfooter.tpl'}