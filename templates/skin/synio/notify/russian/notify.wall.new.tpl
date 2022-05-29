<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getProfileName()}</a> оставил сообщение на <a href="{$oUserWall->getUserWebPath()}wall/">вашей стене</a>.
<br/>

<p style="margin: 1em 40px;">
    <em>{$oWall->getText()}</em>
</p>

