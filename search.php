<?php

use Arris\Path;
use Arris\Toolkit\SphinxToolkit;
use Foolz\SphinxQL\SphinxQL;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/engine/lib/ConfigSimple/Config.class.php';

Config::LoadFromFile(__DIR__ . '/config/config.php');

if (file_exists(Config::Get('path.root.server') . '/config/config.local.php')) {
    Config::LoadFromFile(Config::Get('path.root.server') . '/config/config.local.php', false);
}
$DATETIME_YMD = (new \DateTime())->format( 'Y-m-d' );
$APP_INSTANCE = bin2hex( random_bytes( 8 ) );
$_PATH_ROOT = Path::create( Config::Get( "path.root.project" ) );
$_PATH_LOGS = $_PATH_ROOT->join('logs')->joinName("{$DATETIME_YMD}__search.log")->toString();

$LOGGER = new Logger( "imaginaria.{$APP_INSTANCE}" );
$LOGGER->pushHandler( new StreamHandler( $_PATH_LOGS, Logger::DEBUG ) );

// begin

$search_query = $_REQUEST['query'] ?? '';
$limit = (int)($_REQUEST['limit'] ?? 100);

if (array_key_exists('where', $_REQUEST)) {
    if ($_REQUEST['where'] == 'comments') {
        $search_target = 'comments';
    } else {
        $search_target = 'topics';
    }
} else {
    $search_target = 'topics';
}

if (!empty($search_query)) {
    SphinxToolkit::init('127.0.0.1', 9306);

    if ($search_target == 'comments') {
        $dataset = searchAtComments($search_query, $limit);
    } else {
        $dataset = searchAtTopics($search_query, $limit);
    }
} else {
    $dataset = [];
}

$LOGGER->debug("Looking for `{$search_query}` at {$search_target}", [ $search_query, $search_target, count($dataset), \Arris\Helpers\Server::getIP() ]);

echo <<<SEARCH_HEADER
<style>
    em {
        background-color: yellow;
    }
    input {
        font-size: large;
    }
</style>
<h3>Поиск по топикам</h3>
<form method="get" action="{$_SERVER['SCRIPT_NAME']}">
<table width="100%">
    <tr>
        <td width="100">
            <input type="text" name="query" value="{$search_query}" placeholder="искать..." size="70">
        </td>
        <td>
            <!--&nbsp;&nbsp;<input type="submit" name="at:topics" value="Искать в публикациях">
            &nbsp;&nbsp;<input type="submit" name="at:comments" value="Искать в комментариях">-->
            &nbsp;&nbsp;<button type="submit" name="where" value="topics">Искать в публикациях</button>
            &nbsp;&nbsp;<button type="submit" name="where" value="comments">Искать в комментариях</button>
            &nbsp;&nbsp;Limit: <input name="limit" value="100" placeholder="100">
        </td>
        <td width="*" style="text-align: right">
            <input type="button" onclick="window.location.href='/'" value="Назад на имажинарию" style="text-align: right">
        </td>
    </tr>
</table>
</form>
SEARCH_HEADER;

if ($search_target === 'comments') {
    printResultAtComments($dataset);
} else {
    printResultAtTopics($dataset);
}

exit(0);

/*
 * Функции скрипта
 */

function printResultAtTopics($dataset = []) {
    if (!empty($dataset)) {
        $found_string = pluralForm(count($dataset), ['публикация', 'публикации', 'публикаций']);
?>
        <strong>Найдено <?php echo count($dataset);?> <?php echo $found_string; ?>: </strong><br/><br/>
        <table width="100%" border="1">
            <tr>
                <th>Дата/время</th>
                <th>Автор</th>
                <th>Заголовок</th>
                <th>Текст</th>
            </tr>

            <?php
            foreach ($dataset as $row) {
                ?>
                <tr>
                    <td align="center">
                        <?=$row['cdate_time']?> <br>
                        <?=$row['cdate_date']?>
                    </td>
                    <td>
                        <a
                                href="/profile/<?=$row['user_login']?>/created/topics/"
                                target="_blank">
                            <?=$row['user_login']?>
                        </a>
                    </td>
                    <td>
                        <a href="/p/<?=$row['nice_url']?>.html" target="_blank">
                            <?=$row['topic_title']?>
                        </a>
                    </td>
                    <td>
                        <?=$row['topic_text']?>
                    </td>

                </tr>
            <?php } ?>
        </table>

<?php
    } else {
        echo <<<PRAT_NOTHING
Ничего не найдено
PRAT_NOTHING;

    }
}

