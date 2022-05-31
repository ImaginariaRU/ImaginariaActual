	{if $oUserCurrent && $oUserCurrent->getId()!=$oUserProfile->getId()}
        {literal}
        <script type="text/javascript">
        jQuery(document).ready(function($){
            $('h2.user-login').append(' <span class="ignore-profile-whois">[<a href="#" id="ignore_setting_show">игнор</a>]</span>');
        	$('#ignore_setting_form').jqm({trigger: '#ignore_setting_show'});
        });
        </script>
        {/literal}
    	<div id="ignore_setting_form" class="modal">
    		<header class="modal-header">
    			<h3>{$aLang.plugin.ignore.settings}</h3>
    			<a href="#" class="close jqmClose"></a>
    		</header>
    
    		<form onsubmit="return ls.ignore.updateIgnore(this,{$oUserProfile->getId()});" class="modal-content">
                {if $oConfig->GetValue('plugin.ignore.ignore_post_me_pm')}
                    <p><label><input type="checkbox" name="ignore1" value="1" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMePM())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_me_pm}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_hide_me_comments')}
                    <p><label><input type="checkbox" name="ignore2" value="2" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsHideMeComments())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_hide_me_comments}</label></p>
                    <p style="padding-left: 30px;"><label>{$aLang.plugin.ignore.ignore_comment_rating} <input type="text" name="ignore_comment_rating" id="ignore_comment_rating" value="{if $oIgnore}{$oIgnore->getCommentRating()}{else}{$oConfig->GetValue('plugin.ignore.allow_ignore_comment')}{/if}" class="input-wide" style="width: 50px;text-align: center;" maxlength="2" /></label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_reply_my_comment')}
                    <p><label><input type="checkbox" name="ignore3" value="3" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnoreRyplyMyComment())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_reply_my_comment}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_post_comment_my_topic')}
                    <p><label><input type="checkbox" name="ignore4" value="4" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMyTopic())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_comment_my_topic}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_post_my_wall')}
                    <p><label><input type="checkbox" name="ignore5" value="5" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsIgnorePostMyWall())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_post_my_wall}</label></p>
                {/if}
                {if $oConfig->GetValue('plugin.ignore.ignore_hide_me_topics')}
                    <p><label><input type="checkbox" name="ignore6" value="6" class="input-checkbox"{if $bIgnore or ($oIgnore and $oIgnore->getIsHideMeTopics())} checked="checked"{/if} /> {$aLang.plugin.ignore.ignore_hide_me_topics}</label></p>
                {/if}
                <p><label>{$aLang.plugin.ignore.reason_null}<br /><input type="text" name="reason" id="ignore_setting_reason" class="input-text input-width-full" maxlength="240" value="{if $oIgnore}{$oIgnore->getReason()}{/if}" /></label></p>
                <p><label>{$aLang.plugin.ignore.ignore_profile_show} <input type="radio" name="ignore_profile_show" value="1" {if $oIgnore and $oIgnore->getProfileShow()}checked{/if} class="checkbox" /> {$aLang.plugin.ignore.yes} <input type="radio" name="ignore_profile_show" value="0" {if !$oIgnore or !$oIgnore->getProfileShow()}checked{/if} class="checkbox" /> {$aLang.plugin.ignore.no}</label></p>
    			<button type="submit" class="button button-primary">{$aLang.plugin.ignore.submit_apply}</button>
    		</form>
    	</div>
	{/if}
