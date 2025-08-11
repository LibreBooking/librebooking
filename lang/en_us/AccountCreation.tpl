<p>{$To},</p>

<p>A new user has registered with the following information:<br/>
Email: {$EmailAddress}<br/>
Name: {$FullName}<br/>
Phone: {$Phone}<br/>
Organization: {$Organization}<br/>
Position: {$Position}</p>
{if !empty($CreatedBy)}
	Created by: {$CreatedBy}
{/if}
