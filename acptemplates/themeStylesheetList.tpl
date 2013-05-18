{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeStylesheetL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.stylesheet.view{/lang}</h2>
		{if $themeID}<p>{$theme->themeName}</p>{/if}
	</div>
</div>

{if $deletedThemeStylesheetID}
	<p class="success">{lang}wcf.acp.theme.stylesheet.delete.success{/lang}</p>
{/if}

{if $themeOptions|count}
	<fieldset>
		<legend>{lang}wcf.acp.theme{/lang}</legend>
		<div class="formElement" id="themeDiv">
			<div class="formFieldLabel">
				<label for="themeChange">{lang}wcf.acp.theme{/lang}</label>
			</div>
			<div class="formField">
				<select id="themeChange" onchange="document.location.href=fixURL('index.php?page=ThemeStylesheetList&amp;themeID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
					<option value="0"></option>
					{htmloptions options=$themeOptions selected=$themeID disableEncoding=true}
				</select>
			</div>
			<div class="formFieldDesc hidden" id="themeHelpMessage">
				{lang}wcf.acp.theme.description{/lang}
			</div>
		</div>
		<script type="text/javascript">//<![CDATA[
			inlineHelp.register('theme');
		//]]></script>
	</fieldset>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.theme.stylesheet.view.count.noThemes{/lang}</p>
		</div>
	</div>
{/if}

{if $themeID}
	<div class="contentHeader">
		{pages print=true assign=pagesLinks link="index.php?page=ThemeStylesheetList&themeID=$themeID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
		{if $this->user->getPermission('admin.theme.canAddThemeStylesheet')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=ThemeStylesheetAdd{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeStylesheetAddM.png" alt="" title="{lang}wcf.acp.theme.stylesheet.add{/lang}" /> <span>{lang}wcf.acp.theme.stylesheet.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>

	{if $themeStylesheets|count}
		<div class="border titleBarPanel">
			<div class="containerHead"><h3>{lang}wcf.acp.theme.stylesheet.view.count{/lang}</h3></div>
		</div>
		<div class="border borderMarginRemove">
			<table class="tableList">
				<thead>
					<tr class="tableHead">
						<th class="columnThemeStylesheetID{if $sortField == 'themeStylesheetID'} active{/if}" colspan="2"><div><a href="index.php?page=ThemeStylesheetList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=themeStylesheetID&amp;sortOrder={if $sortField == 'themeStylesheetID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.stylesheet.themeStylesheetID{/lang}{if $sortField == 'themeStylesheetID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeStylesheetTitle{if $sortField == 'title'} active{/if}"><div><a href="index.php?page=ThemeStylesheetList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=title&amp;sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.stylesheet.title{/lang}{if $sortField == 'title'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>

						{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
					</tr>
				</thead>
				<tbody id="themeStylesheetList">
					{foreach from=$themeStylesheets item=themeStylesheet}
						<tr class="{cycle values="container-1,container-2"}">
							<td class="columnIcon">
								{if $this->user->getPermission('admin.theme.canEditThemeStylesheet')}
									<a href="index.php?form=ThemeStylesheetEdit&amp;themeStylesheetID={@$themeStylesheet->themeStylesheetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.theme.stylesheet.edit{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.theme.stylesheet.edit{/lang}" />
								{/if}
								{if $this->user->getPermission('admin.theme.canDeleteThemeStylesheet')}
									<a onclick="return confirm('{lang}wcf.acp.theme.stylesheet.delete.sure{/lang}')" href="index.php?action=ThemeStylesheetDelete&amp;themeStylesheetID={@$themeStylesheet->themeStylesheetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.theme.stylesheet.delete{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.theme.stylesheet.delete{/lang}" />
								{/if}

								{if $additionalButtons.$themeStylesheet->themeStylesheetID|isset}{@$additionalButtons.$themeStylesheet->themeStylesheetID}{/if}
							</td>
							<td class="columnThemeStylesheetID columnID">{@$themeStylesheet->themeStylesheetID}</td>
							<td class="columnThemeStylesheetTitle columnText">
								{if $this->user->getPermission('admin.theme.canEditThemeStylesheet')}
									<a href="index.php?form=ThemeStylesheetEdit&amp;themeStylesheetID={@$themeStylesheet->themeStylesheetID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$themeStylesheet->title}</a>
								{else}
									{$themeStylesheet->title}
								{/if}
							</td>

							{if $additionalColumns.$themeStylesheet->themeStylesheetID|isset}{@$additionalColumns.$themeStylesheet->themeStylesheetID}{/if}
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>

		<div class="contentFooter">
			{@$pagesLinks}

			{if $this->user->getPermission('admin.theme.canAddThemeStylesheet')}
				<div class="largeButtons">
					<ul><li><a href="index.php?form=ThemeStylesheetAdd{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeStylesheetAddM.png" alt="" title="{lang}wcf.acp.theme.stylesheet.add{/lang}" /> <span>{lang}wcf.acp.theme.stylesheet.add{/lang}</span></a></li></ul>
				</div>
			{/if}
		</div>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.theme.stylesheet.view.count.noThemeStylesheets{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{include file='footer'}