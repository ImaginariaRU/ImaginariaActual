<?php

$allowed_video_hostings = [
    'youtube.com',
    'youtu.be',
    'rutube.ru',
    'vimeo.com',
    'kickstarter.com',
    'vk.com',
    'video.yandex.ru',
    'player.vimeo.com',
    'dailymotion.com',
    'coub.com',
    'w.soundcloud.com',
    'vk.com',
    'gfycat.com',
];

return [
    'default' => [
        // Разрешённые теги
        'cfgAllowTags' => [
            // вызов метода с параметрами
            [
                [
                    'ls', 'cut', 'nobr', 'br',
                    'a', 'img',
                    'i', 'b', 'u', 's', 'em', 'strong',
                    'small', 'big',
                    'li', 'ol', 'ul',
                    'sup', 'sub', 'abbr', 'acronym',
                    'h4', 'h5', 'h6',
                    'hr', 'pre', 'code',
                    'object', 'param', 'embed', 'iframe', 'video',
                    'blockquote', 'div',
                    'table', 'tbody', 'th', 'tr', 'td',
                ],
            ],
        ],
        // Коротие теги типа
        'cfgSetTagShort' => [
            [
                ['br', 'img', 'hr', 'cut', 'ls']
            ],
        ],
        // Преформатированные теги
        'cfgSetTagPreformatted' => [
            [
                ['pre', 'code', 'video']
            ],
        ],
        // Разрешённые параметры тегов
        'cfgAllowTagParams' => [
            [
                'img',
                [
                    'src',
                    'alt' => '#text',
                    'title',
                    'align' => ['right', 'left', 'center', 'middle'],
                    'width' => '#int',
                    'height' => '#int',
                    'hspace' => '#int',
                    'vspace' => '#int',
                    'class' => ['image-center']
                ]
            ],
            [
                'a',
                [
                    'title',
                    'href',
                    'rel' => '#text',
                    'name' => '#text',
                    'target' => [
                        '_blank'
                    ]
                ]
            ],
            [
                'cut',
                ['name']
            ],
            [
                'object',
                [
                    'width' => '#int',
                    'height' => '#int',
                    'data' => [
                        '#domain' => $allowed_video_hostings
                    ],
                    'type' => '#text'
                ]
            ],
            [
                'param',
                [
                    'name' => '#text',
                    'value' => '#text'
                ]
            ],
            [
                'embed',
                [
                    'src' => [
                        '#domain' => $allowed_video_hostings
                    ],
                    'type' => '#text',
                    'allowscriptaccess' => '#text',
                    'allowfullscreen' => '#text',
                    'width' => '#int',
                    'height' => '#int',
                    'flashvars' => '#text',
                    'wmode' => '#text'
                ]
            ],
            [
                'video',
                []
            ],
            [
                'acronym',
                ['title']
            ],
            [
                'abbr',
                ['title']
            ],
            [
                'iframe',
                [
                    'width' => '#int',
                    'height' => '#int',
                    'src' => [
                        '#domain' => $allowed_video_hostings
                    ]
                ]
            ],
            [
                'ls',
                [
                    'user' => '#text'
                ]
            ],
            [
                'td',
                [
                    'colspan' => '#int',
                    'rowspan' => '#int',
                    'align' => [ 'right', 'left', 'center', 'justify'],
                    'height' => '#int',
                    'width' => '#int'
                ]
            ],
            [
                'tr',
                [
                    'height' => '#int',
                    'width' => '#int'
                ]
            ],
            [
                'table',
                [
                    'border' => '#int',
                    'cellpadding' => '#int',
                    'cellspacing' => '#int',
                    'align' => ['right', 'left', 'center'],
                    'height' => '#int',
                    'width' => '#int'
                ]
            ],
            [
                'div',
                [
                    'align' => ['left','center','right']
                ]
            ],
        ],
        // Параметры тегов являющиеся обязательными
        'cfgSetTagParamsRequired' => [
            [
                'img',
                'src'
            ],
        ],
        // Теги которые необходимо вырезать из текста вместе с контентом
        'cfgSetTagCutWithContent' => [
            [
                ['script', 'style']
            ],
        ],
        // Вложенные теги
        'cfgSetTagChilds' => [
            [
                'ul',
                ['li'],
                false,
                true
            ],
            [
                'ol',
                ['li'],
                false,
                true
            ],
            [
                'object',
                'param',
                false,
                true
            ],
            [
                'object',
                'embed',
                false,
                false
            ],
            [
                'table',
                ['tr', 'tbody'],
                false,
                true
            ],
            [
                'tbody',
                ['tr'],
                false,
                true
            ],
            [
                'tr',
                ['td', 'th'],
                false,
                true
            ],
        ],
        // Если нужно оставлять пустые не короткие теги
        'cfgSetTagIsEmpty' => [
            [
                ['param', 'embed', 'a', 'iframe']
            ],
        ],
        // Не нужна авто-расстановка <br>
        'cfgSetTagNoAutoBr' => [
            [
                ['ul', 'ol', 'object', 'table', 'tbody', 'tr']
            ]
        ],
        // Теги с обязательными параметрами
        'cfgSetTagParamDefault' => [
            [
                'embed',
                'wmode',
                'opaque',
                true
            ],
        ],
        // Отключение авто-добавления <br>
        'cfgSetAutoBrMode' => [
            [
                true
            ]
        ],
        // Автозамена
        'cfgSetAutoReplace' => [
            [
                ['+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)', '(tm)', '(TM)'],
                ['±', '©', '©', '®', '©', '©', '®', '™', '™']
            ]
        ],
        'cfgSetTagNoTypography' => [
            [
                ['code', 'video', 'object']
            ],
        ],
        // Теги, после которых необходимо пропускать одну пробельную строку
        'cfgSetTagBlockType' => [
            [
                ['h4', 'h5', 'h6', 'ol', 'ul', 'blockquote', 'pre']
            ]
        ],
        'cfgSetTagCallbackFull' => [
            [
                'ls',
                ['_this_', 'CallbackTagLs'],
            ],
        ],
    ],

    // настройки для обработки текста в результатах поиска
    'search' => [
        // Разрешённые теги
        'cfgAllowTags' => [
            // вызов метода с параметрами
            [
                ['span'],
            ],
        ],
        // Разрешённые параметры тегов
        'cfgAllowTagParams' => [
            [
                'span',
                ['class' => '#text']
            ],
        ],
    ],
];