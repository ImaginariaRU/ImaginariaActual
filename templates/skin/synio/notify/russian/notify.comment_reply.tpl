<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getProfileName()}</a> ответил на ваш комментарий
к дискуссии <strong><a href="{$oTopic->getUrl()}">«{$oTopic->getTitle()|escape:'html'}»</a></strong>.
<br/>

{if $oConfig->GetValue('sys.mail.include_comment')}
	<p style="margin: 1em 40px;">
		<em>{$oComment->getText()}</em>
	</p>
{/if}

<a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">ответить на комментарий</a>.