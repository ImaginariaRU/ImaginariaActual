<table class="table table-striped table-bordered table-condensed users-list">
    <thead>
    <tr>
        <th>
            <input type="checkbox" id="id_0" onclick="aceAdmin.selectAllUsers(this);"/>
        </th>
        <th>
            id
        </th>
        <th>
            Login
        </th>
        <th>
            {$oLang->adm_users_date_reg}
        </th>

        <th>
            E-Mail
        </th>

        {if $oConfig->GetValue('general.reg.activation')}
        <th>
            Активирован, спустя <br> минут
        </th>
        {/if}

        <th>
            {$oLang->adm_users_last_activity}
        </th>

        <th>
            {if $sMode != 'admins'}Бан?{else}&nbsp;{/if}
        </th>

        <th>
            Постов
        </th>
        <th>
            Комментов
        </th>
        <th>
            Голосов
        </th>
    </tr>
    </thead>

    {* {$oUser->_getDataOne('user_mail')}  -- это работает!!! *}

    <tbody>
    {foreach $aUserList as $oUser}
        {if $oConfig->GetValue('general.reg.activation') AND !$oUser->getDateActivate()}
            {assign var=classIcon value='icon-gray'}
        {elseif $oUser->IsBannedByLogin()}
            {assign var=classIcon value='icon-red'}
        {elseif $oUser->isAdministrator()}
            {assign var=classIcon value='icon-green'}
        {else}
            {assign var=classIcon value=''}
        {/if}

        <tr class="selectable">

            <td class="checkbox">
                {if $oUserCurrent->GetId()!=$oUser->getId()}
                    <input type="checkbox" id="login_{$oUser->GetLogin()}" onclick="aceAdmin.user.select()"/>
                {else}
                    &nbsp;
                {/if}
            </td>

            <td class="number"> {$oUser->getId()} &nbsp;</td>

            <td {if $oUserCurrent->GetId()==$oUser->getId()}style="font-weight:bold;"{/if}>
                <i class="icon-user {$classIcon}"></i>
                <a href="{router page='admin'}users/profile/{$oUser->getLogin()}/"
                   class="link">{$oUser->getLogin()}</a>
            </td>

            <td class="center">{$oUser->getDateRegister()}</td>

            <td>{$oUser->getUserMail()}</td>

            {if $oConfig->GetValue('general.reg.activation')}
                <td>&nbsp;
                    {if $oUser->getDateActivate()}
                        {*{$oUser->getDateActivate()}*}
                        {$oUser->_getDataOne('activation_diff')} <br/> минут
                    {else}<a
                        href="{router page='admin'}users/activate/{$oUser->getLogin()}/?security_ls_key={$LIVESTREET_SECURITY_KEY}">{$oLang->adm_users_activate}</a>{/if}
                </td>
            {/if}

            <td class="center">
                {assign var="oSession" value=$oUser->getSession()}
                {if $oSession}{$oSession->getDateLast()}{/if}
            </td>

            {if $sMode=='admins'}
                <td class="center">
                    {if $oUser->GetLogin()!='admin'}
                        <a href="{router page='admin'}users/admins/del/?user_login={$oUser->getLogin()}&security_ls_key={$LIVESTREET_SECURITY_KEY}"
                           class="link">{$oLang->adm_exclude}</a>
                        &nbsp;
                    {/if}
                </td>
            {else}
                <td class="center">{if $oUser->isBanned()}{if $oUser->getBanLine()}{$oUser->getBanLine()}{else}
                        unlim{/if}{/if}
                </td>
            {/if}

            <td>
                {$oUser->_getDataOne('count_topics')}
            </td>
            <td>
                {$oUser->_getDataOne('count_comments')}
            </td>
            <td>
                {$oUser->_getDataOne('count_votes')}
            </td>
        </tr>
    {/foreach}
    </tbody>
</table>
