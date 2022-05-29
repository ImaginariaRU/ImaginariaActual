<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUser->getUserWebPath()}">{$oUser->getProfileName()}</a> ответил на ваше сообщение на <a href="{$oUserWall->getUserWebPath()}wall/">стене</a> так: 
<br>

<p style="margin: 1em 40px;">
    <em>{$oWall->getText()}</em>
</p>

<hr>
Ваше сообщение: <br>

<p style="margin: 1em 40px;">
    <em>{$oWallParent->getText()}</em>
</p>