<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>

Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> упомянул вас в 
<a href="{$oParentTarget->getUrl()}#comment{$oTarget->getId()}">комментарии</a> к дискуссии
<strong><a href="{$oParentTarget->getUrl()}">{$oParentTarget->getTitle()}</a></strong>

