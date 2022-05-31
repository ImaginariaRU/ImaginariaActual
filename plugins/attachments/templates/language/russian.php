<?php
//
//  Attachments plugin
//  (P) Rafrica.net Studio, 2010 - 2012
//  http://we.rafrica.net/
//

return array(
    // попап окна (алерты)
    'new_topic_error_not_allowed_file_types' => 'Недопустимый формат файла. Разрешено загружать файлы типов: ',
    'do_you_really_want_to_delete_this_file' => 'Вы действительно хотите удалить этот файл?',

    // инфо-подсказки
    'new_topic_add_file_msg' => '<span>Прикрепить файл:</span>',
    'new_topic_first_message' => 'Выберите файл',
    'new_topic_uploading_file_now' => 'Файл загружается...',
    'new_topic_file_upload_done' => 'Файл добавлен',
    'element_title_download_file' => 'Скачать файл',
    'element_title_delete_file' => 'Удалить файл',
    'file_was_deleted' => 'Файл удален',
    'not_attached_files' => 'Неприкрепленные файлы:',
    'not_attached_files_title' => 'Файлы, загруженные вами в публикацию, которая не была сохранена (опубликована)',
    'attach_file_to_new_topic' => 'Привязать файл к этой публикации',
    'cant_attach_file_to_saved_topic' => 'Привязать файл можно только к новой публикации. К существующей - никак',
    'there_is_no_unattached_files' => 'Нет непривязанных файлов.',

    // вывод списка топиков
    'topic_files_attached' => 'Прикреплено ',
    'topic_N_files' => ' файлов.',
    'files_in_topics' => 'Прикрепленные файлы: ',

    // общие ошибки (клиентская часть)
    'sidebar_detection_general_error' => 'Ошибка в вёрстке шаблона для сайдбара плагина Attachments. Сохраняйте классы обьектов для корректной работы',
    'wrong_parameter_file_form_place' => 'Неверный параметр положения формы для загрузки файлов в конфиге для плагина Attachments',

    // ошибки обработки серверной части
    'upload_not_logged_in' => 'Пользователь не вошел в систему',
    'upload_rating_to_low' => 'Недостаточно рейтинга для загрузки файла',
    'upload_file_to_big' => 'Размер файла больше установленного лимита',
    'upload_universal_answer' => 'Go away!',
    'upload_error_creating_subfolder' => 'Ошибка создания папки файла для пользователя. Проверьте права',
    'upload_error_moving_to_subfolder' => 'Ошибка перемещения файла',
    'upload_error_topic_if_full' => 'Достигнут лимит количества файлов на одну публикацию',
    'upload_unattached_limit' => 'Слишком много неприсоединенных файлов. Удалите файлы из списка справа или присоедините к текущей публикации',

    'upload_err_ini_size' => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
    'upload_err_form_size' => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
    'upload_err_partial' => 'The uploaded file was only partially uploaded',
    'upload_err_no_file' => 'No file was uploaded',
    'upload_err_no_tmp_dir' => 'Missing a temporary folder',
    'upload_err_cant_write' => 'Failed to write file to disk',
    'upload_err_extension' => 'File upload stopped by extension',
    'upload_err_unknown' => 'Unknown upload error',
);
