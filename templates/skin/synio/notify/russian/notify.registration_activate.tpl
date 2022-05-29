<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Вы зарегистрировались на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a><br>

Ваш логин: <strong>{$oUser->getLogin()}</strong><br>

<br>
Для завершения регистрации вам необходимо активировать аккаунт пройдя по ссылке: 
<a href="{router page='registration'}activate/{$oUser->getActivateKey()}/">{router page='registration'}activate/{$oUser->getActivateKey()}/</a>
