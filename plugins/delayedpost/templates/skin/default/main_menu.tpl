<li {if $sAction==$oConfig->GetValue("plugin.delayedpost.url")}class="active"{/if}><a
            href="{router page='delayedpost'}">{$aLang.plugin.delayedpost.new_topic_action_timeshifted} ({$iTopicsCount}
        )</a><i></i></li>