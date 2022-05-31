<!-- Delayed post plugin -->
<link rel="stylesheet" type="text/css"
      href="{$oConfig->GetValue("path.root.web")}/plugins/delayedpost/templates/skin/default/css/style.css"/>
{if ($oUserCurrent and $oUserCurrent->isAdministrator()) or ($oConfig->GetValue("plugin.delayedpost.UserAllowed"))}
    <script src="{$oConfig->GetValue("path.root.web")}/plugins/delayedpost/templates/skin/default/js/init.js"></script>
    <script>
        var DelayedPost_Msg_TimeNotSelected = "{$aLang.plugin.delayedpost.sidebar_title_not_active}";
        {literal}
        jQuery(document).ready(function ($) {
            ls.delayedpost.SetCurrentDateActivity();
            ls.delayedpost.InitChanges();
            $('#Delayedpost_ClearDateSelect').bind('click', function () {
                ls.delayedpost.ClearDateSelect(this);
            });
        });
        {/literal}
    </script>
{/if}
<!-- /Delayed post plugin -->
