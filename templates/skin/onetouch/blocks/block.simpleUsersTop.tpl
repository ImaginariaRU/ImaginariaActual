{if $simpletpl_aUsersTop and count($simpletpl_aUsersTop)}
<div class="top-authors">
    <ul>
        <li class="title">
			{$aLang.top_users}<br />
            <span>{$aLang.top_users_info}</span>
        </li>
		{foreach from=$simpletpl_aUsersTop item=oUser}
            <li><a href="{$oUser->getUserWebPath()}" title="{$oUser->getLogin()}"><img src="{$oUser->getProfileAvatarPath(48)}" /></a></li>
		{/foreach}
    </ul>
</div>
{/if}