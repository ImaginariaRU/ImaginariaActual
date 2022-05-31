<section class="toolbar-showhide-sidebar" id="" style="">
    <a href="#" id="show-sidebar"
       title="{$aLang.plugin.showhidesidebar.show}"{if $smarty.cookies.shs} style="display: block"{/if}></a>
    <a href="#" id="hide-sidebar"
       title="{$aLang.plugin.showhidesidebar.hide}"{if $smarty.cookies.shs} style="display: none"{/if}></a>
</section>
<script>
    jQuery(document).ready(function ($) {

        $("#show-sidebar").click(function () {
            $('#wrapper').removeClass('no-sidebar');
            $("#sidebar").show("slide", {
                direction: '{$oConfig->get('plugin.showhidesidebar.direction')}'
            }, {$oConfig->get('plugin.showhidesidebar.speed')}, function () {
            });
            $('#show-sidebar').css('display', 'none');
            $('#hide-sidebar').css('display', 'block');
            document.cookie = "shs=0; path=/";
            return false;
        });
        $("#hide-sidebar").click(function () {
            $("#sidebar").hide("slide", {
                direction: '{$oConfig->get('plugin.showhidesidebar.direction')}'
            }, {$oConfig->get('plugin.showhidesidebar.speed')}, function () {
                $('#wrapper').addClass('no-sidebar');
            });
            $('#show-sidebar').css('display', 'block');
            $('#hide-sidebar').css('display', 'none');
            document.cookie = "shs=1; path=/";
            return false;
        });
    });
</script>
{if $smarty.cookies.shs==1}
    <style>
        #sidebar {
            display: none;
        }
    </style>
{/if}