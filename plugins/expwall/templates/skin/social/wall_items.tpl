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
    };

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
    {assign var="aReplyWall" value=$oWall->getLastReplyWall()}
    <div id="wall-item-{$oWall->getId()}" class="js-wall-item comment-wrapper">
        <div class="comment">
            <a href="{$oWallUser->getUserWebPath()}"><img src="{$oWallUser->getProfileAvatarPath(48)}" alt="avatar"
                                                          class="comment-avatar"/></a>

            <ul class="comment-info">
                <li class="comment-author"><a href="{$oWallUser->getUserWebPath()}">{$oWallUser->getLogin()}</a></li>
                <li class="comment-date">
                    <time datetime="{date_format date=$oWall->getDateAdd() format='c'}">
                        {date_format date=$oWall->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                    </time>
                </li>
                {if $oUserCurrent}
                    <li><a href="#" class="link-dotted wall-a"
                           onclick="return loadReplyNext({$oWall->getId()});">{$aLang.plugin.expwall.comments}</a></li>
                    <li><a href="#" class="link-dotted wall-a"
                           onclick="return ls.wall.toggleReply({$oWall->getId()});">{$aLang.wall_action_reply}</a></li>
                {/if}
                {if $oWall->isAllowDelete()}
                    <li><a href="#" onclick="return ls.wall.remove({$oWall->getId()});"
                           class="link-dotted">{$aLang.wall_action_delete}</a></li>
                {/if}
            </ul>

            <div class="comment-content text">
                {$oWall->getText()}
            </div>

        </div>


        <div id="wall-reply-container-{$oWall->getId()}" class="comment-wrapper">

        </div>

        {if $oUserCurrent}
            <form class="wall-submit wall-submit-reply" style="display: none">
            <textarea rows="4"
                      id="wall-reply-text-{$oWall->getId()}"
                      class="input-text input-width-full js-wall-reply-text"
                      placeholder="{$aLang.wall_reply_placeholder}"
                      onclick="return ls.wall.expandReply({$oWall->getId()});"></textarea>
                <button type="button"
                        onclick="addReply(jQuery('#wall-reply-text-{$oWall->getId()}').val(), {$oWall->getId()}, {$oWallUser->getId()});"
                        class="button button-primary js-button-wall-submit">{$aLang.wall_reply_submit}</button>
            </form>
        {/if}
    </div>
{/foreach}
