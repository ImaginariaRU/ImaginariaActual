{assign var="sidebarPosition" value='left'}
{include file='header.tpl'}


{include file='menu.settings.tpl'}

{hook run='settings_account_begin'}

<div class="wrapper-content">
    {hook run='form_settings_account_begin'}

    <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}">


    <h3>{$aLang.plugin.shistory.title}</h3>

    {$gTest}

    <table class="table" style="font-size: 11px">
        <thead>
        <tr>
            <th>{$aLang.plugin.shistory.date}</th>
            <th>{$aLang.plugin.shistory.ip}</th>
            <th>{$aLang.plugin.shistory.os}</th>
            <th>{$aLang.plugin.shistory.agent}</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$aHistoryRows item=aRow}
            <tr>
                <td>{$aRow['enter_date']} {$aRow['enter_time']} </td>
                <td>{$aRow['user_ip']}</td>
                <td>{$aRow['user_os']}</td>
                <td>{$aRow['user_agent']}</td>
            </tr>
        {/foreach}
        </tbody>
        <!-- body -->
    </table>

</div>

{hook run='settings_account_end'}

{include file='footer.tpl'}