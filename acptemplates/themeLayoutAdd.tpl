{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeLayout{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.layout.{@$action}{/lang}</h2>
		{if $themeID}<p>{$theme->themeName}</p>{/if}
		{if $themeLayoutID|isset}<p>{lang}{$themeLayout->title}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.theme.layout.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?page=ThemeLayoutList{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.theme.layout.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/themeLayoutM.png" alt="" /> <span>{lang}wcf.acp.menu.link.theme.layout.view{/lang}</span></a></li>
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
					<label for="themeChange">{lang}wcf.acp.theme.layout.themeID{/lang}</label>
				</div>
				<div class="formField">
					<select id="themeChange" onchange="document.location.href=fixURL('index.php?form=ThemeLayoutAdd&amp;themeID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
						<option value="0"></option>
						{htmloptions options=$themeOptions selected=$themeID disableEncoding=true}
					</select>
				</div>
				<div class="formFieldDesc hidden" id="themeIDHelpMessage">
					{lang}wcf.acp.theme.layout.themeID.description{/lang}
				</div>
			</div>
			<script type="text/javascript">//<![CDATA[
				inlineHelp.register('themeID');
			//]]></script>
		</fieldset>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.theme.layout.view.count.noThemes{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{if $themeID || $action == 'edit'}
	<form method="post" action="index.php?form=ThemeLayout{@$action|ucfirst}">
		<div class="border content">
			<div class="container-1">
				<fieldset>
					<legend>{lang}wcf.acp.theme.layout.data{/lang}</legend>
					
					<div class="formElement{if $errorField == 'title'} formError{/if}" id="titleDiv">
						<div class="formFieldLabel">
							<label for="title">{lang}wcf.acp.theme.layout.title{/lang}</label>
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
							{lang}wcf.acp.theme.layout.title.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('title');
					//]]></script>
					
					{if $additionalDataFields|isset}{@$additionalDataFields}{/if}
				</fieldset>
				
				<fieldset>
					<legend>{lang}wcf.acp.theme.layout.display{/lang}</legend>
					
					<div class="formElement" id="styleSheetsDiv">
						<div class="formFieldLabel">
							<label for="styleSheets">{lang}wcf.acp.theme.layout.styleSheets{/lang}</label>
						</div>
						<div class="formField">
							<textarea id="styleSheets" name="styleSheets" cols="40" rows="5">{$styleSheets}</textarea>
						</div>
						<div class="formFieldDesc hidden" id="styleSheetsHelpMessage">
							<p>{lang}wcf.acp.theme.layout.styleSheets.description{/lang}</p>
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('styleSheets');
					//]]></script>
				</fieldset>
					
				{if $additionalFields|isset}{@$additionalFields}{/if}
			</div>
		</div>
			
		<div class="formSubmit">
			<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
			<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
	 		{@SID_INPUT_TAG}
	 		{if $themeID}<input type="hidden" name="themeID" value="{@$themeID}" />{/if}
	 		{if $themeLayoutID|isset}<input type="hidden" name="themeLayoutID" value="{@$themeLayoutID}" />{/if}
	 	</div>
	</form>
{/if}

{include file='footer'}