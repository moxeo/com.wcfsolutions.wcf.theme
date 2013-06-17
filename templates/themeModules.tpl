{foreach from=$themeModules item=themeModule}
	{assign var=themeModuleType value=$themeModule->getThemeModuleType()}
	<{@$themeModuleType->getHTMLTag()} class="module {@$themeModule->themeModuleType}Module{if $themeModule->cssClasses} {$themeModule->cssClasses}{/if}"{if $themeModule->cssID} id="{$themeModule->cssID}"{/if}>
		{@$themeModuleType->getContent($themeModule, $themeModulePosition, $additionalData)}
	</{@$themeModuleType->getHTMLTag()}>
{/foreach}