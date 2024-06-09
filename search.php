<?php

use Arris\Toolkit\SphinxToolkit;
use Foolz\SphinxQL\SphinxQL;

require_once __DIR__ . '/vendor/autoload.php';

$template_query = '';
if (!empty($_REQUEST['query'])) {
    $template_query = $_REQUEST['query'];
    SphinxToolkit::init('127.0.0.1', 9306);

    $query_expression = SphinxQL::expr(implode(', ', [
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
        ->match(['topic_title', 'topic_text'], $template_query)
        ->limit(100);

    $result_data = $query_dataset->execute();

    $dataset = [];
    while ($row = $result_data->fetchAssoc()) {
        $row['cdate'] = date('H:i / d.m.Y', $row['topic_date_add']);
        $row['cdate_time'] = date('H:i', $row['topic_date_add']);
        $row['cdate_date'] = date('d.m.Y', $row['topic_date_add']);
        $dataset[] = $row;
    }
}
?>
<style>
    em {
        background-color: yellow;
    }
    input {
        font-size: large;
    }
</style>
<h3>Поиск по топикам</h3>
<form method="get" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<table width="100%">
    <tr>
        <td width="100">
            <input type="text" name="query" value="<?=$template_query?>" placeholder="искать..." size="70">
        </td>
        <td>
            &nbsp;&nbsp;<input type="submit" value="Искать по заголовку и тексту топика">
        </td>
        <td width="*" style="text-align: right">
            <input type="button" onclick="window.location.href='/'" value="Назад на имажинарию" style="text-align: right">
        </td>
    </tr>
</table>
</form>
<table width="100%" border="1">
    <?php if (!empty($dataset)) { ?>

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

<?php } else { ?>
Ничего не найдено
<?php } ?>

