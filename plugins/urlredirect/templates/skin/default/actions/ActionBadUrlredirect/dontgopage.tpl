{assign var="sTemplatePathPlugin" value=$aTemplatePathPlugin.urlredirect}
{include file="$sTemplatePathPlugin/header.tpl"}

<div class="AccessDenied">

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
                    {$aLang.plugin.urlredirect.this_can_be_dangerous}

                    <div class="GoLink">
                        <s id="UR_URL">{$BadURL}</s>
                    </div>

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
            $('#UR_URL').html($('#UR_URL').html() + window.location.hash);

            // --- if you dont like jQuery - use native JS
            //document.getElementById ('UR_URL').innerHTML = document.getElementById ('UR_URL').innerHTML + window.location.hash;
        });
    </script>

</div>

{include file="$sTemplatePathPlugin/footer.tpl"}