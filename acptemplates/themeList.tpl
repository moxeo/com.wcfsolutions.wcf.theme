{capture append='specialStyles'}
<style type="text/css">
	@import url("{@RELATIVE_WCF_DIR}acp/style/extra/theme{if PAGE_DIRECTION == 'rtl'}-rtl{/if}.css");
</style>
{/capture}{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.view{/lang}</h2>
	</div>
</div>

{if $deletedThemeID}
	<p class="success">{lang}wcf.acp.theme.delete.success{/lang}</p>	
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=ThemeList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
	{if $this->user->getPermission('admin.theme.canAddTheme')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=ThemeAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeAddM.png" alt="" title="{lang}wcf.acp.theme.add{/lang}" /> <span>{lang}wcf.acp.theme.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>

{if $themes|count}
	<div id="themeList">
		{foreach from=$themes item=theme}
			<div class="message content">
				<div class="messageInner container-{cycle name='themes' values='1,2'}">
					<h3 class="subHeadline">
						{if $this->user->getPermission('admin.theme.canEditTheme')}
							<a href="index.php?form=ThemeEdit&amp;themeID={@$theme->themeID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$theme->themeName}</a>
						{else}
							{$theme->themeName}
						{/if}
					</h3>
					
					<div class="messageBody">
						{if $theme->authorName != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.authorName{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->authorName}</span>
								</div>
							</div>
						{/if}
						{if $theme->copyright != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.copyright{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->copyright}</span>
								</div>
							</div>
						{/if}
						{if $theme->themeVersion != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.themeVersion{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->themeVersion}</span>
								</div>
							</div>
						{/if}
						{if $theme->themeDate != '0000-00-00'}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.themeDate{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->themeDate}</span>
								</div>
							</div>
						{/if}
						{if $theme->license != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.license{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->license}</span>
								</div>
							</div>
						{/if}
						{if $theme->authorURL != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.authorURL{/lang}</label>
								</div>
								<div class="formField">
									<a href="{@RELATIVE_WCF_DIR}acp/dereferrer.php?url={$theme->authorURL|rawurlencode}" class="externalURL">{$theme->authorURL}</a>
								</div>
							</div>
						{/if}
						<div class="formElement">
							<div class="formFieldLabel">
								<label>{lang}wcf.acp.theme.themeLayouts{/lang}</label>
							</div>
							<div class="formField">
								{if $this->user->getPermission('admin.theme.canEditThemeLayout') || $this->user->getPermission('admin.theme.canDeleteThemeLayout')}
									<a href="index.php?page=ThemeLayoutList&amp;themeID={@$theme->themeID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{@$theme->themeLayouts}</a>
								{else}
									{@$theme->themeLayouts}
								{/if}
							</div>
						</div>
						{if $theme->themeDescription != ''}
							<div class="formElement">
								<div class="formFieldLabel">
									<label>{lang}wcf.acp.theme.themeDescription{/lang}</label>
								</div>
								<div class="formField">
									<span>{$theme->themeDescription}</span>
								</div>
							</div>
						{/if}
					</div>
					
					<div class="messageFooter">
						<div class="smallButtons">
							<ul>
								<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/upS.png" alt="{lang}wcf.global.scrollUp{/lang}" /></a></li>
								{if $this->user->getPermission('admin.theme.canExportTheme')}
									<li><a href="index.php?form=ThemeExport&amp;themeID={@$theme->themeID}}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.theme.exportButton{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/exportS.png" alt="" /> <span>{lang}wcf.acp.theme.exportButton{/lang}</span></a></li>
								{/if}
								{if $this->user->getPermission('admin.theme.canDeleteTheme')}
									<li><a onclick="return confirm('{lang}wcf.acp.theme.delete.sure{/lang}')" href="index.php?action=ThemeDelete&amp;themeID={@$theme->themeID}}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.theme.delete{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /> <span>{lang}wcf.acp.theme.delete{/lang}</span></a></li>
								{/if}
								{if $this->user->getPermission('admin.theme.canEditTheme')}
									<li><a href="index.php?form=ThemeEdit&amp;themeID={@$theme->themeID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.theme.editButton{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /> <span>{lang}wcf.acp.theme.editButton{/lang}</span></a></li>
								{/if}
							</ul>
						</div>
					</div>
				</div>
			</div>
		{/foreach}
	</div>

	<div class="border infoBox">
		<div class="container-1 infoBoxSorting">
			<div class="containerIcon">
				<img src="{@RELATIVE_WCF_DIR}icon/sortM.png" alt="" />
			</div>
			<div class="containerContent">
				<h3>{lang}wcf.acp.theme.sorting{/lang}</h3>
				<form method="get" action="index.php">
					<div class="themeSort">
						<input type="hidden" name="page" value="ThemeList" />
						<input type="hidden" name="pageNo" value="{@$pageNo}" />
						
						<div class="floatedElement">
							<label for="sortField">{lang}wcf.acp.theme.sortBy{/lang}</label>
							<select name="sortField" id="sortField">
								<option value="themeID"{if $sortField == 'themeID'} selected="selected"{/if}>{lang}wcf.acp.theme.themeID{/lang}</option>
								<option value="themeName"{if $sortField == 'themeName'} selected="selected"{/if}>{lang}wcf.acp.theme.themeName{/lang}</option>
								<option value="themeLayouts"{if $sortField == 'themeLayouts'} selected="selected"{/if}>{lang}wcf.acp.theme.themeLayouts{/lang}</option>
							</select>
							<select name="sortOrder">
								<option value="ASC"{if $sortOrder == 'ASC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.ascending{/lang}</option>
								<option value="DESC"{if $sortOrder == 'DESC'} selected="selected"{/if}>{lang}wcf.global.sortOrder.descending{/lang}</option>
							</select>
						</div>
						
						<div class="floatedElement">
							<input type="image" class="inputImage" src="{@RELATIVE_WCF_DIR}icon/submitS.png" alt="{lang}wcf.global.button.submit{/lang}" />
						</div>
	
						{@SID_INPUT_TAG}
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="contentFooter">
		{@$pagesLinks}
		
		{if $this->user->getPermission('admin.theme.canAddTheme')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=ThemeAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/themeAddM.png" alt="" title="{lang}wcf.acp.theme.add{/lang}" /> <span>{lang}wcf.acp.theme.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.theme.view.count.noThemes{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}