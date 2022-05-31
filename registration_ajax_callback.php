<?php
$dictionary = @include __DIR__ . '/templates/language/russian.php';
$config_global = @include __DIR__ . '/config/config.php';
$config_local = @include __DIR__ . '/config/config.local.php';

$responseCode = '0';
// get POST data
$RespData = json_decode(file_get_contents('php://input'), true);

function CallAPI($method, $url, $data = false){
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

function check_captcha($sCaptchaHash){
	$data = array(
			"secret"  => "6LeQ0lAaAAAAAKpQIcDu4VvQBpmsql1GXs2UrcwX",
			"response" => $sCaptchaHash
			);
	$cap_res = CallAPI('POST', 'https://www.google.com/recaptcha/api/siteverify',  $data);
		$response = json_decode($cap_res);

	return $response -> success; // true / false
}

function create_user($login, $email, $password){
    global $dictionary;
    global $config_global;
    global $config_local;
	
	//echo 'Creating new user...' , PHP_EOL;
	$_USER_LOGIN = $login;//readline('Enter login name: ');
	$_USER_EMAIL = $email;//readline('Enter email: ');
	$_USER_PASS = $password;//readline('Enter pass: ');
	//echo '--------------------', PHP_EOL;

	if (false == ($_USER_LOGIN && $_USER_EMAIL && $_USER_PASS)) {
			return 'err_empty';
			//die;
	}	

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

	//echo 'Checking: is exists user with given email or login? ';
	$found_users = checkUserExist($dbh, $table_user, $_USER_LOGIN, $_USER_EMAIL);

	if (empty($found_users)) {
			//echo 'Not exists!', PHP_EOL;
			
			$uid = addUser($dbh, $table_user, $_USER_LOGIN, $_USER_EMAIL, $_USER_PASS);
			
			addBlog($dbh, $table_blog, $uid, $_USER_LOGIN);
			
			//echo 'New user added.' , PHP_EOL;
			return 'ok';
	} else {
			// echo 'Exists!!!' , PHP_EOL;
			// echo '-------------------------------------------', PHP_EOL;
			// foreach ($found_users as $user) {
					// echo "{$user['user_id']} | {$user['user_login']} | {$user['user_mail']} | {$user['user_profile_name']} | ", PHP_EOL;
			// }
			// echo '-------------------------------------------', PHP_EOL;
			// echo 'Создать такого пользователя нельзя!', PHP_EOL;
			return 'err_login_mail';
	}	
}

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
    
    //echo "Creating blog for `{$login}` (id: {$user_owner_id})" , PHP_EOL;
    
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
 * @param bool $pass
 * @return string
 */
function addUser(PDO $pdo, $table_user, $login = false, $email = false, $pass = false)
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
        'user_password' =>  md5($pass),
        'user_mail'     =>  $email
    ];
    
    $sth = $pdo->prepare($sql);
    $sth->execute($data);
    
    //echo "Creating user `{$login}`, e-mail: `{$email}`" , PHP_EOL;
    
    return $pdo->lastInsertId();
}

function checkParam($Param){
	return !(empty($Param) || $Param==false || is_null($Param) || strlen($Param)<6);
}
if($RespData['stat']=='add') {
	if(!check_captcha($RespData['captcha'])){
		$responseCode = 'err_cap';
		return;
	}
	if(!checkParam($RespData['login'])){
		$responseCode = 'err_login';
		return;
	}
	if(!checkParam($RespData['email'])){
		$responseCode = 'err_email';
		return;
	}
	if(!checkParam($RespData['pass'])){
		$responseCode = 'err_pass';
		return;
	}
	
	$responseCode = create_user($RespData['login'], $RespData['email'], $RespData['pass']);
	
	/*if($resp == 1) {
		echo "ok";
	} else {
		echo "error";
	}*/
} 

echo $responseCode;
