{if ($oUserCurrent and $oUserCurrent->isAdministrator()) or ($oConfig->GetValue("plugin.delayedpost.UserAllowed"))}
    <!-- Delayed post plugin -->
    <div class="block white">
        <div id="SidebarDateSelect">

            <h1>{$aLang.plugin.delayedpost.sidebar_title}<span id="DP_Second_Title"></span></h1>

            <form action="" method="post" id="DP_SidebarForm">
                <input type="hidden" value="{$LIVESTREET_SECURITY_KEY}" name="security_ls_key"/>

                <div class="SelectFields">

                    <select class="DelayedPostSelectItem DayDateSelect" name="DayDateSelect" id="DP_DayDateSelect">
                        {if $aDays}
                            {foreach from=$aDays item=oDay key=ODKey}
                                <option value="{$oDay}"
                                        {if $aTopicDay == $oDay}selected="selected"{/if}>{$ODKey}</option>
                            {/foreach}
                        {/if}
                    </select>

                    <select class="DelayedPostSelectItem MonthDateSelect" name="MonthDateSelect"
                            id="DP_MonthDateSelect">
                        {if $aMonths}
                            {foreach from=$aMonths item=oMonth key=OMKey}
                                <option value="{$oMonth}"
                                        {if $aTopicMonth == $oMonth}selected="selected"{/if}>{$OMKey}</option>
                            {/foreach}
                        {/if}
                    </select>

                    <select class="DelayedPostSelectItem YearDateSelect" name="YearDateSelect" id="DP_YearDateSelect">
                        {if $aYears}
                            {foreach from=$aYears item=oYear key=OYKey}
                                <option value="{$oYear}"
                                        {if $aTopicYear == $oYear}selected="selected"{/if}>{$OYKey}</option>
                            {/foreach}
                        {/if}
                    </select>

                    <br/>
                    @

                    <select class="DelayedPostSelectItem TimeHourSelect" name="TimeHourSelect" id="DP_TimeHourSelect">
                        {if $aHours}
                            {foreach from=$aHours item=oHour}
                                <option value="{$oHour}"
                                        {if $aTopicHour == $oHour}selected="selected"{/if}>{$oHour}</option>
                            {/foreach}
                        {/if}
                    </select>

                    <select class="DelayedPostSelectItem TimeMinuteSelect" name="TimeMinuteSelect"
                            id="DP_TimeMinuteSelect">
                        {if $aMinutes}
                            {foreach from=$aMinutes item=oMinute}
                                <option value="{$oMinute}"
                                        {if $aTopicMinute == $oMinute}selected="selected"{/if}>{$oMinute}</option>
                            {/foreach}
                        {/if}
                    </select>

                </div>

            </form>

            <div id="Delayedpost_ClearDateSelect">{$aLang.plugin.delayedpost.sidebar_clear_date_select_title}</div>

            <div class="ServerDateNow">
                {$aLang.plugin.delayedpost.sidebar_time_on_server}
                <span>{$smarty.now|date_format:"%H:%M:%S %a, %b %e %Y"}</span>
                {$aLang.plugin.delayedpost.sidebar_time_on_server_not_auto}
            </div>

        </div>
    </div>
    <!-- /Delayed post plugin -->
{/if}
