{assign var="sTemplatePathPlugin" value=$aTemplatePathPlugin.urlredirect}
{include file="$sTemplatePathPlugin/header.tpl"}

<div class="NormalRedirect">

    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" valign="middle">
                <div class="LeftName">
                    <h1>LiveStreet</h1>
                </div>
            </td>
            <td align="left" valign="middle">
                <div class="RightMessage">

                    <h1>{$aLang.plugin.urlredirect.you_wanna_go}</h1>
                    {$aLang.plugin.urlredirect.and_leave_this_site}

                    <div class="GoLink">
                        <a id="UR_URL" href="{$URL}">{$URL}</a>
                    </div>

                    {if $oConfig->GetValue("plugin.urlredirect.Time_For_Auto_Going")>=0}
                        <div id="UR_TimeMsg">
                            {$aLang.plugin.urlredirect.you_will_be_forwarded_in_N_sec|ls_lang:"seconds%%`$oConfig->GetValue("plugin.urlredirect.Time_For_Auto_Going")`"}
                        </div>
                    {/if}

                    <small>
                        <a href="{$oConfig->GetValue("path.root.web")}">{$aLang.plugin.urlredirect.maybe_stay_here}</a>
                    </small>

                    {if $bNotCorrectReferer}
                        <div class="RefererError">
                            {$aLang.plugin.urlredirect.referer_protection_caution}
                        </div>
                    {/if}

                </div>
            </td>
        </tr>
    </table>

    <script>
        jQuery(document).ready(function ($) {
            $('#UR_URL').attr('href', $('#UR_URL').attr('href') + window.location.hash);
            $('#UR_URL').html($('#UR_URL').html() + window.location.hash);

            // live counter
            if ((UR_oCounter = $('#UR_TimeMsg b')).length == 1) {
                var UR_iTimeProc = setInterval(function () {
                    ((iVal = parseInt(UR_oCounter.html())) > 0 ? UR_oCounter.html(--iVal) : clearInterval(UR_iTimeProc));
                }, 1000);
            }

            // --- if you dont like jQuery - use native JS
            //document.getElementById ('UR_URL').href = document.getElementById ('UR_URL').href + window.location.hash;
            //document.getElementById ('UR_URL').innerHTML = document.getElementById ('UR_URL').innerHTML + window.location.hash;
        });
    </script>

</div>

{include file="$sTemplatePathPlugin/footer.tpl"}