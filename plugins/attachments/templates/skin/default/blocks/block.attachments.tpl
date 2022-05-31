<!-- Attachments plugin -->
<div class="block white" id="Attachments_Sidebar_Place_Parent_ID">
    <div class="Attachments_Sidebar_Place"></div><!-- dont delete this line -->
</div>

<script>
    {literal}
    function Attachments_CheckUpFileUploadFormPlace() {
        if ((Attachments_FileFormPlace == 'sidebar') || (ls.attachments.IsThisIE())) {
            $('#Attachments_Sidebar_Place_Parent_ID').css('display', 'block');
        }
    }

    Attachments_CheckUpFileUploadFormPlace();
    {/literal}
</script>
<!-- /Attachments plugin -->
