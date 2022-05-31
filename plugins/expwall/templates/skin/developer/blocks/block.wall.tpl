<section class="block block-type-wall">
    <header class="block-header sep">
        <h3><a href="{router page='wall'}">{$aLang.plugin.expwall.title_block}</a></h3>
    </header>

    <div class="js-block-stream-content">
        <div class="js-block-wall-content" style="margin: 10px;">
            {if $aWall}
                {foreach from=$aWall item=oWall}
                    {assign var="oWallUser" value=$oWall->getUser()}
                    {assign var="aReplyWall" value=$oWall->getLastReplyWall()}
                    <div id="wall-item-{$oWall->getId()}" class="js-wall-item comment-wrapper">
                        <div class="comment">
                            <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}"
                                                                          alt="avatar" class="comment-avatar"/></a>

                            <ul class="comment-info">
                                <li class="comment-author"><a
                                            href="{$oWallUser->getUserWebPath()}">{$oWallUser->getLogin()}</a></li>
                                <li class="comment-date">
                                    <time datetime="{date_format date=$oWall->getDateAdd() format='c'}">
                                        {date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                                    </time>
                                </li>
                            </ul>

                            <div class="comment-content text">
                                {$oWall->getText()}
                            </div>

                        </div>

                    </div>
                {/foreach}
            {/if}

        </div>
    </div>
</section>