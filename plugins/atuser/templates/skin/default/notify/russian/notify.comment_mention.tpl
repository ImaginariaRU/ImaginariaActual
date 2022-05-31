<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>

Пользователь <a href="{$oSender->getUserWebPath()}">{$oSender->getLogin()}</a> упомянул вас в
комментарии к дискуссии: «<a href="{$oTopic->getUrl()}"><strong>{$oTopic->getTitle()}</strong></a>».

