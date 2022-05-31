//  Attachments plugin

var ls = ls || {};

ls.attachments = (function ($) {

    this.FileList = [];
    this.CurrentFileName = '';

    // --- File types: Images of files ---

    this.GetImgForFileType = function (TFileName) {
        TFileType = '';
        DotPos = TFileName.lastIndexOf(".");
        if (DotPos != -1) TFileType = TFileName.substr(DotPos + 1);
        TFileType = TFileType.toLowerCase();

        ReturnValue = '';
        switch (TFileType) {
            case 'jpg':
            case 'png':
            case 'gif':
            case 'jpeg':
            case 'bmp':
            case 'jpe':
            case 'tiff':
            case 'psd':
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/image.png">';
                break;
            case 'mp3':
            case 'flac':
            case 'wav':
            case 'ogg':
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/music.png">';
                break;
            case 'torrent':
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/torrent.png">';
                break;
            case 'zip':
            case 'rar':
            case 'tar':
            case 'gz':
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/archive.gif">';
                break;
            case 'pdf':
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/pdf16x16.png">';
                break;
            default:
                ReturnValue = '<img class="SmallIcon" src="/plugins/attachments/templates/skin/default/images/FileTypes/file.png">';
        }
        return ReturnValue;
    }

    // ---

    Array.prototype.RemoveFileByID = function (file_id) {
        for (i = 0; i < this.length; i++) {
            if (file_id == this [i]['file_id']) {
                this.splice(i, 1);
                --i;
            }
        }
    }

    // --- Actions ---

    this.CheckUpFileType = function (TThisInput) {
        this.CurrentFileName = TThisInput.value;

        // browser`s security hack
        this.CurrentFileName = this.CurrentFileName.replace("C:\\fakepath\\", "");

        if ($.trim(Attachments_PosibleFileExtensions) != '') {
            // var PosFileExts = /\.txt|\.mp3|\.zip/i;
            var PosFileExts = '\\.' + Attachments_PosibleFileExtensions.replace(new RegExp(" ", 'g'), "|\\.");

            if (this.CurrentFileName.search(new RegExp(PosFileExts, 'gi')) == -1) {
                alert(Attachments_Not_Allowed_File_Types + Attachments_PosibleFileExtensions);
                this.FormAction('reset');
                return false;
            }
        }

        this.FormAction('submit');
        this.DisableFileField(true);
        this.ShowInfoMsg(Attachments_UploadingFileNow);
        return true;
    }

    // ---

    this.FileUploadDone = function (Attachments_Server_Side_Upload_Result_Value, Attachments_Server_Side_Last_File_ID) {
        this.DisableFileField(false);
        if ((Attachments_Server_Side_Upload_Result_Value != 'success') && (Attachments_Server_Side_Upload_Result_Value != '')) {
            this.ShowInfoMsg(Attachments_Server_Side_Upload_Result_Value);
            this.FormAction('reset');
            return false;
        }
        this.AddFileToList(this.CurrentFileName, Attachments_Server_Side_Last_File_ID);
        this.FormAction('reset');
        this.ShowInfoMsg(Attachments_UploadDone);
        this.RefreshFilelist();
    }

    // ---

    this.DisableFileField = function (FFieldDisabled) {
        $('#att_newfilefield') [0].disabled = FFieldDisabled;
    }

    // ---

    this.FormAction = function (TSFormAction) {
        if (TSFormAction == 'reset') {
            $('#att_newfilefield') [0].form.reset();
        } else if (TSFormAction == 'submit') {
            $('#att_newfilefield') [0].form.submit();
        }
    }

    // --- Files ---

    this.AddFileToList = function (TFilename, TFileID) {
        this.FileList.push(new Object());
        this.FileList [this.FileList.length - 1]['filename'] = TFilename;
        this.FileList [this.FileList.length - 1]['file_id'] = TFileID;
    }

    // ---

    this.RefreshFilelist = function () {
        TFileLength = this.FileList.length;
        $('#AttFileListBox').html('');
        if (TFileLength > 0) {
            $('#AttFileListBox').css('display', 'block');

            for (ic = 0; ic < TFileLength; ic++) {
                TCurFileName = this.FileList [ic]['filename'];
                TCurFileID = this.FileList [ic]['file_id'];

                $('#AttFileListBox').append("<div class=\"FileLine\" id=\"iFileLineID" + TCurFileID + "\">" +
                    this.GetImgForFileType(TCurFileName) +
                    "<a class=\"ListedFile\" href=\"" + Attachments_AttachmentsAction + "get/" + TCurFileID + "\" title=\"" +
                    Attachments_ElementTitleDownloadFile + "\">" + TCurFileName + "</a>" +
                    "<div class=\"DeleteFile\" onclick=\"ls.attachments.DeleteThisFile ('" + TCurFileID + "');\" title=\"" +
                    Attachments_ElementTitleDeleteFile + "\"></div>" + "</div>");
            }
        } else {
            $('#AttFileListBox').css('display', 'none');
        }

        // --- call external function ---
        if (typeof (Attachments_RFL_External) == 'function') {
            Attachments_RFL_External(this.FileList);
        }
    }

    // ---

    this.DirectFileDeletingFromServer = function (TFileID) {
        $.ajax({
            url: Attachments_AttachmentsAction + 'delete/' + TFileID,
            data: {
                security_ls_key: LIVESTREET_SECURITY_KEY
            },
            dataType: 'json',
            type: 'GET',
        });
        this.ShowInfoMsg(Attachments_FileWasDeleted);
    }

    // ---

    this.DeleteThisFile = function (TFileID) {
        if (confirm(Attachments_DoYouReallyWantToDeleteThisFile)) {
            this.FileList.RemoveFileByID(TFileID);
            this.DirectFileDeletingFromServer(TFileID);
            this.RefreshFilelist();
        }
    }

    // ---

    this.DeleteFileFromSidebarFileList = function (TFileID) {
        if (confirm(Attachments_DoYouReallyWantToDeleteThisFile)) {
            this.DirectFileDeletingFromServer(TFileID);
            this.RemoveFileFromSidebarUnattachedList(TFileID);
        }
    }

    // ---

    this.RemoveFileFromSidebarUnattachedList = function (TFileID) {
        $('#UnattachedFileID' + TFileID).parent() [0].removeChild($('#UnattachedFileID' + TFileID) [0]);
        this.CheckSidebarUnattachedFileList();
    }

    // ---

    this.AttachThisFileToNewTopicByID = function (TFileID, TFileNameNew) {
        if ($.trim(Attachments_NewFormID) == '') {
            this.ShowInfoMsg(Attachments_CantAttachToSavedTopic);
            return false;
        }
        $.ajax({
            url: Attachments_AttachmentsAction + 'link/' + TFileID + '/' + Attachments_NewFormID,
            data: {
                security_ls_key: LIVESTREET_SECURITY_KEY
            },
            dataType: 'json',
            type: 'GET',
        });
        this.RemoveFileFromSidebarUnattachedList(TFileID);
        this.ShowInfoMsg(Attachments_UploadDone);

        // add file into array, manually coz it sended by ajax, not by frame as uploading
        this.AddFileToList(TFileNameNew, TFileID);
        this.RefreshFilelist();
    }

    // ---

    this.CheckSidebarUnattachedFileList = function () {
        var SidebarUnattachedFileList = $('div.UnattachedFileList');
        if (SidebarUnattachedFileList.length == 0) {
            $('#Attachments_SidebarUnattachedFileListIDContainer').html(Attachments_ThereIsNoUnattachedFiles);
        }
    }

    // --- Misc ---

    this.ShowInfoMsg = function (MsgStr) {
        $('#AttFileOperationInfoBox').html((MsgStr != null) ? MsgStr : Attachments_DefaultInfoMsg);
    }

    // ---

    this.IsThisIE = function () {
        if ($.browser.ie) return true;
        //if ($.trim (navigator.appName) == 'Microsoft Internet Explorer') return true;
        return false;
    }

    // ---

    return this;

}).call(ls.attachments || {}, jQuery);

function GetRandomValue(LowValue, HighValue) {
    LowValue = parseInt(LowValue);
    HighValue = parseInt(HighValue);
    return Math.floor(Math.random() * (HighValue - LowValue + 1)) + LowValue;
}
