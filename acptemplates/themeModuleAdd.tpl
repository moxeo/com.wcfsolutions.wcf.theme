{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeModule{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.module.{@$action}{/lang}</h2>
		{if $themeID}<p>{$theme->themeName}</p>{/if}
		{if $themeModuleID|isset}<p>{lang}{$themeModule->title}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.theme.module.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?page=ThemeModuleList{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.theme.module.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/themeModuleM.png" alt="" /> <span>{lang}wcf.acp.menu.link.theme.module.view{/lang}</span></a></li>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</ul>
	</div>
</div>

{if $action == 'add'}
	{if $themeOptions|count}
		<fieldset>
			<legend>{lang}wcf.acp.theme{/lang}</legend>
			<div class="formElement" id="themeIDDiv">
				<div class="formFieldLabel">
					<label for="themeChange">{lang}wcf.acp.theme.module.themeID{/lang}</label>
				</div>
				<div class="formField">
					<select id="themeChange" onchange="document.location.href=fixURL('index.php?form=ThemeModuleAdd&amp;themeID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
						<option value="0"></option>
						{htmloptions options=$themeOptions selected=$themeID disableEncoding=true}
					</select>
				</div>
				<div class="formFieldDesc hidden" id="themeIDHelpMessage">
					{lang}wcf.acp.theme.module.themeID.description{/lang}
				</div>
			</div>
			<script type="text/javascript">//<![CDATA[
				inlineHelp.register('themeID');
			//]]></script>
		</fieldset>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.theme.module.view.count.noThemes{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{if $themeID || $action == 'edit'}
	<form method="post" action="index.php?form=ThemeModule{@$action|ucfirst}">
		<div class="border content">
			<div class="container-1">			
				<fieldset>
					<legend>{lang}wcf.acp.theme.module.general{/lang}</legend>
					
					<div class="formElement{if $errorField == 'title'} formError{/if}" id="titleDiv">
						<div class="formFieldLabel">
							<label for="title">{lang}wcf.acp.theme.module.title{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" id="title" name="title" value="{$title}" />
							{if $errorField == 'title'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="titleHelpMessage">
							{lang}wcf.acp.theme.module.title.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('title');
					//]]></script>
					
					<div class="formElement{if $errorField == 'themeModuleType'} formError{/if}" id="themeModuleTypeDiv">
						<div class="formFieldLabel">
							<label for="themeModuleType">{lang}wcf.acp.theme.module.type{/lang}</label>
						</div>
						<div class="formField">
							<select name="themeModuleType" id="themeModuleType" onchange="this.form.submit();">
								<option value=""></option>
								{htmloptions options=$themeModuleTypeOptions selected=$themeModuleType}
							</select>
							{if $errorField == 'themeModuleType'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="themeModuleTypeHelpMessage">
							<p>{lang}wcf.acp.theme.module.type.description{/lang}</p>
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('themeModuleType');
					//]]></script>
					
					{if $additionalGeneralFields|isset}{@$additionalGeneralFields}{/if}
				</fieldset>
				
				<fieldset>
					<legend>{lang}wcf.acp.theme.module.display{/lang}</legend>
					
					<div class="formElement" id="cssIDDiv">
						<div class="formFieldLabel">
							<label for="cssID">{lang}wcf.acp.theme.module.cssID{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" id="cssID" name="cssID" value="{$cssID}" />
						</div>
						<div class="formFieldDesc hidden" id="cssIDHelpMessage">
							{lang}wcf.acp.theme.module.cssID.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('cssID');
					//]]></script>
					
					<div class="formElement" id="cssClassesDiv">
						<div class="formFieldLabel">
							<label for="cssClasses">{lang}wcf.acp.theme.module.cssClasses{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" id="cssClasses" name="cssClasses" value="{$cssClasses}" />
						</div>
						<div class="formFieldDesc hidden" id="cssClassesHelpMessage">
							{lang}wcf.acp.theme.module.cssClasses.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('cssClasses');
					//]]></script>
				</fieldset>
				
				{if $themeModuleTypeObject && $themeModuleTypeObject->getFormTemplateName()}
					{include file=$themeModuleTypeObject->getFormTemplateName()}
				{/if}
				
				{if $additionalFields|isset}{@$additionalFields}{/if}
			</div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
			<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
	 		{@SID_INPUT_TAG}
	 		{if $themeID}<input type="hidden" name="themeID" value="{@$themeID}" />{/if}
	 		{if $themeModuleID|isset}<input type="hidden" name="themeModuleID" value="{@$themeModuleID}" />{/if}
	 	</div>
	</form>
{/if}

{include file='footer'}