<div id="ignore_target_form" class="modal">
	<header class="modal-header">
		<h3>{$aLang.plugin.ignore.target_form_title}</h3>
		<a href="#" class="close jqmClose"></a>
	</header>

	<form onsubmit="return ls.ignore.updateIgnoreTarget(this,{$iTargetId},'{$sTargetType}', {$iUserId});" class="modal-content">
        {if !$iUserId}
            <p><label>{$aLang.plugin.ignore.target_form_login}<br /><input type="text" name="login" id="ignore_target_form_login" class="input-text input-width-full" maxlength="240" value="" /></label></p>
        {/if}
        {if $oConfig->GetValue('plugin.ignore.ignore_target_reply_my_comment')}
            <p><label><input type="checkbox" name="ignore7" value="7" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnoreTargetRyplyMyComment())} checked="checked"{/if} /> {$aLang.plugin.ignore.target_form_reply_my_comment}</label></p>
        {/if}
        {if $oConfig->GetValue('plugin.ignore.ignore_target_post_comment_my_topic') and $sTargetAdmin}
            <p><label><input type="checkbox" name="ignore8" value="8" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnoreTargetPostMyTopic())} checked="checked"{/if} /> {$aLang.plugin.ignore.target_form_post_my_topic}</label></p>
        {/if}
        <p><label>{$aLang.plugin.ignore.reason_null}<br /><input type="text" name="reason" id="ignore_target_form_reason" class="input-text input-width-full" maxlength="240" value="{if $oIgnore}{$oIgnore->getReason()}{/if}" /></label></p>
		<button type="submit" class="button button-primary">{$aLang.plugin.ignore.target_form_submit}</button>
	</form>
</div>