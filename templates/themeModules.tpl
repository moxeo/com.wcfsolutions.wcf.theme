{foreach from=$themeModules key=themeModuleID item=themeModule}
	{assign var=themeModuleType value=$themeModule->getThemeModuleType()}
	<{@$themeModules[$themeModuleID]->getThemeModuleType()->getHTMLTag()} class="module {@$themeModule->themeModuleType}Module{if $themeModule->cssClasses} {$themeModule->cssClasses}{/if}"{if $themeModule->cssID} id="{$themeModule->cssID}"{/if}>
		{capture assign='content'}{@$themeModuleType->getContent($themeModule, $themeModulePosition, $additionalData)}{/capture}
		{@$content}
	</{@$themeModules[$themeModuleID]->getThemeModuleType()->getHTMLTag()}>
{/foreach}