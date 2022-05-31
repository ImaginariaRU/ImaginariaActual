<!-- Attachments plugin -->
<script>
    // --- code ---
    var Attachments_FormUploadAction = "{router page='attachments'}receive";
    var Attachments_PosibleFileExtensions = "{$oConfig->GetValue("plugin.attachments.valid_extensions")}";
    var Attachments_FileFormPlace = "{$oConfig->GetValue("plugin.attachments.AttachmentsFileFormPlace")}";
    var Attachments_PathRootWeb = "{$oConfig->GetValue("path.root.web")}";
    var Attachments_AttachmentsAction = "{router page='attachments'}";
    // --- msgs ---
    var Attachments_AddFileMsg = "{$aLang.plugin.attachments.new_topic_add_file_msg}";
    var Attachments_DefaultInfoMsg = "{$aLang.plugin.attachments.new_topic_first_message}";
    var Attachments_UploadingFileNow = "{$aLang.plugin.attachments.new_topic_uploading_file_now}";
    var Attachments_Not_Allowed_File_Types = "{$aLang.plugin.attachments.new_topic_error_not_allowed_file_types}";
    var Attachments_UploadDone = "{$aLang.plugin.attachments.new_topic_file_upload_done}";
    var Attachments_SidebarDetectionError = "{$aLang.plugin.attachments.sidebar_detection_general_error}";
    var Attachments_WrongFileFormPlace = "{$aLang.plugin.attachments.wrong_parameter_file_form_place}";
    var Attachments_DoYouReallyWantToDeleteThisFile = "{$aLang.plugin.attachments.do_you_really_want_to_delete_this_file}";
    var Attachments_ElementTitleDownloadFile = "{$aLang.plugin.attachments.element_title_download_file}";
    var Attachments_ElementTitleDeleteFile = "{$aLang.plugin.attachments.element_title_delete_file}";
    var Attachments_FileWasDeleted = "{$aLang.plugin.attachments.file_was_deleted}";
    var Attachments_CantAttachToSavedTopic = "{$aLang.plugin.attachments.cant_attach_file_to_saved_topic}";
    var Attachments_ThereIsNoUnattachedFiles = "{$aLang.plugin.attachments.there_is_no_unattached_files}";
    // --- topic ID ---
    var Attachments_CurrentTopicID = "{$_aRequest.topic_id}";
    var Attachments_NewFormID = "";

    // --- adding attached to topic files ---
    {if $aFiles}
    jQuery(document).ready(function ($) {literal}{{/literal}
        {foreach from=$aFiles item=oFile}
        ls.attachments.AddFileToList("{$oFile.attachment_name}", "{$oFile.attachment_id}");
        {/foreach}
        {literal}}{/literal});
    {else}
    // no attached files in topic
    {/if}

    {literal}
    if ($.trim(Attachments_CurrentTopicID) == '') {
        Attachments_NewFormID = GetRandomValue(10, 99).toString() +
            GetRandomValue(10, 99).toString() +
            GetRandomValue(10, 99).toString() +
            GetRandomValue(10, 99).toString() +
            GetRandomValue(10, 99).toString() +
            GetRandomValue(10, 99).toString(); // generating ID for new form
    }
    jQuery(document).ready(function ($) {
        var Attachments_IframeName = 'att_upload_target';
        var FileUploadCode = '<form id="att_file_upload_form" method="post" enctype="multipart/form-data" action="' + Attachments_FormUploadAction + '" target="' + Attachments_IframeName + '">' + Attachments_AddFileMsg +
            '<input name="newfile" size="15" type="file" onchange="ls.attachments.CheckUpFileType (this);" id="att_newfilefield">' +
            '<input name="topic_id" type="hidden" value="' + Attachments_CurrentTopicID + '">' +
            '<input name="form_id" type="hidden" value="' + Attachments_NewFormID + '">' +
            '<div id="AttFileOperationInfoBox"></div><div id="AttFileListBox"></div>' +
            '<iframe id="att_upload_frame" name="' + Attachments_IframeName + '" src=""></iframe>' +
            '</form>';

        var PlaceFindHelper = $('input#topic_title').parent(); // p

        if ((Attachments_FileFormPlace == 'before_text') && (!ls.attachments.IsThisIE())) {
            // insert before textarea and panel
            PlaceFindHelper.parent() [0].insertBefore($('<div />', {
                'class': 'FileUploadBox',
                'html': FileUploadCode
            }) [0], PlaceFindHelper [0].nextSibling);
        } else if ((Attachments_FileFormPlace == 'sidebar') || (ls.attachments.IsThisIE())) {
            // put in sidebar
            var DIVOnSidebar = $('div.Attachments_Sidebar_Place');
            if (DIVOnSidebar.length != 0) {
                DIVOnSidebar.parent() [0].insertBefore($('<div />', {
                    'class': 'FileUploadBox SidebarAdditionalParams',
                    'html': FileUploadCode,
                }) [0], DIVOnSidebar [0]);
            } else {
                alert(Attachments_SidebarDetectionError);
                return false;
            }
        } else {
            alert(Attachments_WrongFileFormPlace);
            return false;
        }

        // attachments init
        ls.attachments.ShowInfoMsg(null);
        ls.attachments.RefreshFilelist();
    });

    {/literal}
    // main form update init
    document.write('<input name="form_id_topic" type="hidden" value="' + Attachments_NewFormID + '" />');
</script>
<!-- /Attachments plugin -->
