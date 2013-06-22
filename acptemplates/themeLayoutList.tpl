{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeLayoutL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.layout.view{/lang}</h2>
		{if $themeID}<p>{$theme->themeName}</p>{/if}
	</div>
</div>

{if $deletedThemeLayoutID}
	<p class="success">{lang}wcf.acp.theme.layout.delete.success{/lang}</p>
{/if}

{if $themeOptions|count}
	<fieldset>
		<legend>{lang}wcf.acp.theme{/lang}</legend>
		<div class="formElement" id="themeDiv">
			<div class="formFieldLabel">
				<label for="themeChange">{lang}wcf.acp.theme{/lang}</label>
			</div>
			<div class="formField">
				<select id="themeChange" onchange="document.location.href=fixURL('index.php?page=ThemeLayoutList&amp;themeID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
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
			<p>{lang}wcf.acp.theme.layout.view.count.noThemes{/lang}</p>
		</div>
	</div>
{/if}

{if $themeID}
	<div class="contentHeader">
		{pages print=true assign=pagesLinks link="index.php?page=ThemeLayoutList&themeID=$themeID&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
		{if $this->user->getPermission('admin.theme.canAddThemeLayout')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=ThemeLayoutAdd{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeLayoutAddM.png" alt="" title="{lang}wcf.acp.theme.layout.add{/lang}" /> <span>{lang}wcf.acp.theme.layout.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>

	{if $themeLayouts|count}
		<div class="border titleBarPanel">
			<div class="containerHead"><h3>{lang}wcf.acp.theme.layout.view.count{/lang}</h3></div>
		</div>
		<div class="border borderMarginRemove">
			<table class="tableList">
				<thead>
					<tr class="tableHead">
						<th class="columnThemeLayoutID{if $sortField == 'themeLayoutID'} active{/if}" colspan="2"><div><a href="index.php?page=ThemeLayoutList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=themeLayoutID&amp;sortOrder={if $sortField == 'themeLayoutID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.layout.themeLayoutID{/lang}{if $sortField == 'themeLayoutID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeLayoutTitle{if $sortField == 'title'} active{/if}"><div><a href="index.php?page=ThemeLayoutList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=title&amp;sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.layout.title{/lang}{if $sortField == 'title'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
						<th class="columnThemeLayoutThemeModules{if $sortField == 'themeModules'} active{/if}"><div><a href="index.php?page=ThemeLayoutList&amp;themeID={@$themeID}&amp;pageNo={@$pageNo}&amp;sortField=themeModules&amp;sortOrder={if $sortField == 'themeModules' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.theme.layout.themeModules{/lang}{if $sortField == 'themeModules'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>

						{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
					</tr>
				</thead>
				<tbody id="themeLayoutList">
					{foreach from=$themeLayouts item=themeLayout}
						<tr class="{cycle values="container-1,container-2"}">
							<td class="columnIcon">
								{if $this->user->getPermission('admin.theme.canEditThemeLayout')}
									{if $themeLayout->isDefault}
										<img src="{@RELATIVE_WCF_DIR}icon/defaultDisabledS.png" alt="" title="{lang}wcf.acp.theme.layout.default{/lang}" />
									{else}
										<a href="index.php?action=ThemeLayoutSetAsDefault&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/defaultS.png" alt="" title="{lang}wcf.acp.theme.layout.setAsDefault{/lang}" /></a>
									{/if}
									<a href="index.php?form=ThemeLayoutCopy&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/copyS.png" alt="" title="{lang}wcf.acp.theme.layout.copy{/lang}" /></a>
									<a href="index.php?form=ThemeLayoutEdit&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.theme.layout.edit{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/defaultDisabledS.png" alt="" title="{lang}wcf.acp.theme.layout.default{/lang}" />
									<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.theme.layout.edit{/lang}" />
								{/if}
								{if $this->user->getPermission('admin.theme.canDeleteThemeLayout')}
									<a onclick="return confirm('{lang}wcf.acp.theme.layout.delete.sure{/lang}')" href="index.php?action=ThemeLayoutDelete&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.theme.layout.delete{/lang}" /></a>
								{else}
									<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.theme.layout.delete{/lang}" />
								{/if}

								{if $additionalButtons.$themeLayout->themeLayoutID|isset}{@$additionalButtons.$themeLayout->themeLayoutID}{/if}
							</td>
							<td class="columnThemeLayoutID columnID">{@$themeLayout->themeLayoutID}</td>
							<td class="columnThemeLayoutTitle columnText">
								{if $this->user->getPermission('admin.theme.canEditThemeLayout')}
									<a href="index.php?form=ThemeLayoutEdit&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$themeLayout->title}</a>
								{else}
									{$themeLayout->title}
								{/if}
							</td>
							<td class="columnThemeModules columnNumbers"><a href="index.php?page=ThemeLayoutModuleAssignment&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{@$themeLayout->themeModules}</a></td>

							{if $additionalColumns.$themeLayout->themeLayoutID|isset}{@$additionalColumns.$themeLayout->themeLayoutID}{/if}
						</tr>
					{/foreach}
				</tbody>
			</table>
		</div>

		<div class="contentFooter">
			{@$pagesLinks}

			{if $this->user->getPermission('admin.theme.canAddThemeLayout')}
				<div class="largeButtons">
					<ul><li><a href="index.php?form=ThemeLayoutAdd{if $themeID}&amp;themeID={@$themeID}{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeLayoutAddM.png" alt="" title="{lang}wcf.acp.theme.layout.add{/lang}" /> <span>{lang}wcf.acp.theme.layout.add{/lang}</span></a></li></ul>
				</div>
			{/if}
		</div>
	{else}
		<div class="border content">
			<div class="container-1">
				<p>{lang}wcf.acp.theme.layout.view.count.noThemeLayouts{/lang}</p>
			</div>
		</div>
	{/if}
{/if}

{include file='footer'}