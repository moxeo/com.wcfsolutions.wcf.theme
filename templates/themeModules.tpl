{foreach from=$themeModules item=themeModule}
	<div class="{@$themeModule->themeModuleType}ThemeModule{if $themeModule->cssClasses} {$themeModule->cssClasses}{/if}"{if $themeModule->cssID} id="{$themeModule->cssID}"{/if}>
		{@$themeModule->getThemeModuleType()->getContent($themeModule, $themeModulePosition, $additionalData)}
	</div>
{/foreach}