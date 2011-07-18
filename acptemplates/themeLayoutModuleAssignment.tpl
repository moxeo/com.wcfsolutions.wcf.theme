{include file='header'}
<script type="text/javascript">
	//<![CDATA[
	document.observe("dom:loaded", function() {
		var themeModuleList = $('themeModuleList');
		if (themeModuleList) {
			themeModuleList.addClassName('dragable');
			
			Sortable.create(themeModuleList, { 
				tag: 'tr',
				onUpdate: function(list) {
					var rows = list.select('tr');
					var showOrder = 0;
					var oldShowOrder = 0;
					var newShowOrder = 0;
					rows.each(function(row, i) {
						row.className = 'container-' + (i % 2 == 0 ? '1' : '2') + (row.hasClassName('marked') ? ' marked' : '');
						showOrder = row.select('.columnNumbers')[0];
						oldShowOrder = showOrder.innerHTML;
						newShowOrder = i + 1;
						if (newShowOrder != oldShowOrder) {
							showOrder.update(newShowOrder);
							new Ajax.Request('index.php?action=ThemeLayoutModuleSort&themeLayoutID={@$themeLayoutID}&themeModulePosition={@$themeModulePosition}&oldShowOrder='+oldShowOrder+'&themeModuleID='+row.id.gsub('themeModuleRow_', '')+SID_ARG_2ND, { method: 'post', parameters: { showOrder: newShowOrder } } );
						}
					});
				}
			});
		}
	});
	//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/themeLayoutModuleAssignmentL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.theme.layout.moduleAssignment{/lang}</h2>
	</div>
</div>

{if $removedThemeModuleID}
	<p class="success">{lang}wcf.acp.theme.layout.moduleAssignment.module.remove.success{/lang}</p>	
{/if}

{if $themeLayoutOptions|count}
	<fieldset>
		<legend>{lang}wcf.acp.theme.layout.moduleAssignment.themeLayout{/lang}</legend>
		<div class="formElement" id="themeLayoutDiv">
			<div class="formFieldLabel">
				<label for="themeLayoutChange">{lang}wcf.acp.theme.layout.moduleAssignment.themeLayout{/lang}</label>
			</div>
			<div class="formField">
				<select id="themeLayoutChange" onchange="document.location.href=fixURL('index.php?page=ThemeLayoutModuleAssignment&amp;themeLayoutID='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
					<option value="0"></option>
					{htmloptions options=$themeLayoutOptions selected=$themeLayoutID}
				</select>
			</div>
			<div class="formFieldDesc hidden" id="themeLayoutHelpMessage">
				{lang}wcf.acp.theme.layout.moduleAssignment.themeLayout.description{/lang}
			</div>
		</div>
		<script type="text/javascript">//<![CDATA[
			inlineHelp.register('themeLayout');
		//]]></script>
	</fieldset>
{/if}

