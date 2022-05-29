<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Вы отправили запрос на смену E-Mail пользователя <a href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a> на сайте <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>.<br/>
Старый E-Mail: <strong>{$oChangemail->getMailFrom()}</strong><br/>
Новый E-Mail: <strong>{$oChangemail->getMailTo()}</strong><br/>


<br/>
Для подтверждения смены емайла пройдите по ссылке:
<a href="{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/">{router page='profile'}changemail/confirm-from/{$oChangemail->getCodeFrom()}/</a>
