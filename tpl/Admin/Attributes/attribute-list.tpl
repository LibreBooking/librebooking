<h4>{$Attributes|default:array()|count} {translate key=Attributes}</h4>
{if $Attributes|default:array()|count > 0}
	{assign var=tableId value='attributeListTable'}
	<div class="table-responsive">
		<table class="table table-striped table-hover border-top w-100" id="{$tableId}">
			<thead>
				<tr>
					<th class="d-none">ID</th>
					<th>{translate key=SortOrder}</th>
					<th>{translate key=DisplayLabel}</th>
					<th>{translate key=Type}</th>
					<th>{translate key=Required}</th>
					{if $Category != CustomAttributeCategory::RESERVATION}
						<th>{translate key=AppliesTo}</th>
					{/if}
					<th>{translate key=CollectedFor}</th>
					<th>{translate key=ValidationExpression}</th>
					<th>{translate key=PossibleValues}</th>
					{if $Category == CustomAttributeCategory::RESERVATION}
						<th>{translate key=Private}</th>
					{/if}
					<th>{translate key=AdminOnly}</th>
					<th class="action">{translate key=Edit}</th>
					<th class="action">{translate key=Delete}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$Attributes item=attribute}
					<tr attributeId="{$attribute->Id()}">
						<td class="d-none">{$attribute->Id()}</td>
						<td>{$attribute->SortOrder()}</td>
						<td>{$attribute->Label()}</td>
						<td>{translate key=$Types[$attribute->Type()]}</td>
						<td>{if $attribute->Required()}
								{translate key=Yes}
							{else}
								{translate key=No}
							{/if}</td>
						{if $Category != CustomAttributeCategory::RESERVATION}
							<td>{if $attribute->UniquePerEntity()}
									{$attribute->EntityDescriptions()|join:', '}
								{else}
									{translate key=All}
								{/if}
							</td>
						{/if}
						<td>
							{if $attribute->HasSecondaryEntities()}
								{$attribute->SecondaryEntityDescriptions()|join:', '}
							{else}
								{translate key=All}
							{/if}
						</td>
						<td>{$attribute->Regex()}</td>
						<td>{$attribute->PossibleValues()}</td>
						{if $Category == CustomAttributeCategory::RESERVATION}
							<td>{if $attribute->IsPrivate()}
									{translate key=Yes}
								{else}
									{translate key=No}
								{/if}</td>
						{/if}
						<td>{if $attribute->AdminOnly()}{translate key=Yes}{else}{translate key=No}{/if}</td>
						<td class="action">
							<a href="#" class="update edit link-primary">
								<span class="visually-hidden">{translate key=Edit}</span>
								<i class="bi bi-pencil-square icon edit"></i>
							</a>
						</td>
						<td class="action">
							<a href="#" class="update delete" attributeId="{$attribute->Id()}">
								<span class="visually-hidden">{translate key=Remove}</span>
								<span class="bi bi-trash3-fill text-danger icon remove"></span>
							</a>
						</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/if}
{datatable tableId={$tableId}}
<script type="text/javascript">
	var attributeList = new Object();

	{foreach from=$Attributes item=attribute}
		attributeList[{$attribute->Id()}] = {
		id: {$attribute->Id()},
		label: "{$attribute->Label()|escape:'javascript'}",
		required: {$attribute->Required()},
		regex: "{$attribute->Regex()|escape:'javascript'}",
		possibleValues: "{$attribute->PossibleValues()|escape:'javascript'}",
		type: "{$attribute->Type()}",
		sortOrder: "{$attribute->SortOrder()}",
		{if $attribute->EntityIds()|count > 0}
			entityIds: ["{$attribute->EntityIds()|join:'","'}"],
		{else}
			entityIds: [],
			{/if}
			entityDescriptions: ["{$attribute->EntityDescriptions()|join:'","'}"],
			adminOnly: {$attribute->AdminOnly()},
			{if $attribute->HasSecondaryEntities()}
				secondaryEntityIds: ["{$attribute->SecondaryEntityIds()|join:'","'}"],
				secondaryEntityDescriptions: ["{$attribute->SecondaryEntityDescriptions()|join:'","'}"],
			{else}
				secondaryEntityIds: [],
				secondaryEntityDescriptions: [],
			{/if}
			secondaryCategory: "{$attribute->SecondaryCategory()}",
			isPrivate: "{$attribute->IsPrivate()}"
		};
	{/foreach}

	$('#attributeList').data('list', attributeList);
</script>