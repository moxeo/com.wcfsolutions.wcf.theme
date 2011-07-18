{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeImportL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.import{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.theme.import.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?page=ThemeList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.theme.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/themeM.png" alt="" /> <span>{lang}wcf.acp.menu.link.theme.view{/lang}</span></a></li>
			{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
		</ul>
	</div>
</div>
<form enctype="multipart/form-data" method="post" action="index.php?form=ThemeImport">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.theme.import.source{/lang}</legend>
				
				<div class="formElement{if $errorField == 'themeUpload'} formError{/if}" id="themeUploadDiv">
					<div class="formFieldLabel">
						<label for="themeUpload">{lang}wcf.acp.theme.import.themeUpload{/lang}</label>
					</div>
					<div class="formField">
						<input type="file" name="themeUpload" id="themeUpload" />
						{if $errorField == 'themeUpload'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'uploadFailed'}{lang}wcf.acp.theme.import.upload.error.failed{/lang}{/if}
								{if $errorType == 'invalid'}{lang}wcf.acp.theme.import.error.invalid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="themeUploadHelpMessage">
						{lang}wcf.acp.theme.import.themeUpload.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeUpload');
				//]]></script>
				
				<div class="formElement{if $errorField == 'themeURL'} formError{/if}" id="themeURLDiv">
					<div class="formFieldLabel">
						<label for="themeURL">{lang}wcf.acp.theme.import.themeURL{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="themeURL" name="themeURL" value="{$themeURL}" />
						{if $errorField == 'themeURL'}
							<p class="innerError">
								{if $errorType == 'downloadFailed'}{lang}wcf.acp.theme.import.download.error.failed{/lang}{/if}
								{if $errorType == 'invalid'}{lang}wcf.acp.theme.import.error.invalid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="themeURLHelpMessage">
						{lang}wcf.acp.theme.import.themeURL.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeURL');
				//]]></script>
				
				{if $additionalSourceFields|isset}{@$additionalSourceFields}{/if}
			</fieldset>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}