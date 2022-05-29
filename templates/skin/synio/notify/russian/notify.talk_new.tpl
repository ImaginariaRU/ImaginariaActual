<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Вам пришло новое письмо от пользователя <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getProfileName()}</a>, прочитать 
и ответить на него можно, перейдя по <a href="{router page='talk'}read/{$oTalk->getId()}/">этой ссылке</a><br>

Тема письма: <strong>{$oTalk->getTitle()|escape:'html'}</strong>
<br>
{if $oConfig->GetValue('sys.mail.include_talk')}
	<p style="margin: 1em 40px;">
		<em>{$oTalk->getText()}</em>
	</p>
{/if}
Не забудьте предварительно авторизоваться!