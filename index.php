<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');

$LIVESTREET_INSTALL_PATH = getenv( 'LIVESTREET_INSTALL_PATH');
if (false === $LIVESTREET_INSTALL_PATH) {
    $LIVESTREET_INSTALL_PATH = dirname(__FILE__);
}

set_include_path(get_include_path().PATH_SEPARATOR.$LIVESTREET_INSTALL_PATH);
chdir($LIVESTREET_INSTALL_PATH);

/*if (isset( $_REQUEST[ 'sid' ] )) { session_id( $_REQUEST[ 'sid' ] ); }
session_start();*/

require_once 'vendor/autoload.php';

// Получаем объект конфигурации
require_once("./config/loader.php");

/**
 * Заводим двигатель
 */
require_once(Config::Get('path.root.engine')."/classes/Engine.class.php");

// use \Monolog\Handler\FilterHandler;
// use \Monolog\Logger;
// use \Monolog\Handler\StreamHandler;

$oProfiler=ProfilerSimple::getInstance(Config::Get('path.root.server').'/logs/'.Config::Get('sys.logs.profiler_file'),Config::Get('sys.logs.profiler'));
$iTimeId=$oProfiler->Start('full_time');

try {
    $oRouter=Router::getInstance();
    $oRouter->Exec();
} catch (Exception $e) {
    dump($e->getMessage());
    dump($e->getTrace());
}

$oProfiler->Stop($iTimeId);
