{if $aProfileIgnoreTo}
    {if $bProfileIgnore}
        <h2 class="header-table mb-15"><a href="{router page='settings'}ignore/">{$aLang.plugin.ignore.user_ignore_i}</a> <span>{$iCountProfileIgnoreTo}</span></h2>
    {else}
        <h2 class="header-table mb-15">{$aLang.plugin.ignore.user_ignore_to} <span>{$iCountProfileIgnoreTo}</span></h2>
    {/if}
    <ul class="user-list-avatar">
    {foreach from=$aProfileIgnoreTo item=oIgnore}
    {assign var="oUserList" value=$oIgnore->getTarget()}
        <li>
            <a href="{$oUserList->getUserWebPath()}"{if $oIgnore->getReason()} title="{$oIgnore->getReason()}" class="js-title-ignore"{/if}><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
        </li>
    {/foreach}
    </ul>
{/if}
{if $aProfileIgnoreFrom}
    <h2 class="header-table mb-15">{$aLang.plugin.ignore.user_ignore_from} <span>{$iCountProfileIgnoreFrom}</span></h2>
    <ul class="user-list-avatar">
    {foreach from=$aProfileIgnoreFrom item=oIgnore}
    {assign var="oUserList" value=$oIgnore->getUser()}
        <li>
            <a href="{$oUserList->getUserWebPath()}"{if $oIgnore->getReason()} title="{$oIgnore->getReason()}" class="js-title-ignore"{/if}><img src="{$oUserList->getProfileAvatarPath(48)}" alt="avatar" class="avatar" /></a>
        </li>
    {/foreach}
    </ul>
{/if}