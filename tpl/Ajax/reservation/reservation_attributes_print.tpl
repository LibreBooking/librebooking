{ldelim}
{if $Attributes|default:array()|count > 0}
	{foreach from=$Attributes item=attribute name=attributes}
		 "{$attribute->Id()}" :
		[ "{$attribute->Type()}" ,
		"{$attribute->Label()|default:''|escape:'json'}" ,
		{if $attribute->Type() eq '5'}
			"{if $attribute->Value() neq null}{formatdate date=$attribute->Value() key=embedded_datetime}{/if}" ] {if $smarty.foreach.attributes.last}{else},{/if}
		{else}
			"{$attribute->Value()|default:''|escape:'json'}" ] {if $smarty.foreach.attributes.last}{else},{/if}
		{/if}
	{/foreach}
{/if}
{rdelim}
