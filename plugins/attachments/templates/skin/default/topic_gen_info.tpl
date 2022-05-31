{if $oConfig->GetValue("plugin.attachments.ShowAttachedFiles") or (($oUserCurrent and $oUserCurrent->isAdministrator()) and ($oConfig->GetValue("plugin.attachments.ShowAttachedFilesForAdmins")))}
    {assign var="AttachedFilesList" value=$oTopic->getAttachments()}
    {if $AttachedFilesList}
        <!-- Attachments plugin -->
        <li class="FilesInTopic"
            title="{$aLang.plugin.attachments.topic_files_attached}{$AttachedFilesList|@count}{$aLang.plugin.attachments.topic_N_files}">
            {$AttachedFilesList|@count}
        </li>
        <!-- /Attachments plugin -->
    {/if}
{/if}
