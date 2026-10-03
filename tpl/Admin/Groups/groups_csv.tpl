{strip}
{* {strip} joins these lines; {linebreak} emits the row separators. *}
"Name",
"Is Auto Add",
"Group Administrator",
"Is Application Admin",
"Is Group Admin",
"Is Resource Admin",
"Is Schedule Admin",
"Members",
"Full Permissions",
"Read Only Permissions"
{linebreak}
{foreach from=$Groups item=group name=groupLoop}
    "{$group->Name()|escape_csv}",
    "{if $group->IsDefault()}true{else}false{/if}",
    "{$group->AdminGroupName()|escape_csv}",
    "{if $group->IsAdmin()}true{else}false{/if}",
    "{if $group->IsGroupAdmin()}true{else}false{/if}",
    "{if $group->IsResourceAdmin()}true{else}false{/if}",
    "{if $group->IsScheduleAdmin()}true{else}false{/if}",
    "
    {foreach from=$Users[$group->Id()] item=user name=userLoop}
        {$user->Email|escape_csv}
        {if !$smarty.foreach.userLoop.last},{/if}
    {/foreach}
    ",
    "
    {foreach from=$PermissionsWrite[$group->Id()] item=p name=fullPermissionsLoop}
        {$p->ResourceName()|escape_csv}
        {if !$smarty.foreach.fullPermissionsLoop.last},{/if}
    {/foreach}
    ",
    "
    {foreach from=$PermissionsRead[$group->Id()] item=p name=readPermissionsLoop}
        {$p->ResourceName()|escape_csv}
        {if !$smarty.foreach.readPermissionsLoop.last},{/if}
    {/foreach}
    "
    {linebreak}
{/foreach}
{/strip}
