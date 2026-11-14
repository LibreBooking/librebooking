{if $CanViewUser}
	<div id="userDetailsPopup">
		<div class="card-header fw-bold">
			{fullname first=$User->FirstName()|default:''|unescape:'html' last=$User->LastName()|default:''|unescape:'html' ignorePrivacy=true}
		</div>
		<div id="userDetailsName" class="card-body">
			{if $User->EmailAddress()}
				<div id="userDetailsEmail" class="fw-bold">
					<span class="label">{translate key=Email}</span>
					<a href="mailto:{$User->EmailAddress()|escape:'html':'UTF-8':false}" class="link-primary">{$User->EmailAddress()|escape:'html':'UTF-8':false}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Phone)}
				<div id="userDetailsPhone" class="fw-bold">
					<span class="label">{translate key=Phone}</span>
					<a href="tel:{$User->GetAttribute(UserAttribute::Phone)|escape:'html':'UTF-8':false}"
						class="link-primary">{$User->GetAttribute(UserAttribute::Phone)|escape:'html':'UTF-8':false}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Organization)}
				<div id="userDetailsOrganization" class="fw-bold">
					<span class="label">{translate key=Organization}</span>
					{$User->GetAttribute(UserAttribute::Organization)|escape:'html':'UTF-8':false}
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Position)}
				<div id="userDetailsPosition" class="fw-bold">
					<span class="label">{translate key=Position}</span>
					{$User->GetAttribute(UserAttribute::Position)|escape:'html':'UTF-8':false}
				</div>
			{/if}
			<div id="userDetailsAttributes" class="fw-bold">
				{foreach from=$Attributes item=attribute}
					<div class="customAttribute">
						<span class="label">{$attribute->Label()}</span>
						{$User->GetAttributeValue($attribute->Id())|escape:'html':'UTF-8':false}
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}
