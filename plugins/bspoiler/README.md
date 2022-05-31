# Spoiler plugin

http://livestreet.ru/blog/17258.html

# Автор

kks

# Возможность добавления в markitup

Меняем в /config/jevix.php:

```
// вызов метода с параметрами
			array(
				array('ls','cut','a', 'img', 'i', 'b', 'u', 's', 'video', 'em',  'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h4', 'h5', 'h6', 'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'blockquote', 'iframe','table','th','tr','td'),
			),	
```

на

```
// вызов метода с параметрами
			array(
				array('ls','cut','a', 'img', 'i', 'b', 'u', 's', 'video', 'em',  'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h4', 'h5', 'h6', 'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'blockquote', 'iframe','table','th','tr','td','spoiler'),
			),	
```

и после 
```
array(
				'table',
				array('border'=>'#int','cellpadding'=>'#int','cellspacing'=>'#int','align'=>array('right', 'left', 'center'),'height'=>'#int','width'=>'#int')
			),
```

добавляем
```
array(
				'spoiler',
				array('title'=>'#text')
			),
```

Заходим на /engine/assets/template/js/settings.js добавляем после

```
{name: ls.lang.get('panel_cut'), className:'editor-cut', replaceWith: function(markitup) { if (markitup.selection) return '<cut name="'+markitup.selection+'">'; else return '<cut>' }},
```

Этот код:
```
{separator:'---------------' },
				{name: ls.lang.get('panel_spoiler'), className:'editor-spoiler', replaceWith:'<spoiler title="[!['+ls.lang.get('panel_spoiler')+']!]"></spoiler>' }
```

Рисуем. Да. Просто рисуем, то, что увидят пользователи: markupit_buttons.png

Кидаем наш рисунок в /engine/assets/jquery/markitup/sets/default/images/

4. Добавляем в /engine/assets/jquery/markitup/sets/default/style.css

```
.markItUp .editor-spoiler a 		{ background-position: -592px -9px; }
```

