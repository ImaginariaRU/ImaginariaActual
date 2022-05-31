{if ($oUserCurrent and $oUserCurrent->isAdministrator()) or ($oConfig->GetValue("plugin.delayedpost.UserAllowed"))}
    <!-- Delayed post plugin -->
    <li {if $sMenuItemSelectForDP=='delayed'}class="active"{/if}>
        <a href="{router page='delayedpost'}">{$aLang.plugin.delayedpost.new_topic_action_timeshifted}</a>
    </li>
    <!-- /Delayed post plugin -->
{/if}
