<h2>{cfg name='view.name'}</h2>
<h4>{cfg name='view.description'}</h4>
<hr>
<br>
Пользователь <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getProfileName()}</a> пригласил вас зарегистрироваться на сайте 
<a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>
<br>
Код приглашения:  <strong>{$oInvite->getCode()}</strong>
<br>
<br>
Для регистрации вам будет необходимо ввести код приглашения на <a href="{router page='login'}">странице входа</a>													
<br>
<br>

							