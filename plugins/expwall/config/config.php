<?php

/* -------------------------------------------------------
 *
 *   LiveStreet (v1.x)
 *   Plugin Expanded wall (v.0.3)
 *   Copyright © 2011 Bishovec Nikolay
 *
 * --------------------------------------------------------
 *
 *   Plugin Page: http://netlanc.net
 *   Contact e-mail: netlanc@yandex.ru
 *
  ---------------------------------------------------------
 */

$config = array();

Config::Set('router.page.wall', 'PluginExpwall_ActionWall');

Config::Set('block.rule_wall', array(
    'action' => 'index',
    'blocks' => array(
        'right' => array(
            'wall' => array(
                'params' => array(
                    'plugin' => 'expwall',
                ),
                'priority' => 100,
            ),
        )
    )
));

return $config;
