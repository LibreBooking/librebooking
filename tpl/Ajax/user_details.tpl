{if $CanViewUser}
	<div>
		<strong>{fullname first=$User->FirstName()|unescape:'html' last=$User->LastName()|unescape:'html' ignorePrivacy=true}</strong>
		{if $User->EmailAddress()}
			<div><strong>{translate key=Email}:</strong> {$User->EmailAddress()}</div>
		{/if}
		{if $User->GetAttribute(UserAttribute::Phone)}
			<div><strong>{translate key=Phone}:</strong> {$User->GetAttribute(UserAttribute::Phone)}</div>
		{/if}
		{if $User->GetAttribute(UserAttribute::Organization)}
			<div><strong>{translate key=Organization}:</strong> {$User->GetAttribute(UserAttribute::Organization)}</div>
		{/if}
		{if $User->GetAttribute(UserAttribute::Position)}
			<div><strong>{translate key=Position}:</strong> {$User->GetAttribute(UserAttribute::Position)}</div>
		{/if}
		{foreach from=$Attributes item=attribute}
			<div>{$attribute->Label()}: {$User->GetAttributeValue($attribute->Id())}</div>
		{/foreach}
	</div>
{/if}
