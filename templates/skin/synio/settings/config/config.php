<?php

$config = array();

// Максимальная вложенность комментов при отображении
$config['module']['comment']['max_tree'] = 12;

// Ограничение на вывод числа друзей пользователя на странице его профиля
$config['module']['user']['friend_on_profile']    = 18;

$config['view']['theme'] = 'default';


/* Grid type:
 * 
 * fluid - резина
 * fixed - фиксированная ширина
 */
$config['view']['grid']['type'] = 'fluid';

/* Fluid settings */
$config['view']['grid']['fluid_min_width'] = 976; // Min - 976px
$config['view']['grid']['fluid_max_width'] = 1300;

/* Fixed settings */
$config['view']['grid']['fixed_width'] = 976;


$config['head']['default']['js'] = Config::Get('head.default.js');
$config['head']['default']['js'][] = '___path.static.skin___/js/template.js';
//$config['head']['default']['js'][] = '___path.static.skin___/js/reguser_script.js?v=0.12';


$config['head']['default']['css'] = array(
	"___path.static.skin___/css/reset.css",
	"___path.static.skin___/css/base.css",
	"___path.root.engine___/assets/jquery/markitup/skins/synio/style.css",
	"___path.root.engine___/assets/jquery/markitup/sets/synio/style.css",
	"___path.root.engine___/assets/jquery/jcrop/jquery.Jcrop.css",
	"___path.root.engine___/assets/prettify/prettify.css",
	"___path.static.skin___/css/grid.css",
	"___path.static.skin___/css/common.css",
	"___path.static.skin___/css/text.css",
	"___path.static.skin___/css/forms.css",
	"___path.static.skin___/css/buttons.css",
	"___path.static.skin___/css/navs.css",
	"___path.static.skin___/css/icons.css",
	"___path.static.skin___/css/tables.css",
	"___path.static.skin___/css/topic.css",
	"___path.static.skin___/css/comments.css",
	"___path.static.skin___/css/blocks.css",
	"___path.static.skin___/css/modals.css",
	"___path.static.skin___/css/blog.css",
	"___path.static.skin___/css/profile.css",
	"___path.static.skin___/css/wall.css",
	"___path.static.skin___/css/infobox.css",
	"___path.static.skin___/css/jquery.notifier.css",
	"___path.static.skin___/css/smoothness/jquery-ui.css",
	"___path.static.skin___/themes/___view.theme___/style.css",
	"___path.static.skin___/css/print.css",
    "___path.root.server___/config/skins/synio.custom.css"
);


return $config;
