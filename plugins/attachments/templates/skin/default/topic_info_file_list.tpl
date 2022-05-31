{if $oConfig->GetValue("plugin.attachments.ShowAttachedFiles") or (($oUserCurrent and $oUserCurrent->isAdministrator()) and ($oConfig->GetValue("plugin.attachments.ShowAttachedFilesForAdmins")))}
    {assign var="AttachedFilesList" value=$oTopic->getAttachments()}
    {if $AttachedFilesList}
        <!-- Attachments plugin -->
        <div class="AttachmentsInTopic">
            {$aLang.plugin.attachments.files_in_topics}

            {foreach from=$AttachedFilesList item=oFile name=nFileList}
                <a
                href="{router page='attachments'}get/{$oFile.attachment_id}">{$oFile.attachment_name}</a>{if !$smarty.foreach.nFileList.last}, {/if}
            {/foreach}
        </div>
        <!-- /Attachments plugin -->
    {/if}
{/if}
