{include file='globalheader.tpl' Select2=true DataTable=true Trumbowyg=true}

<div id="page-manage-announcements" class="admin-page">
	<h1 class="border-bottom mb-3">{translate key=ManageAnnouncements}</h1>

	<form id="addForm" class="form-inline" role="form" method="post">
		<div class="accordion">
			<div class="accordion-item shadow mb-2 panel-default" id="add-announcement-panel">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed link-primary fw-bold" type="button"
						data-bs-toggle="collapse" data-bs-target="#add-announcement-content" aria-expanded="false"
						aria-controls="add-announcement-content">
						<i class="bi bi-plus-circle-fill me-1"></i>{translate key="AddAnnouncement"}
					</button>
				</h2>
				<div id="add-announcement-content" class="accordion-collapse collapse">
					<div class="accordion-body add-contents">
						<div class="row gy-2 mb-2">
							<div id="addResults" class="error d-none"></div>
							<div class="form-group col-12">
								<label class="fw-bold" for="addAnnouncement">{translate key='Announcement'}<i
										class="bi bi-asterisk text-danger align-top form-control-feedback"
										style="font-size: 0.5rem;"></i></label>
								<textarea class="form-control  has-feedback required" rows="1" style="width:100%"
									{formname key=ANNOUNCEMENT_TEXT} id="addAnnouncement"></textarea>
							</div>
							<div class="form-group col-sm-2 col-6">
								<label class="fw-bold" for="BeginDate">{translate key='BeginDate'}</label>
								<input type="date" id="BeginDate" class="form-control"
									{formname key=ANNOUNCEMENT_START} />
								<input type="hidden" id="formattedBeginDate" {formname key=ANNOUNCEMENT_START} />
							</div>
							<div class="form-group col-sm-2 col-6">
								<label class="fw-bold" for="EndDate">{translate key='EndDate'}</label>
								<input type="date" id="EndDate" class="form-control" {formname key=ANNOUNCEMENT_END} />
								<input type="hidden" id="formattedEndDate" {formname key=ANNOUNCEMENT_END} />
							</div>
							<div class="form-group col-sm-2 col-6">
								<label class="fw-bold" for="addPriority">{translate key='Priority'}</label>
								<input type="number" min="0" step="1" class="form-control"
									{formname key=ANNOUNCEMENT_PRIORITY} id="addPriority" />
							</div>
							<div class="form-group col-sm-3 col-6">
								<label class="fw-bold" for="addPage">{translate key='DisplayPage'}</label>
								<select id="addPage" class="form-select" {formname key=DISPLAY_PAGE}>
									<option value="1">{translate key=Dashboard}</option>
									<option value="5">{translate key=Login}</option>
								</select>
							</div>
							<div id="moreOptions">
								<a href="#" class="link-primary" data-bs-toggle="collapse"
									data-bs-target="#advancedAnnouncementOptions"><i
										class="bi bi-plus-circle-fill me-1"></i>{translate key=MoreOptions}</a>
								<div id="advancedAnnouncementOptions" class="collapse row gy-2">
									<div class="form-group col-12 col-md-6">
										<label for="announcementGroups"
											class="visually-hidden">{translate key=UsersInGroups}</label>
										<select id="announcementGroups" class="form-select" multiple="multiple"
											style="width:100%" {formname key=FormKeys::GROUP_ID multi=true}>
											{foreach from=$Groups item=group}
												<option value="{$group->Id}">{$group->Name}</option>
											{/foreach}
										</select>
									</div>
									<div class="form-group col-12 col-md-6">
										<label for="resourceGroups"
											class="visually-hidden">{translate key=UsersWithAccessToResources}</label>
										<select id="resourceGroups" class="form-select" multiple="multiple"
											style="width:100%" {formname key=RESOURCE_ID multi=true}>
											{foreach from=$Resources item=resource}
												<option value="{$resource->GetId()}">{$resource->GetName()}</option>
											{/foreach}
										</select>
									</div>
									<div class="form-group col-12">
										<div class="form-check no-padding-left">
											<input class="form-check-input" type="checkbox" id="sendAsEmail"
												{formname key=FormKeys::SEND_AS_EMAIL} />
											<label class="form-check-label"
												for="sendAsEmail">{translate key=SendAsEmail}</label>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="accordion-footer border-top pt-3">
							{add_button class="btn-sm"}
							{reset_button class="btn-sm"}
							{indicator}
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="card shadow">
		<div class="card-body">
			{assign var=tableId value=announcementList}
			<div class="table-responsive">
				<table class="table table-striped table-hover border-top w-100 align-middle" id="{$tableId}">
					<thead>
						<tr>
							<th>{translate key='Announcement'}</th>
							<th>{translate key='Priority'}</th>
							<th>{translate key='BeginDate'}</th>
							<th>{translate key='EndDate'}</th>
							<th>{translate key='Groups'}</th>
							<th>{translate key='Resources'}</th>
							<th>{translate key='DisplayPage'}</th>
							<th class="action">{translate key='Actions'}</th>
						</tr>
					</thead>
					<tbody>
						{foreach from=$announcements item=announcement}
							<tr data-announcement-id="{$announcement->Id()}">
								<td class="announcementText">{$announcement->Text()|unescape:'html'}</td>
								<td class="announcementPriority">{$announcement->Priority()}</td>
								<td class="announcementStart">
									{formatdate date=$announcement->Start()->ToTimezone($timezone)}
								</td>
								<td class="announcementEnd">{formatdate date=$announcement->End()->ToTimezone($timezone)}
								</td>
								<td class="announcementGroups">
									{foreach from=$announcement->GroupIds() item=groupId}{$Groups[$groupId]->Name}
									{/foreach}
								</td>
								<td class="announcementResources">
									{foreach from=$announcement->ResourceIds() item=resourceId}{$Resources[$resourceId]->GetName()}
									{/foreach}</td>
								<td class="announcementDisplayPage">
									{translate key={Pages::NameFromId($announcement->DisplayPage())}}
								</td>
								<td class="action announcementActions">
									<a href="#" title="{translate key=Edit}" class="update edit link-primary"><span
											class="bi bi-pencil-square icon"></a>
									<div class="vr"></div>
									{if $announcement->CanEmail()}
										<a href="#" title="{translate key=Email}" class="update sendEmail link-primary"><span
												class="bi bi-envelope icon"></a>
										<div class="vr"></div>
									{/if}
									<a href="#" title="{translate key=Delete}" class="update delete"><span
											class="bi bi-trash3-fill text-danger icon remove"></span></a>
								</td>
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
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
		aria-hidden="true" data-bs-focus="false" data-bs-backdrop="static">
		<div class="modal-dialog modal-lg">
			<form id="editForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editDialogLabel">{translate key=Edit}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body row gy-2">
						<div class="form-group mb-2">
							<label class="fw-bold" for="editText">{translate key=Announcement}<i
									class="bi bi-asterisk text-danger align-top form-control-feedback"
									style="font-size: 0.5rem;"></i></label>
							<textarea id="editText" class="form-control has-feedback required" rows="5"
								{formname key='ANNOUNCEMENT_TEXT'}></textarea>
						</div>
						<div class="form-group col-md-6 mb-2">
							<label class="fw-bold" for="editBegin">{translate key='BeginDate'}</label>
							<input type="date" id="editBegin" class="form-control" />
							<input type="hidden" id="formattedEditBegin" {formname key=ANNOUNCEMENT_START} />
						</div>
						<div class="form-group col-md-6 mb-2">
							<label class="fw-bold" for="editEnd">{translate key='EndDate'}</label>
							<input type="date" id="editEnd" class="form-control" />
							<input type="hidden" id="formattedEditEnd" {formname key=ANNOUNCEMENT_END} />
						</div>
						<div class="form-group mb-2">
							<label class="fw-bold" for="editPriority">{translate key='Priority'}</label>
							<input type="number" min="0" step="1" id="editPriority" class="form-control"
								{formname key=ANNOUNCEMENT_PRIORITY} />
						</div>
						<div class="form-group mb-2" id="editUserGroupsDiv">
							<label for="editUserGroups" class="visually-hidden">{translate key=UsersInGroups}</label>
							<select id="editUserGroups" class="form-select" multiple="multiple"
								{formname key=FormKeys::GROUP_ID multi=true} style="width: 100%;">
								{foreach from=$Groups item=group}
									<option value="{$group->Id}">{$group->Name}</option>
								{/foreach}
							</select>
						</div>
						<div class="form-group mb-2" id="editResourceGroupsDiv">
							<label for="editResourceGroups"
								class="visually-hidden">{translate key=UsersWithAccessToResources}</label>
							<select id="editResourceGroups" class="form-select" multiple="multiple"
								{formname key=RESOURCE_ID multi=true} style="width: 100%;">
								{foreach from=$Resources item=resource}
									<option value="{$resource->GetId()}">{$resource->GetName()}</option>
								{/foreach}
							</select>
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

	<div class="modal fade" id="emailDialog" tabindex="-1" role="dialog" aria-labelledby="emailDialogLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="emailForm" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="emailDialogLabel">{translate key=SendAsEmail}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-info"><span id="emailCount" class="fw-bold"></span>
							{translate key=AnnouncementEmailNotice}</div>
					</div>
					<div class="modal-footer">
						{cancel_button}
						{update_button key=SendAsEmail}
						{indicator}
					</div>
				</div>
			</form>
		</div>
	</div>

	{include file="javascript-includes.tpl" Select2=true DataTable=true Trumbowyg=true Resizimg=true}
	{datatable tableId={$tableId}}
	{control type="DatePickerSetupControl" ControlId="BeginDate" AltId="formattedBeginDate"}
	{control type="DatePickerSetupControl" ControlId="EndDate" AltId="formattedEndDate"}
	{control type="DatePickerSetupControl" ControlId="editBegin" AltId="formattedEditBegin"}
	{control type="DatePickerSetupControl" ControlId="editEnd" AltId="formattedEditEnd"}

	{csrf_token}

	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/announcement.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		$(document).ready(function() {
			const defaultTrumbowygOptions = {
				btns: [
					['viewHTML'],
					['undo', 'redo'],
					['formatting'],
					['strong', 'em', 'del'],
					['link'],
					['insertImage'],
					['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
					['unorderedList', 'orderedList']
				],
				tagsToRemove: ['script', 'link'],
				removeformatPasted: true,
				urlProtocol: true
			};

			$('#addAnnouncement').trumbowyg(defaultTrumbowygOptions);

			$(document).on('shown.bs.modal', '#editDialog', function() {
				// Obtaining and sanitizing content
				const rawContent = $('#editText').val();
				const sanitizedHtml = DOMPurify.sanitize(rawContent);

				// Initialize editor
				$('#editText').trumbowyg(defaultTrumbowygOptions)
					.trumbowyg('html', sanitizedHtml);
			});

			$(document).on('hidden.bs.modal', '#editDialog', function() {
				if ($('#editText').data('trumbowyg')) {
					$('#editText').trumbowyg('destroy');
				}
			});

			var actions = {
				add: '{ManageAnnouncementsActions::Add}',
				edit: '{ManageAnnouncementsActions::Change}',
				deleteAnnouncement: '{ManageAnnouncementsActions::Delete}',
				email: '{ManageAnnouncementsActions::Email}'
			};

			var accessoryOptions = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				saveRedirect: '{$smarty.server.SCRIPT_NAME}',
				getEmailCountUrl: '{$smarty.server.SCRIPT_NAME}?dr=emailCount',
				actions: actions
			};

			var announcementManagement = new AnnouncementManagement(accessoryOptions);
			announcementManagement.init();

			{foreach from=$announcements item=announcement}
				announcementManagement.addAnnouncement(
					'{$announcement->Id()}',
					DOMPurify.sanitize('{$announcement->Text()|escape:"quotes"|regex_replace:"/[\n]/":"\\n"}'),
					'{formatdate date=$announcement->Start()->ToTimezone($timezone)}',
					'{formatdate date=$announcement->End()->ToTimezone($timezone)}',
					'{$announcement->Priority()}',
					[{foreach from=$announcement->GroupIds() item=id}{$id},{/foreach}],
					[{foreach from=$announcement->ResourceIds() item=id}{$id},{/foreach}],
					{$announcement->DisplayPage()}
				);
			{/foreach}


			$('#announcementGroups').select2({
				placeholder: '{translate key=UsersInGroups}'
			});

			$('#editUserGroups').select2({
				placeholder: '{translate key=UsersInGroups}',
				dropdownParent: $('#editDialog')
			});

			$('#resourceGroups').select2({
				placeholder: '{translate key=UsersWithAccessToResources}'
			});

			$('#editResourceGroups').select2({
				placeholder: '{translate key=UsersWithAccessToResources}',
				dropdownParent: $('#editDialog')
			});
		});
	</script>
</div>
{include file='globalfooter.tpl'}