# WTF

Livestreet 1.0+
Plugin Expanded Wall v. 0.4

# Issue

По неизвестной причине плагин не подключает свои шаблоны куда полагается.
В итоге сколько угодно можно править настройки в

`plugins/expwall/templates/skin/default/wall_items_reply.tpl`

но править надо файл

`templates/skin/synio/actions/ActionProfile/wall_items_reply.tpl`

Нас интересует блок

```
<a href="{$oReplyUser->getUserWebPath()}" class="author">{$oReplyUser->getLogin()}</a>
```

Его нужно заменить на блок
```
<a href="{$oReplyUser->getUserWebPath()}" class="author">{if $oReplyUser->getProfileName()}{$oReplyUser->getProfileName()}{else}{$oReplyUser->getLogin()}{/if}</a>
```

# Оригинальный ридми:


УСТАНОВКА:
1. Скопировать плагин в каталог /plugins/
2. Через панель управления плагинами (/admin/plugins/) запустить его активацию.
3. Выполнить настройки плагина в файле /plugins/expwall/config/config.php
4. Для работы стены в проффиле пользователя, в файл шаблона \templates\skin\_Ваш_шаблон_\actions\ActionProfile\whois.tpl
после кода

		{hook run='profile_whois_item' oUserProfile=$oUserProfile}
	</div>
</div>

добавить хук

{hook run='profile_whois_end' oUserProfile=$oUserProfile}

ОБНОВЛЕНИЯ
v0.3
- добавлен редактор в форму сообщения стенок.
v0.2.1
- адаптированы шаблоны developer и social.
v0.2
- поправлено имя хука для главного меню;
- добавлена стена в профиль пользователя.

