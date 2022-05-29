<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Вы запросили повторную активацию на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a><br>

Ссылка на активацию аккаунта:
<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>

<br><br>
