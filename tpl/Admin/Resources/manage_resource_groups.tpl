{include file='globalheader.tpl' cssFiles='assets/vendor/jquery-contextmenu/2.9.2/css/jquery.contextMenu.css,assets/vendor/jqtree/1.6.2/css/jqtree.css'}

<div id="page-manage-resource-groups" class="admin-page">

	{include file='Admin/Resources/manage_resource_menu.tpl' ResourcePageTitleKey='ManageResourceGroups'}

	<div id="globalError" class="alert alert-danger d-none"></div>

	<div id="manage-resource-groups-container bg-dark">
		<div class="card shadow mb-3">
			<div class="card-body">
				<div id="help" class="alert alert-info d-flex align-items-center" role="alert">
					<div>
						<div>{translate key=ResourceGroupHelp1}</div>
						<div>{translate key=ResourceGroupHelp2}</div>
						<div>{translate key=ResourceGroupHelp3}</div>
					</div>
					<div class="ms-auto">
						<button type="button" class="btn-close" data-bs-dismiss="alert"
							aria-label="{translate key=Close}">
						</button>
					</div>
				</div>
				<div id="new-group">
					<form method="post" id="addGroupForm" ajaxAction="{ManageResourceGroupsActions::AddGroup}">
						<div class="row">
							<div class="form-group col-sm-6 mx-auto">
								<label for="groupName" class="visually-hidden">{translate key=AddNewGroup}</label>
								<div class="input-group mb-3">
									<input type="text" name="{FormKeys::GROUP_NAME}"
										class="form-control new-group inline" size="30" id="groupName"
										placeholder="{translate key=AddNewGroup}" />
									<input type="hidden" name="{FormKeys::PARENT_ID}" />
									<a href="#" class="btn btn-primary" type="button" id="btnAddGroup">
										<i class="bi bi-plus-circle-fill icon add inline"></i>
										{translate key=Add}</a>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="d-flex justify-content-center gap-5">
					<div id="group-tree"></div>
					<div id="resource-list">
						<h4>{translate key=Resources}</h4>
						{foreach from=$Resources item=resource}
							<div class="resource-draggable" resource-name="{$resource->GetName()|escape:javascript}"
								resource-id="{$resource->GetId()}">{$resource->GetName()}</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<input type="hidden" id="activeId" value="" />

<div id="renameDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="renameGroupDialogLabel"
	aria-hidden="true">
	<form id="renameForm" method="post" ajaxAction="{ManageResourceGroupsActions::RenameGroup}" class="was-validated">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="renameGroupDialogLabel">{translate key=Rename}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="form-label fw-bold" for="editName">{translate key='Name'}</label>
						<input id="editName" type="text" class="form-control textbox required triggerSubmit" required
							maxlength="85" {formname key=GROUP_NAME} />
					</div>
				</div>
				<div class="modal-footer">
					{cancel_button}
					{update_button}
					{indicator}
				</div>
			</div>
		</div>
	</form>
</div>

<div id="deleteDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="deleteDialogLabel"
	aria-hidden="true">
	<form id="deleteForm" method="post" ajaxAction="{ManageResourceGroupsActions::DeleteGroup}">
		<div class="modal-dialog">
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
		</div>
	</form>
</div>

<div id="addChildDialog" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addGroupDialogLabel"
	aria-hidden="true">
	<form id="addChildForm" method="post" ajaxAction="{ManageResourceGroupsActions::AddGroup}" class="was-validated">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addGroupDialogLabel">{translate key=AddNewGroup}</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="childName" class="form-label fw-bold">{translate key='Name'}</label>
						<input id="childName" type="text" class="form-control required new-group" maxlength="85"
							{formname key=GROUP_NAME} required />
					</div>
				</div>
				<div class="modal-footer">
					{cancel_button}
					{add_button}
					<input type="hidden" id="groupParentId" name="{FormKeys::PARENT_ID}" />

					{indicator}
				</div>
			</div>
		</div>
	</form>
</div>

{csrf_token}

{include file="javascript-includes.tpl"}
{jsfile src="ajax-helpers.js"}
{jsfile src="admin/resource-groups.js"}
{vendor_js src="jquery-form/3.09/jquery.form-3.09.min.js"}
{vendor_js src="jqtree/1.6.2/js/tree.jquery.js"}
{vendor_js src="jquery-cookie/1.3.1/js/jquery.cookie.js"}
{vendor_js src="jquery-contextmenu/2.9.2/js/jquery.contextMenu.js"}

<script type="text/javascript">
	$(document).ready(function() {
		var actions = {
			addResource: '{ManageResourceGroupsActions::AddResource}',
			removeResource: '{ManageResourceGroupsActions::RemoveResource}',
			moveNode: '{ManageResourceGroupsActions::MoveNode}'
		};

		var groupOptions = {
			submitUrl: '{$smarty.server.SCRIPT_NAME}',
			actions: actions,
			renameText: '{translate key=Rename|escape:'javascript'}',
			addChildText: '{translate key=AddGroup|escape:'javascript'}',
			deleteText: '{translate key=Delete|escape:'javascript'}',
			exitText: '{translate key=Close|escape:'javascript'}'
		};

		var groupManagement = new ResourceGroupManagement(groupOptions);
		groupManagement.init({$ResourceGroups});

		$('#help-button').click(function(e) {
			$('#' + $(this).attr('help-ref')).dialog();
		});
	});
</script>
</div>

{include file='globalfooter.tpl'}