/**
 * @param string $prompt
 * @param int $limit
 * @return array
 * @throws \Foolz\SphinxQL\Exception\ConnectionException
 * @throws \Foolz\SphinxQL\Exception\DatabaseException
 * @throws \Foolz\SphinxQL\Exception\SphinxQLException
 */
function searchAtTopics(string $prompt, int $limit = 100):array {
    $query_expression = SphinxQL::expr(\implode(', ', [
        'id',
        'topic_date_add',
        'topic_publish',
        'tag',
        'nice_url',
        "highlight({before_match='<em>', after_match='</em>', around=8}, 'topic_title') AS topic_title",
        "highlight({before_match='<em>', after_match='</em>', around=8}, 'topic_text') AS topic_text",
        "user_login",
        "yearmonthday(topic_date_add) AS topic_date_ymd"
    ]));

    $query_dataset = SphinxToolkit::createInstance()
        ->select($query_expression)
        ->from("topicsIndex")
        ->offset(0)
        ->orderBy("topic_date_add", 'DESC')
        ->match(['topic_title', 'topic_text'], $prompt)
        ->limit($limit)
        ->option("max_matches", $limit*2)
    ;

    $result_data = $query_dataset->execute();

    $dataset = [];
    while ($row = $result_data->fetchAssoc()) {
        $row['cdate'] = \date('H:i / d.m.Y', $row['topic_date_add']);
        $row['cdate_time'] = \date('H:i', $row['topic_date_add']);
        $row['cdate_date'] = \date('d.m.Y', $row['topic_date_add']);
        $dataset[] = $row;
    }

    return $dataset;
}

function searchAtComments($prompt, $limit = 100) {
    $query_expression = SphinxQL::expr(\implode(', ', [
        'id',
        'comment_date',
        'user_login',
        'user_profile_avatar',
        'nice_url',
        "highlight({before_match='<em>', after_match='</em>', around=8}, 'comment_text') AS comment_text",
    ]));

    $query_dataset = SphinxToolkit::createInstance()
        ->select($query_expression)
        ->from("commentsIndex")
        ->offset(0)
        ->orderBy("comment_date", 'DESC')
        ->match(['comment_text'], $prompt)
        ->limit($limit)
        ->option("max_matches", $limit*2)
    ;

    $result_data = $query_dataset->execute();

    $dataset = [];
    while ($row = $result_data->fetchAssoc()) {
        $row['cdate'] = \date('d M Y H:i', $row['comment_date']);
        $row['imgsrc'] = empty($row['user_profile_avatar']) ? '' : <<<IMGSRC
<img src="{$row['user_profile_avatar']}" alt="{$row['user_login']}" width="32" height="32">
IMGSRC;
        $dataset[] = $row;
    }

    return $dataset;
}

function printResultAtComments($dataset) {
    if (!empty($dataset)) {
        $found_string = pluralForm(count($dataset), ['комментарий', 'комментария', 'комментариев']);

        ?>
        <strong>Найдено <?php echo count($dataset); ?> <?php echo $found_string; ?>::</strong><br/><br/>
        <table width="100%" border="1">
            <tr>
                <th colspan="2">Автор</th>
                <th>Дата</th>
                <th>Текст</th>
            </tr>

            <?php
            foreach ($dataset as $row) {
                ?>
                <tr>
                    <td align="center">
                        <?=$row['imgsrc']?> <br>
                    </td>
                    <td align="center">
                        <a
                                href="/profile/<?=$row['user_login']?>/created/topics/"
                                target="_blank">
                            <?=$row['user_login']?>
                        </a>
                    </td>
                    <td align="center">
                        <a href="/p/<?=$row['nice_url']?>.html#comment<?=$row['id']?>" target="_blank">
                            <?=$row['cdate']?>
                        </a>
                    </td>
                    <td>
                        <?=$row['comment_text']?>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <?php
    } else {
        echo <<<PRAC_NOTHING
Ничего не найдено
PRAC_NOTHING;
    }
}

function pluralForm($number, $forms, string $glue = '|'):string
{
    if (is_string($forms)) {
        $forms = explode($forms, $glue);
    } elseif (!is_array($forms)) {
        return '';
    }

    if (count($forms) != 3) return '';

    return
        ($number % 10 == 1 && $number % 100 != 11)
            ? $forms[0]
            : (
        ($number % 10 >= 2 && $number % 10 <= 4 && ($number % 100 < 10 || $number % 100 >= 20))
            ? $forms[1]
            : $forms[2]
        );
}

# -eof-

