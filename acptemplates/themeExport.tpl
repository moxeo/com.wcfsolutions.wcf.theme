{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeExportL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.export{/lang}</h2>
		<p>{$theme->themeName}</p>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?page=ThemeList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.theme.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/themeM.png" alt="" /> <span>{lang}wcf.acp.menu.link.theme.view{/lang}</span></a></li>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</ul>
	</div>
</div>
<form method="post" action="index.php?form=ThemeExport">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.theme.export.data{/lang}</legend>
				
				<div class="formElement{if $errorField == 'filename'} formError{/if}" id="filenameDiv">
					<div class="formFieldLabel">
						<label for="filename">{lang}wcf.acp.theme.export.filename{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="filename" name="filename" value="{$filename}" />
						{if $errorField == 'filename'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="filenameHelpMessage">
						{lang}wcf.acp.theme.export.filename.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('filename');
				//]]></script>
				
				{if $additionalDataFields|isset}{@$additionalDataFields}{/if}
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.acp.theme.export.files{/lang}</legend>
				
				<div class="formElement" id="exportTemplatesDiv">
					<div class="formField">
						<label id="exportTemplates"><input type="checkbox" name="exportTemplates" value="1" {if $exportTemplates}checked="checked" {/if}/> {lang}wcf.acp.theme.export.exportTemplates{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="exportTemplatesHelpMessage">
						<p>{lang}wcf.acp.theme.export.exportTemplates.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('exportTemplates');
				//]]></script>
				
				{if $additionalFileFields|isset}{@$additionalFileFields}{/if}
			</fieldset>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		<input type="hidden" name="themeID" value="{@$themeID}" />
 	</div>
</form>

{include file='footer'}