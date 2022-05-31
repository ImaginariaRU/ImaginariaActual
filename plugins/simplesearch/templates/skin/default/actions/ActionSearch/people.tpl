{include file='header.tpl'}

<script>
    jQuery(document).ready(function ($) {
        $('div.SearchContainer div.FoundBy').bind('click', function () {
            $(this).parent().children('div.MoreInfo').slideToggle();
        });
    });
</script>

<div class="SearchContainer">

    <h2 class="page-header">{$aLang.search_results}: {$aReq.q|escape:'html'}</h2>

    {include file="{$aTemplatePathPlugin.simplesearch}actions/ActionSearch/switcher.tpl"}

    {if $bIsResults}
        {if $aReq.sType == 'people'}

            {if $aUsersRating}
                <table class="table table-users">
                    <thead>
                    <tr>
                        <th class="cell-name">{$aLang.user}</th>
                        <th>&nbsp;</th>
                        <th class="cell-skill">{$aLang.user_skill}</th>
                        <th class="cell-rating cell-tab">
                            <div class="cell-tab-inner active"><span>{$aLang.user_rating}</span></div>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    {foreach from=$aUsersRating item=oUser name="aUsersRatingCycle"}
                        <tr>
                            <td class="cell-name">
                                <a href="{$oUser->getUserWebPath()}"><img src="{$oUser->getProfileAvatarPath(48)}"
                                                                          alt="avatar" class="avatar"/></a>

                                <div class="name {if !$oUser->getProfileName()}no-realname{/if}">
                                    <p class="username word-wrap"><a
                                                href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></p>
                                    {if $oUser->getProfileName()}<p class="realname">{$oUser->getProfileName()}</p>{/if}
                                </div>

                                <br/>
                                <div class="FoundBy">
                                    {$aLang.plugin.simplesearch.People_Search_Found}
                                    {assign var="sVal" value="s`$aMatching[$smarty.foreach.aUsersRatingCycle.iteration-1]`"}
                                    {$aLang.plugin.simplesearch.People_Search_Found_By.$sVal}
                                </div>
                                <div class="MoreInfo">
                                    {$oUser->getFoundPlace()}
                                </div>
                            </td>
                            <td>
                                {if $oUserCurrent}
                                    <a href="{router page='talk'}add/?talk_users={$oUser->getLogin()}">
                                        <button type="submit" class="button button-action button-action-send-message"><i
                                                    class="icon-synio-send-message"></i><span>{$aLang.user_write_prvmsg}</span>
                                        </button>
                                    </a>
                                {/if}
                            </td>
                            <td class="cell-skill">{$oUser->getSkill()}</td>
                            <td class="cell-rating {if $oUser->getRating() < 0}negative{/if}">
                                <strong>{$oUser->getRating()}</strong></td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            {else}
                {$aLang.user_empty}
            {/if}

            {include file='paging.tpl' aPaging="$aPaging"}


        {else}
            ACHTUNG, dirty boy! {* not people action *}
        {/if}

    {else}
        {$aLang.search_results_empty}
        <br/>
        {include file="{$aTemplatePathPlugin.simplesearch}search_in_big_se.tpl"}
    {/if}

</div>

{include file='footer.tpl'}