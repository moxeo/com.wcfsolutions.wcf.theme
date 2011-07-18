{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/theme{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.{@$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.theme.{@$action}.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=ThemeList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" themeName="{lang}wcf.acp.menu.link.theme.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/themeM.png" alt="" /> <span>{lang}wcf.acp.menu.link.theme.view{/lang}</span></a></li></ul>
	</div>
</div>

<form method="post" action="index.php?form=Theme{@$action|ucfirst}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.theme.data{/lang}</legend>
				
				<div class="formElement{if $errorField == 'themeName'} formError{/if}" id="themeNameDiv">
					<div class="formFieldLabel">
						<label for="themeName">{lang}wcf.acp.theme.themeName{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="themeName" name="themeName" value="{$themeName}" />
						{if $errorField == 'themeName'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="themeNameHelpMessage">
						{lang}wcf.acp.theme.themeName.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeName');
				//]]></script>
				
				<div class="formElement" id="templatePackIDDiv">
					<div class="formFieldLabel">
						<label for="templatePackID">{lang}wcf.acp.theme.templatePackID{/lang}</label>
					</div>
					<div class="formField">
						<select name="templatePackID" id="templatePackID">
							<option value="">{lang}wcf.acp.theme.template.pack.default{/lang}</option>
							{htmlOptions options=$templatePacks selected=$templatePackID}
						</select>
					</div>
					<div class="formFieldDesc hidden" id="templatePackIDHelpMessage">
						{lang}wcf.acp.theme.templatePackID.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('templatePackID');
				//]]></script>
				
				<div class="formElement{if $errorField == 'dataLocation'} formError{/if}" id="dataLocationDiv">
					<div class="formFieldLabel">
						<label for="dataLocation">{lang}wcf.acp.theme.dataLocation{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="dataLocation" name="dataLocation" value="{$dataLocation}" />
						{if $errorField == 'dataLocation'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="dataLocationHelpMessage">
						{lang}wcf.acp.theme.dataLocation.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('dataLocation');
				//]]></script>
				
				{if $additionalDataFields|isset}{@$additionalDataFields}{/if}
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.acp.theme.information{/lang}</legend>
				
				<div class="formElement" id="themeDescriptionDiv">
					<div class="formFieldLabel">
						<label for="themeDescription">{lang}wcf.acp.theme.themeDescription{/lang}</label>
					</div>
					<div class="formField">
						<textarea cols="40" rows="5" name="themeDescription" id="themeDescription">{$themeDescription}</textarea>
					</div>
					<div class="formFieldDesc hidden" id="themeDescriptionHelpMessage">
						{lang}wcf.acp.theme.themeDescription.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeDescription');
				//]]></script>
				
				<div class="formElement" id="themeVersionDiv">
					<div class="formFieldLabel">
						<label for="themeVersion">{lang}wcf.acp.theme.themeVersion{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="themeVersion" name="themeVersion" value="{$themeVersion}" />
					</div>
					<div class="formFieldDesc hidden" id="themeVersionHelpMessage">
						{lang}wcf.acp.theme.themeVersion.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeVersion');
				//]]></script>
				
				<div class="formElement" id="themeDateDiv">
					<div class="formFieldLabel">
						<label for="themeDate">{lang}wcf.acp.theme.themeDate{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="themeDate" name="themeDate" value="{$themeDate}" />
					</div>
					<div class="formFieldDesc hidden" id="themeDateHelpMessage">
						{lang}wcf.acp.theme.themeDate.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('themeDate');
				//]]></script>
				
				<div class="formElement" id="copyrightDiv">
					<div class="formFieldLabel">
						<label for="copyright">{lang}wcf.acp.theme.copyright{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="copyright" name="copyright" value="{$copyright}" />
					</div>
					<div class="formFieldDesc hidden" id="copyrightHelpMessage">
						{lang}wcf.acp.theme.copyright.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('copyright');
				//]]></script>
				
				<div class="formElement" id="licenseDiv">
					<div class="formFieldLabel">
						<label for="license">{lang}wcf.acp.theme.license{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="license" name="license" value="{$license}" />
					</div>
					<div class="formFieldDesc hidden" id="licenseHelpMessage">
						{lang}wcf.acp.theme.license.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('license');
				//]]></script>
				
				<div class="formElement" id="authorNameDiv">
					<div class="formFieldLabel">
						<label for="authorName">{lang}wcf.acp.theme.authorName{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="authorName" name="authorName" value="{$authorName}" />
					</div>
					<div class="formFieldDesc hidden" id="authorNameHelpMessage">
						{lang}wcf.acp.theme.authorName.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('authorName');
				//]]></script>
				
				<div class="formElement" id="authorURLDiv">
					<div class="formFieldLabel">
						<label for="authorURL">{lang}wcf.acp.theme.authorURL{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="authorURL" name="authorURL" value="{$authorURL}" />
					</div>
					<div class="formFieldDesc hidden" id="authorURLHelpMessage">
						{lang}wcf.acp.theme.authorURL.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('authorURL');
				//]]></script>
				
				{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}
			</fieldset>
				
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $themeID|isset}<input type="hidden" name="themeID" value="{@$themeID}" />{/if}
 	</div>
</form>

{include file='footer'}