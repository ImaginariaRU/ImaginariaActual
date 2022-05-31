<?php
/**
 * User: Karel Wintersky <karel.wintersky@gmail.com>
 * Date: 22.02.2019, time: 11:14
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

$LIVESTREET_INSTALL_PATH = getenv( 'LIVESTREET_INSTALL_PATH');
if (false === $LIVESTREET_INSTALL_PATH) {
    $LIVESTREET_INSTALL_PATH = dirname(__FILE__);
}

set_include_path(get_include_path().PATH_SEPARATOR.$LIVESTREET_INSTALL_PATH);
chdir($LIVESTREET_INSTALL_PATH);

require_once __DIR__. '/vendor/autoload.php';

// Получаем объект конфигурации
require_once("./config/loader.php");

$dbname = Config::Get('db.params.dbname');
$dbuser = Config::Get('db.params.user');
$dbpass = Config::Get('db.params.pass');

$dsl = "mysql:host=localhost;port=3306;dbname={$dbname}";
$dbh = new \PDO($dsl, $dbuser, $dbpass);
$dbh->exec("SET NAMES UTF-8;");
$dbh->exec("SET NAMES UTF8;");
$dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);

// ----
if (!isset($_GET['comment_id'])) {
    die('Required comment_id');
}

$sql = "SELECT ls_comment.user_id, ls_comment.comment_text, 

ls_user.user_login,
ls_user.`user_profile_name`


FROM ls_comment , ls_user


WHERE comment_id = :comment_id
AND ls_comment.`user_id` = ls_user.`user_id`";

$sth = $dbh->prepare($sql);
$sth->execute(['comment_id' => $_GET['comment_id']]);

$comment_data = $sth->fetch();

$sql = "SELECT 
ls_vote.`vote_direction`,
ls_vote.vote_date,
ls_user.user_login,
ls_user.user_profile_name

FROM ls_vote, ls_user

WHERE target_type = 'comment'
AND target_id = :comment_id
AND ls_vote.user_voter_id = ls_user.user_id";

$sth = $dbh->prepare($sql);
$sth->execute(['comment_id' => $_GET['comment_id'] ]);

$votes = $sth->fetchAll();

$votes_up = [];
$votes_down = [];
$votes_abstain = [];

array_walk($votes, function($data) use(&$votes_up, &$votes_down, &$votes_abstain){
    if ($data['vote_direction'] == 1) {
       $votes_up[] = $data;
    } elseif ($data['vote_direction'] == -1) {
       $votes_down[] = $data;
    } else {
       $votes_abstain[] = $data;
    }
});



// вставлен хардкод в W:\imaginaria.ru\ImaginariaLS\templates\skin\synio\comment.tpl 

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');
?>
<html>
<head>
    <title>Votes viewer for comment <?php ?></title>
</head>
<body>
<body>
Итоги голосования для комментария:
<fieldset>
    <legend><?php echo $comment_data['user_profile_name']?></legend>
    <?php echo $comment_data['comment_text']; ?>
</fieldset>

<table border="1">
    <tr>
        <?php if (!empty($votes_up)) { ?>
        <th width="20%">+</th>
        <?php } ?>
        <?php if (!empty($votes_down)) { ?>
        <th width="20%">-</th>
        <?php } ?>
        <?php if (!empty($votes_abstain)) { ?>
        <th width="20%">воздержались</th>
        <?php } ?>
    </tr>
    <tr>
        <?php if (!empty($votes_up)) { ?>
        <td>
            <ul>
            <?php 
            foreach ($votes_up as $vote){
                echo "<li><a href=\"https://imaginaria.ru/profile/{$vote['user_login']}/\">{$vote['user_profile_name']}</a></li>";    
            }
            ?>
            </ul>
        </td>
        <?php } ?>
        <?php if (!empty($votes_down)) { ?>
        <td>
            <ul>
                <?php
                foreach ($votes_down as $vote){
                    echo "<li><a href=\"https://imaginaria.ru/profile/{$vote['user_login']}/\">{$vote['user_profile_name']}</a></li>";
                }
                ?>
            </ul>
        </td>
        <?php } ?>
        <?php if (!empty($votes_abstain)) { ?>
        <td>
            <ul>
                <?php
                foreach ($votes_abstain as $vote){
                    echo "<li><a href=\"https://imaginaria.ru/profile/{$vote['user_login']}/\">{$vote['user_profile_name']}</a></li>";
                }
                ?>
            </ul>
        </td>
        <?php } ?>
    </tr>
</table>
</body>
</body>
</html>












