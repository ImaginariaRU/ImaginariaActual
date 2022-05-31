<?php
/********************************************
 * Author: Vladimir Linkevich
 * e-mail: Vladimir.Linkevich@gmail.com
 * since 2011-02-25
 ********************************************/
$config = [];

/*
 * will set cut after XXX characters
 *
 * Автоматически вставляем тег <cut> после ХХХ символов (слова и теги разрывать не должен)
 */
$config['length_before_cut'] = 500;

/*
 * Does not allow to input CUT between opening and closing tags of listed below;
 *
 * Не разрешает вставлять CUT внутри этих тегов:
 */
$config['unbreakable_tags'] = array('video', 'code', 'a', 'blockquote', 'ul', 'ol', 'h4', 'h5', 'h6', 'cut');

/*
 * Do you want to cut topics in personal blogs as well?
 *
 * В персональных блогах топики резать будем?
 */
$config['cut_personal_topics'] = true;

/*
 * Should we cut text for topics by admin?
 *
 * Топики администратора тоже урезать?
 */
$config['cut_admin_topics'] = true;

/*
 * If LightModeOn is set "true" then IF user had put the <cut> into text autocutting check SecondBarrier.
 * Otherways AutoCut will override user's cut IF user's cut is AFTER the length_before_cut value.
 *
 * Если включить опцию ниже, то ЕСЛИ пользователь поставил <cut>, АвтоКат установит другой лимит: SecondBarrier.
 * Иначе, пользовательский кат будет заменен автоматическим ЕСЛИ он был установлен ПОСЛЕ лимита (length_before_cut).
 */
$config['LightModeOn'] = false;

/* 
 * Add an other text length check if LightModeOn. set 0 if you accept ANY length before user's manual cut;
 *
 * Вторая проверка (при LightModeOn). Установите 0, если пользователь может поставить КАТ в ЛЮБОМ месте,
 * или установите второе разумное ограничение;
 */
$config['SecondBarrier'] = 800;

/**
 * KW:
 *
 * Минимальная длина "остатка" поста (в процентах от полной длины текста), при котором применяется тег 'cut'.
 * Если длина обрезаемого текста меньше этой (общая длина текста) * (это значение) - обрезки текста не происходит.
 *
 */
$config['length_of_uncuttable_text_relative'] = 5;

/*
 * KW:
 *
 * минимальная длина "остатка" поста в символах (utf8) при которой не происходит применения обрезки текста
 */
$config['length_of_uncuttable_text_absolute'] = 40;

return $config;

