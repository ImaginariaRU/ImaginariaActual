<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUserTopic->getUserWebPath()}">{$oUserTopic->getProfileName()}</a> опубликовал в блоге 
<strong>«{$oBlog->getTitle()|escape:'html'}»</strong> новую дискуссию:<br>

<a href="{$oTopic->getUrl()}">{$oTopic->getTitle()|escape:'html'}</a><br>
														