Для пользователя <a
        href="{$oUser->getUserWebPath()}">{$oUser->getLogin()}</a></b> было запрошено изменение почтового адреса с
<i>{$oUser->getMail()}</i> на <i>{$oChangeMail->getChangeMailTo()}</i>
<br/><br/>
<i><b>ВНИМАНИЕ!<b> НЕ переходите по ссылке ниже, если вы не отправляли эту заявку. </i>
<br/><br/>
<a href='{router page='login'}changemail/{$oChangeMail->getCode()}/'>Подтвердить смену e-mail</a>
<br/><br/>
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>