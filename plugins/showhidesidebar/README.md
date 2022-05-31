Show hide sidebar
=================

Плагин дает возможность скрывать, и показать ранее скрытый сайдбар. Для этих целей добавлена дополнительная кнопка в тулбар.



Автор
=====

Copyright В© 2013 Bishovec Nikolay
Plugin Page: http://netlanc.net
CMS Page http://livestreetcms.com
Contact e-mail: netlanc@yandex.ru

ЛИЦЕНЗИЯ
========

Плагин распространяется  по лицензии Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0) (http://creativecommons.org/licenses/by-sa/3.0/deed.ru)

УСТАНОВКА
=========

1. Скопировать плагин в каталог /plugins/
2. Через панель управления плагинами (/admin/plugins/) запустить его активацию.
3. Выполнить настройки плагина в файле /plugins/showhidesidebar/config/config.php
4. Радоваться ;)

ОБНОВЛЕНИЯ
==========

v.0.3 
- Refactoring for PHP7

v.0.2
- добавлена возможность установки скорости и направления сворачивания сайдбара

# Issues

1. jQuery UI 

Плагин использует хардкодное добавление тяжелого jQueryUI через шаблон
`templates/skin/default/toolbar_showhidesidebar.tpl`
как
```
<script type='text/javascript'
        src='{$oConfig->get('path.root.web')}/plugins/showhidesidebar/js/jquery-ui.min.v1.8.24.js'></script>
<script>
```

Непонятно, почему этот файл не подключается через метод `Init()` и непонятно, зачем 
вообще использовать тяжелый jQuery UI

Кажется, только ради https://jqueryui.com/show/
А значит имеет смысл использовать кастомную сборку

https://jqueryui.com/download/#!version=1.12.1&components=100000110000000000000000010000001000000000000010



2. base64 

Кроме того, `templates/skin/default/css/style.css` будет оптимально использовать
base64-изображение - на 1 запрос к серверу меньше.

