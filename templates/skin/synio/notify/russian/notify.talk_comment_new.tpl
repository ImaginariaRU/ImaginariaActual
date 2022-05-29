<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getProfileName()}</a> оставил новый комментарий 
к письму <strong>«{$oTalk->getTitle()|escape:'html'}»</strong>, прочитать его можно перейдя по <a href="{router page='talk'}read/{$oTalk->getId()}/#comment{$oTalkComment->getId()}">этой ссылке</a><br>

{if $oConfig->GetValue('sys.mail.include_talk')}
	<p style="margin: 1em 40px;">
		<em>{$oTalkComment->getText()}</em>
	</p>
{/if}
Не забудьте авторизоваться на сайте!