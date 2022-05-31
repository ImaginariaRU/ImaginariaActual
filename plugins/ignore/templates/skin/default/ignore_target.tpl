<div id="ignore-target-block" class="ignore-target-block">
    <p class="title"><span>{$iCount}</span> {$aLang.plugin.ignore.target_block_user}</p>
    <div><a href="#" onclick="ls.ignore.showIgnoreTarget(this, {$iTargetId},'{$sTargetType}', 0); return false;">Добавить</a></div>
    <p class="users"{if !$aIgnoreUser} style="display: none;"{/if}>
    {if $aIgnoreUser}
		{foreach from=$aIgnoreUser item=oUser name=users}
			<a href="#" onclick="ls.ignore.showIgnoreTarget(this, {$iTargetId},'{$sTargetType}', {$oUser->getId()}); return false;">{$oUser->getLogin()}</a>{if !$smarty.foreach.users.last}, {/if}								      		
		{/foreach}
    {/if}
    </p>
</div>