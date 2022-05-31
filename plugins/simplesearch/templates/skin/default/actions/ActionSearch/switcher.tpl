<ul class="nav nav-pills">
    {foreach from=$aRes.aCounts item=iCount key=sType name="sTypes"}
        <li {if $aReq.sType == $sType}class="active"{/if}>
            <a href="{router page='search'}{$sType}/?q={$aReq.q|escape:'html'}">
                {$iCount}
                {if $sType=="topics"}
                    {$aLang.search_results_count_topics}
                {elseif $sType=="comments"}
                    {$aLang.search_results_count_comments}
                {elseif $sType=="people"}
                    {$aLang.plugin.simplesearch.People_Search_Menu}
                {elseif $sType=="blogsdescriptions"}
                    {$aLang.plugin.simplesearch.Blogs_Search_Menu}
                {elseif $sType=="tags"}
                    {$aLang.plugin.simplesearch.Tags_Search_Menu}
                {/if}
            </a>
        </li>
    {/foreach}
</ul>
