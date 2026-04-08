{ldelim}
{if $Attributes|default:array()|count > 0}
	{foreach from=$Attributes item=attribute name=attributes}
		{assign var='attributeValue' value=$attribute->Value()|default:''}
		{if $attribute->Type() eq $CustomAttributeTypeDateTime && $attribute->Value() neq null}
			{capture assign='attributeValue'}{formatdate date=$attribute->Value() key=general_datetime}{/capture}
		{/if}
		{$attribute->Id()|cat:''|json_encode} :
		[ {$attribute->Type()|json_encode} ,
		{$attribute->Label()|default:''|json_encode} ,
		{$attributeValue|json_encode} ] {if $smarty.foreach.attributes.last}{else},{/if}
	{/foreach}
{/if}
{rdelim}
