{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}

{include file='menu.settings.tpl'}

<table class="table table-users">
	<thead>
		<tr>
			<th class="cell-name cell-tab"><div class="cell-tab-inner">{$aLang.user}</div></th>
			<th class="cell-skill cell-tab"><div class="cell-tab-inner">Настроить</div></th>
			<th class="cell-skill cell-tab"><div class="cell-tab-inner">Удалить</div></th>
		</tr>
	</thead>

	<tbody>
		{if $aUsersList}
			{foreach from=$aUsersList item=oUserList}
				<tr id="ignore-user-{$oUserList->getId()}">
					<td class="cell-name">
						<a href="{$oUserList->getUserWebPath()}"><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
						<div class="name {if !$oUserList->getProfileName()}no-realname{/if}">
							<p class="username word-wrap"><a href="{$oUserList->getUserWebPath()}">{$oUserList->getLogin()}</a></p>
							{if $oUserList->getProfileName()}<p class="realname">{$oUserList->getProfileName()}</p>{/if}
						</div>
					</td>
					<td style="text-align: center;"><a href="#" class="js-title-ignore" onclick="ls.ignore.showIgnore(this,{$oUserList->getId()}); return false;" title="{$aLang.plugin.ignore.user_ignore_from}: {$oUserList->getCountIgnore()}">Настроить</a></td>
					<td style="text-align: center;"><a href="#" onclick="ls.ignore.deleteIgnore(this,{$oUserList->getId()}, '#ignore-user-{$oUserList->getId()}'); return false;">Удалить</a></td>
				</tr>
			{/foreach}
		{else}
			<tr>
				<td colspan="3">
					{if $sUserListEmpty}
						{$sUserListEmpty}
					{else}
						{$aLang.user_empty}
					{/if}
				</td>
			</tr>
		{/if}
	</tbody>
</table>

{include file='footer.tpl'}