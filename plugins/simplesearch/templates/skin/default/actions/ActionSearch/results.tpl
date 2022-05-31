{include file='header.tpl'}

<div class="SearchContainer">

    <h2 class="page-header">{$aLang.search_results}</h2>

    {include file="{$aTemplatePathPlugin.simplesearch}search_form.tpl"}

    {include file="{$aTemplatePathPlugin.simplesearch}actions/ActionSearch/switcher.tpl"}

    {if $bIsResults}
        {if $aReq.sType == 'topics'}

            {* here we make our own topic_list.tpl coz we need special headers that will describe found info *}

            {assign var="sPreviousMatch" value=""}

            {foreach from=$aTopics item=oTopic name="aTopicsCycle"}
                {assign var="sVal" value="s`$aMatching[$smarty.foreach.aTopicsCycle.iteration-1]`"}
                {if $sPreviousMatch!=$sVal}

                    {* show title of current search match (strict or not and where information was found) *}
                    <div class="TitleWhereFound">
                        {$aLang.plugin.simplesearch.Search_Found_By.$sVal}
                        {$aLang.plugin.simplesearch.Search_Found}
                    </div>
                    {assign var="sPreviousMatch" value=$sVal}

                {/if}

                {include file="topic_topic.tpl" bTopicList=true}
            {/foreach}

            {include file='paging.tpl' aPaging="$aPaging"}

        {elseif $aReq.sType == 'comments'}

            {* here we make our own comment_list.tpl coz we need special headers that will describe found info *}

            {assign var="sPreviousMatch" value=""}

            {foreach from=$aComments item=oComment name="aCommentsCycle"}
                {assign var="sVal" value="s`$aMatching[$smarty.foreach.aCommentsCycle.iteration-1]`"}
                {if $sPreviousMatch!=$sVal}

                    {* show title of current search match (strict or not and where information was found) *}
                    <div class="TitleWhereFound">
                        {$aLang.plugin.simplesearch.Search_Found_By.$sVal}
                        {$aLang.plugin.simplesearch.Search_Found}
                    </div>
                    {assign var="sPreviousMatch" value=$sVal}

                {/if}

                {include file="comment_list.tpl" aComments=array($oComment) aPaging=false}
            {/foreach}

            {include file='paging.tpl' aPaging="$aPaging"}

        {elseif $aReq.sType == 'blogsdescriptions'}

            {* here we make our own blog_list.tpl coz we need special headers that will describe found info *}

            {assign var="sPreviousMatch" value=""}

            {foreach from=$aBlogs item=oBlog name="aBlogsCycle"}
                {assign var="sVal" value="s`$aMatching[$smarty.foreach.aBlogsCycle.iteration-1]`"}
                {if $sPreviousMatch!=$sVal}

                    {* show title of current search match (strict or not and where information was found) *}
                    <div class="TitleWhereFound">
                        {$aLang.plugin.simplesearch.Search_Found_By.$sVal}
                        {$aLang.plugin.simplesearch.Search_Found}
                    </div>
                    {assign var="sPreviousMatch" value=$sVal}

                {/if}

                {include file="blog_list.tpl" aBlogs=array($oBlog)}
                <div class="FoundBy">{$aLang.plugin.simplesearch.Blogs_Search_Show_Description}</div>
                <div class="MoreInfo">
                    {$oBlog->getDescription()}
                </div>
            {/foreach}

            {include file='paging.tpl' aPaging="$aPaging"}

        {elseif $aReq.sType == 'tags'}

            {include file="topic_list.tpl"}

        {else}
            {hook run='search_result' sType=$aReq.sType}

        {/if}
    {else}
        {$aLang.search_results_empty}
        <br/>
        {include file="{$aTemplatePathPlugin.simplesearch}search_in_big_se.tpl"}
    {/if}

</div>

{include file='footer.tpl'}