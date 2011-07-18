{foreach from=$themeModules item=themeModule}
	{if $themeModule->getThemeModuleType()->hasContent($themeModule, $themeModulePosition, $additionalData)}
		<div class="{@$themeModule->themeModuleType}ThemeModule{if $themeModule->cssClasses} {$themeModule->cssClasses}{/if}"{if $themeModule->cssID} id="{$themeModule->cssID}"{/if}>
			{@$themeModule->getThemeModuleType()->getContent($themeModule, $themeModulePosition, $additionalData)}
		</div>
	{/if}
{/foreach}