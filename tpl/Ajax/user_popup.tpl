{if $CanViewUser}
	<div id="userDetailsPopup">
		<div class="fw-bold">
			{fullname first=$User->FirstName()|unescape:'html' last=$User->LastName()|unescape:'html' ignorePrivacy=true}
		</div>
		<div id="userDetailsName">
			{if $User->EmailAddress()}
				<div id="userDetailsEmail">
					<span class="fw-bold">{translate key=Email}</span>
					<a href="mailto:{$User->EmailAddress()}" class="link-primary">{$User->EmailAddress()}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Phone)}
				<div id="userDetailsPhone">
					<span class="fw-bold">{translate key=Phone}</span>
					<a href="tel:{$User->GetAttribute(UserAttribute::Phone)}"
						class="link-primary">{$User->GetAttribute(UserAttribute::Phone)}</a>
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Organization)}
				<div id="userDetailsOrganization">
					<span class="fw-bold">{translate key=Organization}</span>
					{$User->GetAttribute(UserAttribute::Organization)}
				</div>
			{/if}
			{if $User->GetAttribute(UserAttribute::Position)}
				<div id="userDetailsPosition">
					<span class="fw-bold">{translate key=Position}</span>
					{$User->GetAttribute(UserAttribute::Position)}
				</div>
			{/if}
			<div id="userDetailsAttributes">
				{foreach from=$Attributes item=attribute}
					<div class="customAttribute">
						<span class="fw-bold">{$attribute->Label()}</span>
						{$User->GetAttributeValue($attribute->Id())}
					</div>
				{/foreach}
			</div>
		</div>
	</div>
{/if}
