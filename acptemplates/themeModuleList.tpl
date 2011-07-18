{capture append='specialStyles'}
<style type="text/css">
	@import url("{@RELATIVE_WCF_DIR}acp/style/wsip{if PAGE_DIRECTION == 'rtl'}-rtl{/if}.css");
</style>
{/capture}{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeModuleL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.module.view{/lang}</h2>
		{if $themeID}<p>{$theme->themeName}</p>{/if}
	</div>
</div>

{if $deletedThemeModuleID}
	<p class="success">{lang}wcf.acp.theme.module.delete.success{/lang}</p>	
{/if}

{if $successfulSorting}
	<p class="success">{lang}wcf.acp.theme.module.sort.success{/lang}</p>	
{/if}

{if $themeOptions|count}
	<fieldset>
		<legend>{lang}wcf.acp.theme{/lang}</legend>
		<div class="formElement" id="themeDiv">
			<div class="formFieldLabel">
				<label for="themeChange">{lang}wcf.acp.theme{/lang}</label>
			</div>
			<div class="formField">
				<select id="themeChange" onchange="document.location.href=fixURL('index.php?page=ThemeModuleList&amp;themeID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
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
			<p>{lang}wcf.acp.theme.module.view.count.noThemes{/lang}</p>
		</div>
	</div>
{/if}

{if $themeID}
	<div class="contentHeader">
		{pages print=true assign=pagesLinks link="index.php?page=ThemeModuleList&themeID=$themeID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
		{if $this->user->getPermission('admin.theme.canAddThemeModule')}
			<div class="largeButtons">
				<ul>
					<li><a href="index.php?form=ThemeModuleAdd&amp;themeID={@$themeID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeModuleAddM.png" alt="" title="{lang}wcf.acp.theme.module.add{/lang}" /> <span>{lang}wcf.acp.theme.module.add{/lang}</span></a></li>
				</ul>
			</div>
		{/if}
	</div>
	
	{if $themeModules|count}
		<div class="border titleBarPanel">
			<div class="containerHead"><h3>{lang}wcf.acp.theme.module.view.count{/lang}</h3></div>
		</div>
		<div class="border borderMarginRemove">
			<table class="tableList">
				<thead>
					<tr class="tableHead">
						<th class="columnThemeModuleID{if $sortField == 'themeModuleID'} active{/if}" colspan="2"><div><a href="index.php?page=ThemeModuleList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=themeModuleID&amp;sortOrder={if $sortField == 'themeModuleID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.module.themeModuleID{/lang}{if $sortField == 'themeModuleID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeModuleTitle{if $sortField == 'title'} active{/if}"><div><a href="index.php?page=ThemeModuleList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=title&amp;sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.module.title{/lang}{if $sortField == 'title'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeModuleType{if $sortField == 'themeModuleType'} active{/if}"><div><a href="index.php?page=ThemeModuleList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=themeModuleType&amp;sortOrder={if $sortField == 'themeModuleType' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.module.themeModuleType{/lang}{if $sortField == 'themeModuleType'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeModulePreview"><div><span class="emptyHead">{lang}wcf.acp.theme.module.preview{/lang}</span></div></th>
						
						{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
					</tr>
				</thead>
				<tbody>
					{foreach from=$themeModules item=themeModule}
						<tr class="{cycle values="container-1,container-2"}">
							<td class="columnIcon">
								<a href="index.php?form=ThemeModuleEdit&amp;themeModuleID={@$themeModule->themeModuleID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.theme.module.edit{/lang}" /></a>
								<a href="index.php?action=ThemeModuleDelete&amp;themeModuleID={@$themeModule->themeModuleID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.acp.theme.module.delete.sure{/lang}')" title="{lang}wcf.acp.theme.module.delete{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>
								
								{if $additionalButtons.$themeModule->themeModuleID|isset}{@$additionalButtons.$themeModule->themeModuleID}{/if}
							</td>
							<td class="columnThemeModuleID columnID">{@$themeModule->themeModuleID}</td>
							<td class="columnThemeModuleTitle columnText">
								{if $this->user->getPermission('admin.theme.canEditThemeModule')}
									<a href="index.php?form=ThemeModuleEdit&amp;themeModuleID={@$themeModule->themeModuleID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$themeModule->title}</a>
								{else}
									{$themeModule->title}
								{/if}
							</td>
							<td class="columnThemeModuleType columnText">
								{lang}wcf.theme.module.type.{@$themeModule->themeModuleType}{/lang}
							</td>
							<td class="columnThemeModulePreview columnText">
								{@$themeModule->getThemeModuleType()->getPreviewHTML($themeModule)}
							</td>
							
							{if $additionalColumns.$themeModule->themeModuleID|isset}{@$additionalColumns.$themeModule->themeModuleID}{/if}
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>
		
		<div class="contentFooter">
			{@$pagesLinks}
			
			{if $this->user->getPermission('admin.portal.canAddThemeModule')}
				<div class="largeButtons">
					<ul>
						<li><a href="index.php?form=ThemeModuleAdd&amp;themeID={@$themeID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeModuleAddM.png" alt="" title="{lang}wcf.acp.theme.module.add{/lang}" /> <span>{lang}wcf.acp.theme.module.add{/lang}</span></a></li>
					</ul>
				</div>
			{/if}
		</div>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.theme.module.view.count.noThemeModules{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{include file='footer'}