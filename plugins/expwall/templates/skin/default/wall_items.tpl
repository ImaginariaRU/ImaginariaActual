<script type="text/javascript">
    {literal}
    function loadReplyNext(iPid, tr) {
        var divContaner = $('#wall-item-replies-' + iPid);
        if (!tr)
            if (!divContaner.hasClass('open')) {
                divContaner.show();
                $('#wall-reply-container-' + iPid).html('<br />');
                $('#id-rep-1-' + iPid).hide();
                $('#id-rep-2-' + iPid).show();
                divContaner.addClass('open');
            } else {
                divContaner.hide();
                $('#wall-item-' + iPid).children('.wall-submit-reply').hide();
                $('#id-rep-1-' + iPid).show();
                $('#id-rep-2-' + iPid).hide();
                divContaner.removeClass('open');
                return false;
            }
        var params = {iId: iPid};
        var url = aRouter['wall'] + 'ajax/load-reply/';
        ls.ajax(url, params, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                if (result.iCountWall) {
                    $('#wall-reply-container-' + iPid).html(result.sText);
                    //$('#wall-item-'+iPid).children('.wall-submit-reply').show();
                }
            }
        });
        return false;
    }
    ;
    this.addReply = function (sText, iPid, iuser) {
        $('.js-button-wall-submit').attr('disabled', true);
        var url = aRouter['wall'] + 'ajax/add/';
        var params = {sText: sText, iPid: iPid, iuser: iuser};

        ls.hook.marker('addReplyBefore');
        $('#wall-reply-text-' + iPid).addClass('loader');
        ls.ajax(url, params, function (result) {
            $('.js-button-wall-submit').attr('disabled', false);
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                $('.js-wall-reply-text').val('');
                loadReplyNext(iPid, 1);
            }
            $('#wall-reply-text-' + iPid).removeClass('loader');
        }.bind(this));
        return false;
    };
    {/literal}
</script>


{foreach from=$aWall item=oWall}
    {assign var="oWallUser" value=$oWall->getUser()}
    {assign var="aWallUser" value=$oWall->getWallUser()}
    {assign var="aReplyWall" value=$oWall->getLastReplyWall()}
    <div id="wall-item-{$oWall->getId()}" class="js-wall-item wall-item-wrapper">
        <div class="wall-item">
            <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar"
                                                          class="avatar"/></a>

            <p class="info">
                <a href="{$oWallUser->getUserWebPath()}">{if $oWallUser->getProfileName()}{$oWallUser->getProfileName()}{else}{$oWallUser->getLogin()}{/if}</a>
                {if $oWallUser->getLogin()!=$aWallUser->getLogin()}
                    >>>
                    <a href="{$aWallUser->getUserWebPath()}">{if $aWallUser->getProfileName()}{$aWallUser->getProfileName()}{else}{$aWallUser->getLogin()}{/if}</a>
                {/if}
                <time class="date"
                      datetime="{date_format date=$oWall->getDateAdd() format='c'}">{date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}</time>
            </p>

            <div class="wall-item-content text">
                {$oWall->getText()}
            </div>

            {if $oUserCurrent}
                <ul class="actions wall-item-actions">
                    <li><a href="#" class="link-dotted"
                           onclick="return loadReplyNext({$oWall->getId()});">{$aLang.plugin.expwall.comments}</a>
                    </li>

                    <li><a href="#" class="link-dotted" onclick="ls.wall.toggleReply({$oWall->getId()}); return false;"
                           id="id-rep-1-{$oWall->getId()}">{$aLang.wall_action_reply}</a></li>

                    {if $oWall->isAllowDelete()}
                        <li><a href="#" onclick="return ls.wall.remove({$oWall->getId()});"
                               class="link-dotted">{$aLang.wall_action_delete}</a></li>
                    {/if}
                </ul>
            {/if}
        </div>


        <div class="wall-item-replies" id="wall-item-replies-{$oWall->getId()}" style="display: none;">


            <div class="wall-item-container" id="wall-reply-container-{$oWall->getId()}">

            </div>
        </div>
        <a href="#" class="link-dotted" onclick="ls.wall.toggleReply({$oWall->getId()}); return false;"
           id="id-rep-2-{$oWall->getId()}" style="display: none;">{$aLang.wall_action_reply}</a>


        {if $oUserCurrent}
            <form class="wall-submit wall-submit-reply" style="display: none">
            <textarea rows="4" id="wall-reply-text-{$oWall->getId()}"
                      class="mce-editor input-text input-width-full js-wall-reply-text"
                      placeholder="{$aLang.wall_reply_placeholder}"
                      onclick="return ls.wall.expandReply({$oWall->getId()});"></textarea>
                <button type="button"
                        onclick="addReply(jQuery('#wall-reply-text-{$oWall->getId()}').val(), {$oWall->getId()}, {$aWallUser->getId()});"
                        class="button button-primary js-button-wall-submit">{$aLang.wall_reply_submit}</button>
            </form>
        {/if}
    </div>
{/foreach}