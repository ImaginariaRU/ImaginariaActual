<!-- Configengine plugin -->
<div class="CE_ImportSettings">
    <h2 class="page-header">{$aLang.plugin.configengine.Import_Settings}</h2>

    <form action="{router page='configengine'}import/" method="post" enctype="multipart/form-data"
          onsubmit="return confirm('Ok?');">
        {if $bReturnToReferer}
            <input type="hidden" name="bReturnToReferer" value="{$bReturnToReferer}"/>
        {/if}
        {if $sThisPluginNameOnly}
            <input type="hidden" name="sThisPluginNameOnly" value="{$sThisPluginNameOnly}"/>
        {/if}
        <input type="hidden" name="security_ls_key" value="{$LIVESTREET_SECURITY_KEY}"/>
        <div class="FileField">
            <div class="Cover">{$aLang.plugin.configengine.Import_Field_Tip}</div>
            <div class="FWrapper">
                <input type="file" size="50" name="settings"
                       onchange="$ (this).parent ().prev ().html ($ (this).val ());"/>
            </div>
        </div>
        <input type="submit" value="{$aLang.plugin.configengine.Import_Submit}" class="button button-primary"/>
    </form>
</div>
<!-- /Configengine plugin -->
