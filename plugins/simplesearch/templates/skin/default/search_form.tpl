<!-- Simplesearch plugin -->
<script>
    jQuery(document).ready(function ($) {
        $('div.SearchContainer div.FoundBy').bind('click.ss', function () {
            $(this).next('div.MoreInfo').slideToggle();
        });
        $('div.SearchContainer div.ShowMoreOptions').bind('click.ss', function () {
            $(this).toggleClass('Active');
            $(this).next('div.MoreOptions').slideToggle();
        });
    });
</script>

<form action="{router page='search'}{$aReq.sType}/" method="GET" class="search">
    {hook run='search_form_begin'}
    <input type="text" value="{$aReq.q|escape:'html'}" name="q"
           placeholder="{$aLang.plugin.simplesearch.Search_Field_Tip}"
           maxlength="{$oConfig->GetValue("plugin.simplesearch.Max_Length_Of_Search_Query")}" id="SS_SearchField"
           class="input-text"/>
    <input type="submit" value="" title="{$aLang.search_submit}" class="input-submit icon icon-search"/>

    <div class="ShowMoreOptions{if $iSearchInOneBlogSelected or $iFavoriteUserId} Active{/if}">{$aLang.plugin.simplesearch.Blogs_Search_Show_More_Options}</div>
    <div class="MoreOptions{if $iSearchInOneBlogSelected or $iFavoriteUserId} Active{/if}">
        {$aLang.plugin.simplesearch.Blogs_Search_Here_Only}
        <select name="blogid" class="input-width-250" onchange="this.form.submit();">
            <option value="0">{$aLang.plugin.simplesearch.Blogs_Search_Here_Only_Disabled}</option>
            {foreach from=$aOpenCloseBlogsForCurrentUserObj item=oBlog}
                <option value="{$oBlog->getId()}"
                        {if $iSearchInOneBlogSelected==$oBlog->getId()}selected="selected"{/if}>{$oBlog->getTitle()|escape:'html'}</option>
            {/foreach}
        </select>
        {if $oUserCurrent and in_array($aReq.sType, array('topics', 'comments', 'tags'))}
            <label>
                <input type="checkbox" name="favorite" value="1" {if $iFavoriteUserId}checked="checked"{/if}
                       onchange="this.form.submit();"/>{$aLang.plugin.simplesearch.Search_In_Favorite}
            </label>
        {/if}
    </div>

    {hook run='search_form_end'}
</form>
<!-- /Simplesearch plugin -->
