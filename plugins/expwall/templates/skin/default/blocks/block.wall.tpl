<section class="block block-type-wall">
    <header class="block-header sep">
        <h3><a href="{router page='wall'}">{$aLang.plugin.expwall.title_block}</a></h3>
    </header>

    {* ПАТЧ СТЕНЫ, СУКА ТАБЛИЧНАЯ ВЁРСТКА *}

    <div class="block-content">
        <div class="js-block-wall-content">
            {if $aWall}
                {foreach from=$aWall item=oWall}
                    {assign var="oWallUser" value=$oWall->getUser()}
                    {assign var="aReplyWall" value=$oWall->getLastReplyWall()}
                    <div id="wall-item-{$oWall->getId()}" class="js-wall-item wall-item-wrapper" style="padding-left: 0px; padding-top: 10px; margin-bottom: 10px"> <!-- KW PATCH -->
                        <div class="wall-item">
                            <table width="100%" border="0">
                                <tr>
                                    <td width="50">
                                        <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" style="position: relative; width: 48px; height: 48px;"/></a>
                                    </td>
                                    <td align="left" class="info">
                                        <a href="{$oWallUser->getUserWebPath()}wall/">{if $oWallUser->getProfileName()}{$oWallUser->getProfileName()}{else}{$oWallUser->getLogin()}{/if}</a>
                                        <br><br>
                                        <time class="date"
                                              datetime="{date_format date=$oWall->getDateAdd() format='c'}">{date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        {$oWall->getText()}
                                    </td>
                                </tr>
                            </table>
                            {*
                            <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar" class="avatar" style="position: relative"/></a>

                            <p class="info">
                                <a href="{$oWallUser->getUserWebPath()}wall/">{if $oWallUser->getProfileName()}{$oWallUser->getProfileName()}{else}{$oWallUser->getLogin()}{/if}</a>
                                ·
                                <time class="date"
                                      datetime="{date_format date=$oWall->getDateAdd() format='c'}">{date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
                            </p>

                            <div class="wall-item-content text">
                                {$oWall->getText()}
                            </div>*}

                        </div>


                    </div>
                {/foreach}
            {/if}

        </div>
    </div>
</section>