{if $CanViewUser}
	<div id="userDetailsPopup">
		<div class="card-header fw-bold">
			{fullname first=$User->FirstName()|unescape:'html' last=$User->LastName()|unescape:'html' ignorePrivacy=true}
		</div>
		<div id="userDetailsName" class="card-body">
			{if $User->EmailAddress()}
				<div id="userDetailsEmail" class="fw-bold">
					<span class="label">{translate key=Email}</span>
					<a href="mailto:{$User->EmailAddress()}" class="link-primary">{$User->EmailAddress()}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Phone)}
				<div id="userDetailsPhone" class="fw-bold">
					<span class="label">{translate key=Phone}</span>
					<a href="tel:{$User->GetAttribute(UserAttribute::Phone)}"
						class="link-primary">{$User->GetAttribute(UserAttribute::Phone)}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Organization)}
				<div id="userDetailsOrganization" class="fw-bold">
					<span class="label">{translate key=Organization}</span>
					{$User->GetAttribute(UserAttribute::Organization)}
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Position)}
				<div id="userDetailsPosition" class="fw-bold">
					<span class="label">{translate key=Position}</span>
					{$User->GetAttribute(UserAttribute::Position)}
				</div>
			{/if}
			<div id="userDetailsAttributes" class="fw-bold">
				{foreach from=$Attributes item=attribute}
					<div class="customAttribute">
						<span class="label">{$attribute->Label()}</span>
						{$User->GetAttributeValue($attribute->Id())}
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}
