/*
 Configengine plugin
 (P) PSNet, 2008 - 2013
 http://psnet.lookformp3.net/
 http://livestreet.ru/profile/PSNet/
 https://catalog.livestreetcms.com/profile/PSNet/
 http://livestreetguide.com/developer/PSNet/
 */

jQuery(document).ready(function ($) {

    $('a.ce_delete_plugin_config').bind('click.ce', function () {
        if (!confirm(ls.lang.get('plugin.configengine.Plugins_List_Plugin_Delete_Question'))) return false;
        PluginTableLine = $(this).closest('tr');
        $.ajax({
            url: aRouter ['configengine'] + 'deleteplugin',
            data: {
                'security_ls_key': LIVESTREET_SECURITY_KEY,
                'pluginname': $(this).attr('data-pluginname')
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                PluginTableLine.hide(200, function () {
                    $(this).remove();
                });
                ls.msg.notice('Ok', '');
            }
        });
        return false;
    });

});
