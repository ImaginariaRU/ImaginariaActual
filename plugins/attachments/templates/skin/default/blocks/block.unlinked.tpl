{if $aFiles}
    <!-- Attachments plugin -->
    <div class="block white">
        <h1 title="{$aLang.plugin.attachments.not_attached_files_title}">{$aLang.plugin.attachments.not_attached_files}</h1>

        <div id="Attachments_SidebarUnattachedFileListIDContainer">
            {foreach from=$aFiles item=oFile name="oFileIteract"}
                <div id="UnattachedFileID{$oFile.attachment_id}"
                     class="UnattachedFileList {if $smarty.foreach.oFileIteract.iteration % 2 == 1}secondLine{/if}">
                    <div class="DeleteThisFile"
                         onclick="ls.attachments.DeleteFileFromSidebarFileList ('{$oFile.attachment_id}');"
                         title="{$aLang.plugin.attachments.element_title_delete_file}"></div>
                    <div class="AttachThisFileToNewTopic"
                         onclick="ls.attachments.AttachThisFileToNewTopicByID ('{$oFile.attachment_id}', '{$oFile.attachment_name}');"
                         title="{$aLang.plugin.attachments.attach_file_to_new_topic}"></div>
                    <a class="CurFileA" href="{router page='attachments'}get/{$oFile.attachment_id}"
                       title="{$aLang.plugin.attachments.element_title_download_file}">{$oFile.attachment_name}</a>
                </div>
            {/foreach}
        </div>
    </div>
    <!-- /Attachments plugin -->
{/if}
