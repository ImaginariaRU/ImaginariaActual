{include file='header.tpl'}

{include file='editor.tpl'}

<h2 class="page-header">{$aLang.plugin.expwall.title_block}</h2>

<script type="text/javascript">
    var ipage = 2;
    ls.wall.init({
        login: '{if $oUserCurrent}{$oUserCurrent->getLogin()}{/if}'
    });

    jQuery(document).ready(function ($) {
        $("textarea").charCount({
            allowed: 250,
            warning: 0
        });
    });
    {literal}
    function loadNext() {
        var divLast = $('#wall-container').find('.js-wall-item:last');
        if (divLast.length) {
            var idLess = divLast.attr('id').replace('wall-item-', '');
        } else {
            return false;
        }
        $('#wall-button-next').addClass('loader');
        var params = { 'ipage': ipage};
        var url = aRouter['wall'] + 'ajax/load/';
        ls.ajax(url, params, function (result) {
            if (result.bStateError) {
                ls.msg.error(null, result.sMsg);
            } else {
                if (result.iCountWall) {
                    $('#wall-container').append(result.sText);
                    ipage++;
                }
                var iCount = iCount - result.iCountWallReturn;
                if (iCount) {
                    $('#wall-count-next').text(iCount);
                } else {
                    $('#wall-button-next').detach();
                }
                ls.hook.run('ls_wall_loadnext_after', [idLess, result]);
            }
            $('#wall-button-next').removeClass('loader');
        }.bind(this));
        return false;
    }
    ;
    {/literal}
</script>

{if $oUserCurrent}
    <div id="wall-item-0">
        <form class="wall-submit wall-submit-reply wall-submit-parent">
        <textarea rows="4"
                  id="wall-text"
                  class="mce-editor markitup-editor input-text input-width-full js-wall-reply-parent-text"
                  placeholder="{$aLang.wall_add_title}"
                  onclick="return ls.wall.expandReply(0);" style="height: 100px;"></textarea>

            <button type="button" onclick="ls.wall.add(jQuery('#wall-text').val(),0);"
                    class="button button-primary js-button-wall-submit">{$aLang.wall_add_submit}</button>
        </form>
    </div>
{else}
    <div class="wall-note">
        <h3>{$aLang.wall_add_quest}</h3>
    </div>
{/if}

{if !count($aWall)}
    <div class="wall-note" id="wall-note-list-empty">
        <h3>{$aLang.wall_list_empty}</h3>
    </div>
{/if}

<div id="wall-container" class="wall">
    {include file="$sTPWall/wall_items.tpl"}
</div>


{if $iCountWall-count($aWall)}
    <a href="#" onclick="return loadNext();" id="wall-button-next" class="stream-get-more"><span
                class="wall-more-inner">{$aLang.wall_load_more} (<span
                    id="wall-count-next">{$iCountWall-count($aWall)}</span>)</span></a>
{/if}

{include file='footer.tpl'}