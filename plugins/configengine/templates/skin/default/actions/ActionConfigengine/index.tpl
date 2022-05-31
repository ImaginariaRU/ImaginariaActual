{assign var="bNoSidebar" value=true}
{assign var="noSidebar" value=true}
{include file='header.tpl'}

<div class="ConfigEngine PluginsConfigList">
    <h2 class="page-header">{$aLang.plugin.configengine.Plugins_List} (<span>{count($aCEConfigs)}</span>)</h2>
    {if count($aCEConfigs)>0}
        <table cellpadding="0" cellspacing="0" class="PluginList" width="100%" border="1">
            <thead>
            <tr>
                <th>{$aLang.plugin.configengine.Plugins_List_Plugin_Name}</th>
                <th>{$aLang.plugin.configengine.Plugins_List_Plugin_Autoload}</th>
                <th>{$aLang.plugin.configengine.Plugins_List_Plugin_Controls}</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$aCEConfigs item=aCEConfig name=aCEConfigName}
                <tr {if $smarty.foreach.aCEConfigName.iteration % 2 == 0}class="second"{/if}>
                    <td class="nameleft">
                        {if stripos($aCEConfig.pluginname,$oConfig->GetValue('plugin.configengine.plugins_data_signature_prefix'))!==false}
                            <small title="{$aLang.plugin.configengine.Plugins_List_Plugin_Name_Storage}" class="Tip">?
                            </small>
                        {/if}
                        <b>{$aCEConfig.pluginname}</b>
                    </td>
                    <td>
                        {if $aCEConfig.autoload}
                            {$aLang.plugin.configengine.Plugins_List_Plugin_Autoload_Yes}
                        {else}
                            {$aLang.plugin.configengine.Plugins_List_Plugin_Autoload_No}
                        {/if}
                    </td>
                    <td>
                        <a href="#" class="ce_delete_plugin_config Tip Control"
                           data-pluginname="{$aCEConfig.pluginname}"
                           title="{$aLang.plugin.configengine.Plugins_List_Plugin_Delete_Config}">X</a>
                        <a href="{router page='configengine'}export/{$aCEConfig.pluginname|escape:'url'}?security_ls_key={$LIVESTREET_SECURITY_KEY}"
                           class="Tip Control" title="{$aLang.plugin.configengine.Plugins_List_Plugin_Export_Config}">&darr;</a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
        {include file='paging.tpl' aPaging="$aPaging"}

    {else}
        {$aLang.plugin.configengine.Plugins_List_Empty}
        <br/>
    {/if}

    <br/>

    {include file="{$aTemplatePathPlugin.configengine}import_settings.tpl"}

    <script>
        jQuery(document).ready(function ($) {
            ls.lang.load({lang_load name="plugin.configengine.Plugins_List_Plugin_Delete_Question"});
        });
    </script>

</div>

{include file='footer.tpl'}