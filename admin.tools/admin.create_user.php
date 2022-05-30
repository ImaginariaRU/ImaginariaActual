<?php

if (php_sapi_name() !== 'cli') die('Not allowed');

echo 'Creating new user...' , PHP_EOL;
$_USER_LOGIN = readline('Enter login name: ');
$_USER_EMAIL = readline('Enter email: ');
echo '--------------------', PHP_EOL;

if (false == ($_USER_LOGIN && $_USER_EMAIL)) {
    echo '[ERROR] Login or email is empty.' . PHP_EOL;
    die;
}

$dictionary = @include __DIR__ . '/templates/language/russian.php';
$config_global = @include __DIR__ . '/config/config.php';
$config_local = @include __DIR__ . '/config/config.local.php';

$table_user = str_replace('___db.table.prefix___', $config_local['db']['table']['prefix'], $config_global['db']['table']['user']);
$table_blog = str_replace('___db.table.prefix___', $config_local['db']['table']['prefix'], $config_global['db']['table']['blog']);
$db_config = $config_local['db']['params'];

$db_host = $db_config['host'] ?? 'localhost';
$db_name = $db_config['dbname'] ?? 'mysql';
$db_user = $db_config['user'] ?? 'root';
$db_pass = $db_config['pass'] ?? '';
$db_port = $db_config['port'] ?? 3306;

$dsl = sprintf("mysql:host=%s;port=%s;dbname=%s", $db_host, $db_port, $db_name);
$dbh = new PDO($dsl, $db_user, $db_pass);
$dbh->exec("SET NAMES utf8 COLLATE utf8_general_ci");

echo 'Checking: is exists user with given email or login? ';
$found_users = checkUserExist($dbh, $table_user, $_USER_LOGIN, $_USER_EMAIL);

if (empty($found_users)) {
    echo 'Not exists!', PHP_EOL;
    
    $uid = addUser($dbh, $table_user, $_USER_LOGIN, $_USER_EMAIL);
    
    addBlog($dbh, $table_blog, $uid, $_USER_LOGIN);
    
    echo 'New user added.' , PHP_EOL;
} else {
    echo 'Exists!!!' , PHP_EOL;
    echo '-------------------------------------------', PHP_EOL;
    foreach ($found_users as $user) {
        echo "{$user['user_id']} | {$user['user_login']} | {$user['user_mail']} | {$user['user_profile_name']} | ", PHP_EOL;
    }
    echo '-------------------------------------------', PHP_EOL;
    echo 'Создать такого пользователя нельзя!', PHP_EOL;
}

die;

// ===================

/**
 * Проверяет существование пользователя
 *
 * @param PDO $pdo
 * @param $table_user
 * @param bool $login
 * @param bool $email
 * @return array
 */
function checkUserExist(PDO $pdo, $table_user, $login = false, $email = false)
{
    $sql = "
SELECT user_id, user_login, user_mail, COALESCE(user_profile_name, '----') AS user_profile_name FROM {$table_user} WHERE `user_login` = :user_login OR `user_mail` = :user_mail
    ";
    $data = [
        'user_login'    =>  $login,
        'user_mail'     =>  $email
    ];
    
    $sth = $pdo->prepare($sql);
    $sth->execute($data);
    return $sth->fetchAll();
}

/**
 * Создает блог для пользователя
 *
 * @param PDO $pdo
 * @param $table_blog
 * @param $user_owner_id
 * @param $login
 * @return bool
 */
function addBlog(PDO $pdo, $table_blog, $user_owner_id, $login)
{
    global $dictionary;
    
    $sql = "
INSERT INTO {$table_blog}
    SET user_owner_id = :user_owner_id,
        blog_title = :blog_title,
        blog_description = :blog_description,
        blog_type = 'personal',
        blog_date_add = NOW(),
        blog_limit_rating_topic = -1000,
        blog_url = :blog_url,
        blog_avatar = :blog_avatar
";
    $data = [
        'user_owner_id'     =>  $user_owner_id,
        'blog_title'        =>  $dictionary['blogs_personal_title'] . ' ' . $login,
        'blog_description'  =>  $dictionary['blogs_personal_description'],
        'blog_url'          =>  null,
        'blog_avatar'       =>  null
    ];
    
    echo "Creating blog for `{$login}` (id: {$user_owner_id})" , PHP_EOL;
    
    $sth = $pdo->prepare($sql);
    return $sth->execute($data);
}

/**
 * Добавляет пользователя
 *
 * @param PDO $pdo
 * @param $table_user
 * @param bool $login
 * @param bool $email
 * @return string
 */
function addUser(PDO $pdo, $table_user, $login = false, $email = false)
{
    $sql = "
INSERT INTO {$table_user}
    SET user_login = :user_login,
        user_password = :user_password,
        user_mail = :user_mail,
        user_date_register = NOW(),
        user_ip_register = '127.0.0.1',
        user_activate = 1,
        user_activate_key = ''
";
    
    $data = [
        'user_login'    =>  $login,
        'user_password' =>  md5('password'),
        'user_mail'     =>  $email
    ];
    
    $sth = $pdo->prepare($sql);
    $sth->execute($data);
    
    echo "Creating user `{$login}`, e-mail: `{$email}`" , PHP_EOL;
    
    return $pdo->lastInsertId();
}

