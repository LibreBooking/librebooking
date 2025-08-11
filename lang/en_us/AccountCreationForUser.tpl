<p>{$FullName},</p>

<p>An account for {$AppTitle} has been created for you with the following details:<br/>
Email: {$EmailAddress}<br/>
Name: {$FullName}<br/>
Phone: {$Phone}<br/>
Organization: {$Organization}<br/>
Position: {$Position}<br/>
Password: {$Password}</p>
{if !empty($CreatedBy)}
	Created by: {$CreatedBy}
{/if}

<a href="{$ScriptUrl}">Log In to {$AppTitle}</a>
