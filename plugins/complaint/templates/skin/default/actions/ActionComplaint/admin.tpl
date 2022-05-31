{assign var="noSidebar" value=true}
{include file='header.tpl'}

<h2 class="page-header">
    {$aLang.plugin.complaint.complaints}
</h2>

<div id="complaints-list-original">
    <table class="table table-complaints">
        <thead>
        <tr>
            <th class="cell-tab">
                {$aLang.plugin.complaint.list.date_add}
            </th>
            <th class="cell-tab">
                {$aLang.plugin.complaint.list.user}
            </th>
            <th class="cell-tab">
                {$aLang.plugin.complaint.list.text}
            </th>
            <th class=cell-tab">
                {$aLang.plugin.complaint.list.topic}
            </th>
            <th class="cell-tab">
                {$aLang.plugin.complaint.list.author}
            </th>
        </tr>
        </thead>
        <tbody>
        {if $aComplaints}
            {foreach from=$aComplaints item=oComplaint}
                {assign var="oTopic" value=$oComplaint->getTopic()}
                {assign var="oTopicUser" value=$oTopic->getUser()}
                {assign var="oUser" value=$oComplaint->getUser()}
                <tr>
                    <td>
                        {$oComplaint->getDateAdd()}
                    </td>
                    <td>
                        {if $oUser}
                        <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a>
                        {else}
                            {$aLang.plugin.complaint.list.user_not_found}
                        {/if}
                    </td>
                    <td>
                        {$oComplaint->getText()}
                    </td>
                    <td>
                        {if $oTopic}
                        <a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a>
                        {else}
                            {$aLang.plugin.complaint.list.topic_not_found}
                        {/if}
                    </td>
                    <td>
                        {if $oTopicUser}
                        <a href="{$oTopicUser->getUserWebPath()}">{$oTopicUser->getLogin()}</a>
                        {else}
                            {$aLang.plugin.complaint.list.user_not_found}
                        {/if}
                    </td>
                </tr>
            {/foreach}
        {else}
            <tr>
                <td colspan="4">
                    {$aLang.plugin.complaint.list.empty}
                </td>
            </tr>
        {/if}
        </tbody>
    </table>
    {include file='paging.tpl' aPaging=$aPaging}
</div>

{include file='footer.tpl'}