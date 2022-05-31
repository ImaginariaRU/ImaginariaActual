<?php
/*
  Simplesearch plugin
  (P) PSNet, 2008 - 2013
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
  http://livestreetguide.com/developer/PSNet/
*/

return array(

    // ошибки
    'Error' => 'Ошибка',
    'Search_Query_Is_Wrong' => 'Ваш поисковый запрос слишком короткий или длинный',
    'Search_Word_Is_Wrong' => 'Слово "<b>%%word%%</b>" слишком короткое или длинное и исключено из поиска',
    'Search_Query_Is_Empty' => 'Для поиска не осталось слов',
    'Topics_Search_Is_Disabled' => 'Поиск по топикам отключен',
    'Comments_Search_Is_Disabled' => 'Поиск по комментариям отключен',
    'People_Search_Is_Disabled' => 'Поиск по людям отключен',
    'Blogs_Search_Is_Disabled' => 'Поиск по описаниям блогов отключен',
    'Tags_Search_Is_Disabled' => 'Поиск по тегам (в блогах) отключен',

    // заголовки
    'Search_On' => 'Результаты поиска для: ',
    'People_Search' => 'Поиск по людям',
    'People_Search_Tip' => 'Поиск выполняется по логину, имени, стране, городу и поле "о себе".',
    'People_Search_Strict_Search' => 'Точный поиск',
    'Search_Field_Tip' => 'Поиск',

    // для блогов
    'Blogs_Search_Show_Description' => 'Показать описание блога',
    'Blogs_Search_Show_More_Options' => 'Показать больше опций',
    'Blogs_Search_Here_Only' => 'Искать только в этом блоге:',
    'Blogs_Search_Here_Only_Disabled' => '&mdash; искать во всех блогах &mdash;',

    'Search_In_Favorite' => 'Поиск по избранному',

    // меню
    'People_Search_Menu' => 'людей',
    'Blogs_Search_Menu' => 'описаний блогов',
    'Tags_Search_Menu' => 'топиков по тегу',

    // поиск по людям
    'People_Search_Found' => 'Совпадение в',
    'People_Search_Found_By' => array(
        's1' => 'логине',
        's2' => 'имени',
        's3' => 'стране',
        's4' => 'городе',
        's8' => 'поле "о себе"'
    ),

    // поиск в текстах
    'Search_Found' => 'совпадение:',
    'Search_Found_By' => array(
        's1' => 'В тексте найдено не точное',               // not strict
        's2' => 'В заголовке найдено не точное',
        's3' => 'В заголовке и тексте найдено не точное',
        's4' => 'В тексте найдено точное',                  // strict
        's5' => 'В заголовке найдено точное',
        's6' => 'В заголовке и тексте найдено точное',
    ),

    // если ничего не найдено
    'Find_In_Other_SE' => 'Найти',
    'Find_In_Other_SE_In' => 'средствами',

);
