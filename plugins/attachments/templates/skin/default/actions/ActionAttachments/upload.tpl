{literal}
<html>
<body>
<script>
    function FileUploadDone() {
        if (top.ls.attachments.FileUploadDone) top.ls.attachments.FileUploadDone("{/literal}{$Attachments_Upload_Result}", "{$Attachments_Last_Uploaded_File_ID}{literal}");
    }

    window.onload = FileUploadDone;
</script>
</body>
</html>
{/literal}
