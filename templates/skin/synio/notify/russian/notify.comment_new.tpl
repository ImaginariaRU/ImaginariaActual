<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getProfileName()}</a> оставил комментарий 
к дискуссии <strong><a href="{$oTopic->getUrl()}">«{$oTopic->getTitle()|escape:'html'}»</a></strong>.

{if $oConfig->GetValue('sys.mail.include_comment')}
<p style="margin: 1em 40px;">
	<em>{$oComment->getText()}</em>
</p>
{/if}
<br>
<a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">Ответить на комментарий</a>.
<br>

{if $sSubscribeKey}
	<br><br>
	<a href="{router page='subscribe'}unsubscribe/{$sSubscribeKey}/">Отписаться от новых комментариев к этой дискуссии</a>.
{/if}



