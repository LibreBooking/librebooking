{include file='globalheader.tpl' DataTable=true}

<div id="page-manage-attributes" class="admin-page">
	<h1 class="border-bottom mb-3">{translate key=CustomAttributes}</h1>

	<div id="customAttributeHeader" class="card shadow mb-3">
		<div class="card-body">
			<label class="fw-bold" for="attributeCategory">{translate key=Category}: </label>
			<div class="input-group">
				<select id="attributeCategory" class="inline form-select">
					<option value="{CustomAttributeCategory::RESERVATION}">{translate key=CategoryReservation}</option>
					<option value="{CustomAttributeCategory::USER}">{translate key=User}</option>
					<option value="{CustomAttributeCategory::RESOURCE}">{translate key=Resource}</option>
					<option value="{CustomAttributeCategory::RESOURCE_TYPE}">{translate key=ResourceType}</option>
				</select>
				<a href="#" id="addAttributeButton" class="btn btn-primary"><i
						class="bi bi-plus-circle-fill me-1 icon add"></i>{translate key=AddAttribute}</a>
			</div>
		</div>
	</div>

	<div class="modal fade" id="addAttributeDialog" tabindex="-1" role="dialog" aria-labelledby="addLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="addAttributeForm" ajaxAction="{ManageAttributesActions::AddAttribute}" method="post">
				<input type="hidden" {formname key=ATTRIBUTE_CATEGORY} id="addCategory" value="" />

				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="addLabel">{translate key=AddAttribute}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group mb-2">
							<label class="fw-bold" for="attributeType">{translate key=Type}</label>
							<select {formname key=ATTRIBUTE_TYPE} id="attributeType" class="form-select">
								<option value="{CustomAttributeTypes::SINGLE_LINE_TEXTBOX}">
									{translate key=$Types[CustomAttributeTypes::SINGLE_LINE_TEXTBOX]}</option>
								<option value="{CustomAttributeTypes::MULTI_LINE_TEXTBOX}">
									{translate key=$Types[CustomAttributeTypes::MULTI_LINE_TEXTBOX]}</option>
								<option value="{CustomAttributeTypes::SELECT_LIST}">
									{translate key=$Types[CustomAttributeTypes::SELECT_LIST]}</option>
								<option value="{CustomAttributeTypes::CHECKBOX}">
									{translate key=$Types[CustomAttributeTypes::CHECKBOX]}</option>
								<option value="{CustomAttributeTypes::DATETIME}">
									{translate key=$Types[CustomAttributeTypes::DATETIME]}</option>
							</select>
						</div>
						<div class="textBoxOptions">
							<div class="attributeLabel form-group mb-2">
								<label class="fw-bold" for="ATTRIBUTE_LABEL">{translate key=DisplayLabel}<i
										class="bi bi-asterisk text-danger align-top form-control-feedback"
										style="font-size: 0.5rem;"></i></label>
								{textbox name=ATTRIBUTE_LABEL class="required has-feedback"}
							</div>

							<div class="attributeValidationExpression form-group mb-2">
								<label class="fw-bold"
									for="ATTRIBUTE_VALIDATION_EXPRESSION">{translate key=ValidationExpression}</label>
								{textbox name=ATTRIBUTE_VALIDATION_EXPRESSION}
							</div>

							<div class="attributePossibleValues form-group mb-2" style="display:none">
								<label for="ATTRIBUTE_POSSIBLE_VALUES">{translate key=PossibleValues} <span
										class="note fw-bold">({translate key=CommaSeparated})</span></label>
								{textbox name=ATTRIBUTE_POSSIBLE_VALUES class="required has-feedback"}
							</div>

							<div class="attributeSortOrder form-group mb-2">
								<label class="fw-bold" for="ATTRIBUTE_SORT_ORDER">{translate key=SortOrder}</label>
								<input type="number" class="form-control" min="0" {formname key=ATTRIBUTE_SORT_ORDER}
									maxlength=3 id="ATTRIBUTE_SORT_ORDER" />
							</div>

							<div class="attributeUnique form-group">
								<label for="addAttributeEntityId">{translate key=AppliesTo}</label>
								<a href="#" id="appliesTo" class="link-primary">{translate key=All}</a>
								<div class="appliesToId" id="addAttributeEntityId" style="display:none;"></div>
							</div>

							<div class="attributeRequired form-group">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" {formname key=ATTRIBUTE_IS_REQUIRED}
										id="attributeRequired" />
									<label class="form-check-label"
										for="attributeRequired">{translate key=Required}</label>
								</div>
							</div>

							<div class="attributeAdminOnly form-group">
								<div class="form-check">
									<input class="form-check-input" type="checkbox"
										{formname key=ATTRIBUTE_IS_ADMIN_ONLY} id="ATTRIBUTE_IS_ADMIN_ONLY" />
									<label class="form-check-label"
										for="ATTRIBUTE_IS_ADMIN_ONLY">{translate key=AdminOnly}</label>
								</div>
							</div>
							<div class="attributeIsPrivate form-group">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" {formname key=ATTRIBUTE_IS_PRIVATE}
										id='attributePrivate' />
									<label class="form-check-label"
										for="attributePrivate">{translate key=Private}</label>
								</div>
							</div>

							<div class="secondaryEntities d-none form-group">
								<div class="form-check">
									<input type="checkbox" class="limitScope form-check-input"
										{formname key=ATTRIBUTE_LIMIT_SCOPE} id="attributeLimitScope" />
									<label class="form-check-label"
										for="attributeLimitScope">{translate key=LimitAttributeScope}</label>
								</div>
							</div>
							<div class="attributeSecondary d-none form-group">
								<label for="attributeSecondaryCategory"
									class="visually-hidden">{translate key=Category}</label>
								<select class="secondaryAttributeCategory form-select"
									{formname key=ATTRIBUTE_SECONDARY_CATEGORY} id="attributeSecondaryCategory">
									<option value="{CustomAttributeCategory::USER}">{translate key=User}</option>
									<option value="{CustomAttributeCategory::RESOURCE}">{translate key=Resource}
									</option>
									<option value="{CustomAttributeCategory::RESOURCE_TYPE}">
										{translate key=ResourceType}</option>
								</select>
							</div>
							<div class="attributeSecondary d-none form-group">
								<label for="attributeSecondaryEntityDescription">{translate key=CollectFor}</label>
								<a href="#" class="secondaryPrompt link-primary"
									id="attributeSecondaryEntityDescription">{translate key=All}</a>

							</div>
						</div>

						<div id="entityChoices"></div>

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

	<div class="modal fade" id="editAttributeDialog" tabindex="-1" role="dialog" aria-labelledby="editLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="editAttributeForm" ajaxAction="{ManageAttributesActions::UpdateAttribute}" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="editLabel">{translate key=EditAttribute}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="form-group mb-2">
							<label class="fw-bold">{translate key=Type}</label>
							<span class='editAttributeType'
								id="editType{CustomAttributeTypes::SINGLE_LINE_TEXTBOX}">{translate key=$Types[CustomAttributeTypes::SINGLE_LINE_TEXTBOX]}</span>
							<span class='editAttributeType'
								id="editType{CustomAttributeTypes::MULTI_LINE_TEXTBOX}">{translate key=$Types[CustomAttributeTypes::MULTI_LINE_TEXTBOX]}</span>
							<span class='editAttributeType'
								id="editType{CustomAttributeTypes::SELECT_LIST}">{translate key=$Types[CustomAttributeTypes::SELECT_LIST]}</span>
							<span class='editAttributeType'
								id="editType{CustomAttributeTypes::CHECKBOX}">{translate key=$Types[CustomAttributeTypes::CHECKBOX]}</span>
						</div>
						<div class="textBoxOptions">
							<div class="form-group attributeLabel mb-2">
								<label class="fw-bold" for="editAttributeLabel">{translate key=DisplayLabel}<i
										class="bi bi-asterisk text-danger align-top form-control-feedback"
										style="font-size: 0.5rem;"></i></label>
								{textbox name=ATTRIBUTE_LABEL class="required has-feedback" id='editAttributeLabel'}
							</div>

							<div class="form-group attributeValidationExpression">
								<label class="fw-bold"
									for="editAttributeRegex">{translate key=ValidationExpression}</label>
								{textbox name=ATTRIBUTE_VALIDATION_EXPRESSION id='editAttributeRegex'}
							</div>

							<div class="form-group attributePossibleValues has-feedback" style="display:none">
								<label for="editAttributePossibleValues">{translate key=PossibleValues} <span
										class="note">({translate key=CommaSeparated}
										)</span></label>
								{textbox name=ATTRIBUTE_POSSIBLE_VALUES class="required" id="editAttributePossibleValues"}
							</div>

							<div class="form-group attributeSortOrder mb-2">
								<label class="fw-bold" for="editAttributeSortOrder">{translate key=SortOrder}</label>
								<input type="number" class="form-control" min="0" {formname key=ATTRIBUTE_SORT_ORDER}
									id="editAttributeSortOrder" />
							</div>

							<div class="form-group attributeUnique">
								<label for="editAttributeEntityId">{translate key=AppliesTo}</label>
								<a href="#" id="editAppliesTo" class="link-primary">{translate key=All}</a>
								<div class="appliesToId" id='editAttributeEntityId' style="display:none;"></div>
							</div>

							<div class="form-group attributeRequired">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" {formname key=ATTRIBUTE_IS_REQUIRED}
										id='editAttributeRequired' />
									<label class="form-check-label"
										for="editAttributeRequired">{translate key=Required}</label>
								</div>
							</div>

							<div class="form-group attributeAdminOnly">
								<div class="form-check">
									<input class="form-check-input" type="checkbox"
										{formname key=ATTRIBUTE_IS_ADMIN_ONLY} id="editAttributeAdminOnly" />
									<label class="form-check-label"
										for="editAttributeAdminOnly">{translate key=AdminOnly}</label>
								</div>
							</div>

							<div class="form-group attributeIsPrivate">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" {formname key=ATTRIBUTE_IS_PRIVATE}
										id='editAttributePrivate' />
									<label class="form-check-label"
										for="editAttributePrivate">{translate key=Private}</label>
								</div>
							</div>

							<div class="form-group secondaryEntities d-none">
								<div class="form-check">
									<input type="checkbox" class="limitScope form-check-input"
										{formname key=ATTRIBUTE_LIMIT_SCOPE} id="editAttributeLimitScope" />
									<label class="form-check-label"
										for="editAttributeLimitScope">{translate key=LimitAttributeScope}</label>
								</div>
							</div>

							<div class="form-group attributeSecondary d-none">
								<label for="editAttributeSecondaryCategory"
									class="visually-hidden">{translate key=Category}</label>
								<select class="secondaryAttributeCategory form-select"
									{formname key=ATTRIBUTE_SECONDARY_CATEGORY} id="editAttributeSecondaryCategory">
									<option value="{CustomAttributeCategory::USER}">{translate key=User}</option>
									<option value="{CustomAttributeCategory::RESOURCE}">{translate key=Resource}
									</option>
									<option value="{CustomAttributeCategory::RESOURCE_TYPE}">
										{translate key=ResourceType}</option>
								</select>
							</div>

							<div class="form-group attributeSecondary d-none">
								<label for="editAttributeSecondaryEntityDescription">{translate key=CollectFor}</label>
								<a href="#" class="secondaryPrompt link-primary"
									id="editAttributeSecondaryEntityDescription">{translate key=All}</a>
								{*<input type="hidden" class="secondaryEntityIds" {formname key=ATTRIBUTE_SECONDARY_ENTITY_IDS} id="editAttributeSecondaryEntityIds"/>*}
							</div>
						</div>
						<div id="editEntityChoices"></div>
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

	<div class="modal fade" id="deleteDialog" tabindex="-1" role="dialog" aria-labelledby="deleteLabel"
		aria-hidden="true">
		<div class="modal-dialog">
			<form id="deleteForm" ajaxAction="{ManageAttributesActions::DeleteAttribute}" method="post">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="deleteLabel">{translate key=Delete}</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
					</div>
					<div class="modal-body">
						<div class="alert alert-danger">
							{translate key=DeleteWarning}
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

	<div class="card shadow">
		<div class="card-body">
			<div id="attributeList">
			</div>
		</div>
	</div>

	{csrf_token}
	{indicator id="indicator"}

	<input type="hidden" id="activeId" value="" />
	{include file="javascript-includes.tpl" DataTable=true}
	{jsfile src="ajax-helpers.js"}
	{jsfile src="admin/attributes.js"}
	{jsfile src="js/jquery.form-3.09.min.js"}

	<script type="text/javascript">
		$(document).ready(function() {
			var attributeOptions = {
				submitUrl: '{$smarty.server.SCRIPT_NAME}',
				changeCategoryUrl: '{$smarty.server.SCRIPT_NAME}?{QueryStringKeys::DATA_REQUEST}=attributes&{QueryStringKeys::ATTRIBUTE_CATEGORY}=',
				singleLine: '{CustomAttributeTypes::SINGLE_LINE_TEXTBOX}',
				multiLine: '{CustomAttributeTypes::MULTI_LINE_TEXTBOX}',
				selectList: '{CustomAttributeTypes::SELECT_LIST}',
				date: '{CustomAttributeTypes::DATETIME}',
				checkbox: '{CustomAttributeTypes::CHECKBOX}',
				allText: "{translate key=All|escape:'javascript'}",
				categories: {
					reservation: {CustomAttributeCategory::RESERVATION},
					resource: {CustomAttributeCategory::RESOURCE},
					user: {CustomAttributeCategory::USER},
					resource_type: {CustomAttributeCategory::RESOURCE_TYPE}
				},
				resourcesUrl: 'manage_resources.php?{QueryStringKeys::DATA_REQUEST}=all',
				usersUrl: 'manage_users.php?{QueryStringKeys::DATA_REQUEST}=all',
				resourceTypesUrl: 'manage_resource_types.php?{QueryStringKeys::DATA_REQUEST}=all'
			};

			var attributeManagement = new AttributeManagement(attributeOptions);
			attributeManagement.init();
		});
	</script>
</div>
{include file='globalfooter.tpl'}