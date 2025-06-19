{include file='globalheader.tpl' InlineEdit=true DataTable=false}

<div id="page-manage-resource-types" class="admin-page">

	{include file='Admin/Resources/manage_resource_menu.tpl' ResourcePageTitleKey='ManageResourceTypes'}

	<div id="globalError" class="error" style="display:none"></div>

	<form id="addForm" ajaxAction="{ManageResourceTypesActions::Add}" class="form-inline" role="form" method="post">
		<div class="accordion">
			<div class="accordion-item shadow mb-3 panel-default" id="add-resource-type-panel">
				<h2 class="accordion-header">
					<button class="accordion-button" type="button" data-bs-toggle="collapse"
						data-bs-target="#collapse-add-contents" aria-expanded="true"
						aria-controls="collapse-add-contents">
						{translate key="AddResourceType"}
					</button>
				</h2>
				<div id="collapse-add-contents" class="accordion-collapse collapse show">
					<div class="accordion-body add-contents">
						<div id="addResults" class="error d-none"></div>
						<div class="d-flex align-items-center gap-2 mb-2">
							<label class="fw-bold" for="resourceTypeName">{translate key='Name'}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i></label>
							<div class="form-group">
								<input type="text" class="form-control required has-feedback" maxlength="85" required
									{formname key=RESOURCE_TYPE_NAME} id="resourceTypeName" />
							</div>
							<label class="fw-bold ms-3" for="resourceTypeDesc">{translate key='Description'}</label>
							<div class="form-group">
								<textarea class="form-control" rows="1" {formname key=RESOURCE_TYPE_DESCRIPTION}
									id="resourceTypeDesc"></textarea>
							</div>
						</div>
						<div class="accordion-footer border-top pt-3">
							<button type="button" class="btn btn-primary btn-sm save create">
								<i class="bi bi-check-circle"></i>
								{translate key='Create'}
							</button>
							<button type="reset" class="btn btn-outline-secondary btn-sm">{translate key=Reset}</button>
							{indicator}
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="card shadow">
		<div class="card-body">
			{assign var=tableId value=resourceTypes}
			<table class="table table-striped table-hover border-top" id="{$tableId}">
				<thead>
					<tr>
						<th>{translate key='Name'}</th>
						<th>{translate key='Description'}</th>
						<th class="action">{translate key='Actions'}</th>
						{if $AttributeList|default:array()|count > 0}
							<th>{translate key='More'}</th>
						{/if}
					</tr>
				</thead>
				<tbody>
					{foreach from=$ResourceTypes item=type}
						{assign var=id value=$type->Id()}
						<tr>
							<td>{$type->Name()}</td>
							<td>{$type->Description()|nl2br}</td>
							<td class="action">
								<a href="#" class="update edit link-primary"><span class="bi bi-pencil-square icon"></a> |
								<a href="#" class="update delete"><span
										class="bi bi-trash3-fill text-danger icon remove"></span></a>
								<input type="hidden" class="id" value="{$id}" />
							</td>
							{if $AttributeList|default:array()|count > 0}
								<td>
									{foreach from=$AttributeList item=attribute}
										{include file='Admin/InlineAttributeEdit.tpl' id=$id attribute=$attribute value=$type->GetAttributeValue($attribute->Id())}
									{/foreach}
								</td>
							{/if}
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
	</div>

	<input type="hidden" id="activeId" value="" />

	<div class="modal fade" id="editDialog" tabindex="-1" role="dialog" aria-labelledby="editDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="editForm" method="post" ajaxAction="{ManageResourceTypesActions::Update}" class="was-validated">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editDialogLabel">{translate key=Edit}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group has-feedback">
							<label for="editName" class="fw-bold">{translate key=Name}<i
									class="bi bi-asterisk text-danger align-top" style="font-size: 0.5rem;"></i></label>
							<input type="text" id="editName" class="form-control required" required="required"
								maxlength="85" {formname key=RESOURCE_TYPE_NAME} />
						</div>
						<div class="form-group">
							<label for="editDescription" class="fw-bold">{translate key='Description'}</label>
							<textarea class="form-control" rows="1" {formname key=RESOURCE_TYPE_DESCRIPTION}
								id="editDescription"></textarea>
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
			<form id="deleteForm" method="post" ajaxAction="{ManageResourceTypesActions::Delete}">
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

	{include file="javascript-includes.tpl" InlineEdit=true DataTable=true}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/resource-types.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}
	{datatable tableId=$tableId}
	<script type="text/javascript">
		function hidePopoversWhenClickAway() {
			$('body').on('click', function(e) {
				$('[rel="popover"]').each(function() {
					if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e
							.target).length === 0) {
						$(this).popover('hide');
					}
				});
			});
		}

		function setUpPopovers() {
			$('[rel="popover"]').popover({
				container: 'body',
				html: true,
				placement: 'top',
				content: function() {
					var popoverId = $(this).data('popover-content');
					return $(popoverId).html();
				}
			}).click(function(e) {
				e.preventDefault();
			}).on('show.bs.popover', function() {

			}).on('shown.bs.popover', function() {
				var trigger = $(this);
				var popover = trigger.data('bs.popover').tip();
				popover.find('.editable-cancel').click(function() {
					trigger.popover('hide');
				});
			});
		}

		function setUpEditables() {
			$.fn.editable.defaults.mode = 'popup';
			$.fn.editable.defaults.toggle = 'manual';
			$.fn.editable.defaults.emptyclass = '';
			$.fn.editable.defaults.params = function(params) {
				params.CSRF_TOKEN = $('#csrf_token').val();
				return params;
			};

			var updateUrl = '{$smarty.server.SCRIPT_NAME}?action=';

			$('.inlineAttribute').editable({
				url: updateUrl + '{ManageResourceTypesActions::ChangeAttribute}',
				emptytext: '-'
			});
		}

		$(document).ready(function() {
			setUpPopovers();
			hidePopoversWhenClickAway();
			setUpEditables();

			var opts = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				saveRedirect: '{$smarty.server.SCRIPT_NAME}'
			};

			var resourceTypes = new ResourceTypeManagement(opts);
			resourceTypes.init();

			{foreach from=$ResourceTypes item=type}
				resourceTypes.add({
					id:{$type->Id()},
					name: "{$type->Name()|escape:'javascript'}",
					description: "{$type->Description()|escape:'javascript'}"
				});
			{/foreach}
		});


		//$('#add-resource-type-panel').showHidePanel();
	</script>
</div>

{include file='globalfooter.tpl'}