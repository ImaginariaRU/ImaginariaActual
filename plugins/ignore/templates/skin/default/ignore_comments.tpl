{if !$oComment->getDelete() and $oUserCurrent}
    {if $oUserCurrent->getId() != $oUser->getId()}
        <li class="ignore-comment-user"><a href="#" class="link-dotted js-title-ignore" onclick="ls.ignore.showIgnore(this,{$oUser->getId()}); return false;" title="{$aLang.plugin.ignore.user_ignore_from}: {$oUser->getCountIgnore()}">{$aLang.plugin.ignore.ignore}</a><span style="display: none;" class="ignore-comment-user-id">{$oUser->getId()}</span></li>
    {/if}
{/if}