{if $themeLayoutID}		
	{if $themeModulePositions|count}
		<fieldset>
			<legend>{lang}wcf.acp.theme.layout.moduleAssignment.themeModulePosition{/lang}</legend>
			<div class="formElement" id="themePositionDiv">
				<div class="formFieldLabel">
					<label for="themePositionChange">{lang}wcf.acp.theme.layout.moduleAssignment.themeModulePosition{/lang}</label>
				</div>
				<div class="formField">
					<select id="themePositionChange" onchange="document.location.href=fixURL('index.php?page=ThemeLayoutModuleAssignment&amp;themeLayoutID={@$themeLayoutID}&amp;themeModulePosition='+this.options[this.selectedIndex].value+'&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}')">
						{foreach from=$themeModulePositions item=position}
							<option value="{@$position}"{if $position == $themeModulePosition} selected="selected"{/if}>{lang}wcf.theme.module.position.{@$position}{/lang}</option>
						{/foreach}
					</select>
				</div>
				<div class="formFieldDesc hidden" id="themePositionHelpMessage">
					{lang}wcf.acp.theme.layout.moduleAssignment.themeModulePosition.description{/lang}
				</div>
			</div>
			<script type="text/javascript">//<![CDATA[
				inlineHelp.register('themePosition');
			//]]></script>
		</fieldset>
	{/if}
	
	{if $themeModules|count || $themeModuleOptions|count}
		<div class="border titleBarPanel">
			<div class="containerHead"><h3>{lang}wcf.acp.theme.layout.moduleAssignment.modules{/lang}</h3></div>
		</div>
		{if $themeModules|count}
			<div class="border borderMarginRemove">
				<table class="tableList">
					<thead>
						<tr class="tableHead">
							<th class="columnThemeID" colspan="2"><div><span class="emptyHead">{lang}wcf.acp.theme.layout.moduleAssignment.module.themeModuleID{/lang}</span></div></th>
							<th class="columnTitle"><div><span class="emptyHead">{lang}wcf.acp.theme.layout.moduleAssignment.module.title{/lang}</span></div></th>
							<th class="columnShowOrder"><div><span class="emptyHead">{lang}wcf.acp.theme.layout.moduleAssignment.module.showOrder{/lang}</span></div></th>
							
							{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
						</tr>
					</thead>
					<tbody id="themeModuleList">
						{foreach from=$themeModules item=child}
							{assign var=themeModule value=$child.themeModule}
							<tr class="{cycle values="container-1,container-2"}" id="themeModuleRow_{@$themeModule->themeModuleID}">
								<td class="columnIcon">
									{if $this->user->getPermission('admin.theme.canEditThemeLayout')}
										<a href="index.php?action=ThemeLayoutModuleRemove&amp;themeLayoutID={@$themeLayout->themeLayoutID}&amp;themeModulePosition={@$themeModulePosition}&amp;showOrder={@$child.showOrder}&amp;themeModuleID={@$themeModule->themeModuleID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" onclick="return confirm('{lang}wcf.acp.theme.layout.moduleAssignment.module.remove.sure{/lang}')" title="{lang}wcf.acp.theme.layout.moduleAssignment.module.remove{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>
									{else}
										<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.theme.layout.moduleAssignment.module.remove{/lang}" />
									{/if}
									
									{if $additionalButtons.$themeModule->themeModuleID|isset}{@$additionalButtons.$themeModule->themeModuleID}{/if}
								</td>
								<td class="columnThemeID columnID">{@$themeModule->themeModuleID}</td>
								<td class="columnTitle columnText">
									{$themeModule->title}
								</td>
								<td class="columnShowOrder columnNumbers">{@$child.showOrder}</td>
								
								{if $additionalColumns.$themeModule->themeModuleID|isset}{@$additionalColumns.$themeModule->themeModuleID}{/if}
							</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
		{/if}
		{if $themeModuleOptions|count}
			<form method="post" action="index.php?page=ThemeLayoutModuleAssignment">
				<div class="border content borderMarginRemove">
					<div class="container-1">
						<fieldset>
							<legend>{lang}wcf.acp.theme.layout.moduleAssignment.module.add{/lang}</legend>
							<div class="formElement{if $errorField == 'themeModuleID'} formError{/if}">
								<div class="formFieldLabel">
									<label for="themeModuleID">{lang}wcf.acp.theme.layout.moduleAssignment.module{/lang}</label>
								</div>
								<div class="formField">
									<select name="themeModuleID" id="themeModuleID">
										{htmloptions options=$themeModuleOptions selected=$themeModuleID disableEncoding=true}
									</select>
									<input type="submit" accesskey="s" value="{lang}wcf.acp.theme.layout.moduleAssignment.module.button.add{/lang}" />
									<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
									<input type="hidden" name="themeLayoutID" value="{@$themeLayoutID}" />
									<input type="hidden" name="themeModulePosition" value="{@$themeModulePosition}" />
									{@SID_INPUT_TAG}
									{if $errorField == 'themeModuleID'}
										<p class="innerError">
											{if $errorType == 'invalid'}{lang}wcf.acp.theme.layout.moduleAssignment.module.invalid{/lang}{/if}
										</p>
									{/if}
								</div>
							</div>
						</fieldset>
					</div>
				</div>
			</form>
		{/if}
	{/if}
{/if}

{include file='footer'}