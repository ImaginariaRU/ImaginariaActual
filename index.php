<?php

use Arris\Path;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

define('LS_VERSION', '3.0');
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');

/*define( 'LIVESTREET_PATH_INSTALL', dirname( __DIR__, 1 ) );
define( 'LIVESTREET_PATH_WWW', LIVESTREET_PATH_INSTALL . '/www' );
define( 'LIVESTREET_PATH_ENGINE', LIVESTREET_PATH_INSTALL . '/www/engine');*/

$LIVESTREET_INSTALL_PATH = getenv('LIVESTREET_INSTALL_PATH');
if (false === $LIVESTREET_INSTALL_PATH) {
    $LIVESTREET_INSTALL_PATH = dirname(__FILE__);
}

set_include_path(get_include_path() . PATH_SEPARATOR . $LIVESTREET_INSTALL_PATH);
chdir($LIVESTREET_INSTALL_PATH);

require_once 'vendor/autoload.php';

// Получаем объект конфигурации
require_once("./config/loader.php");

try {
    if (Config::Get('global.maintenance') === true) {
        die(file_get_contents(Config::Get('path.root.server') . '/templates/maintenance.html' ) );
    }

    require_once(Config::Get('path.root.engine') . "/classes/Engine.class.php");

    $app_instance = bin2hex( random_bytes( 8 ) );
    $DATETIME_YMD = (new \DateTime())->format( 'Y-m-d' );

    $_PATH_ROOT = Path::create( Config::Get( "path.root.project" ) );
    $MONOLOG_STATS_FILE = $_PATH_ROOT->join('logs')->joinName("{$DATETIME_YMD}__stat.log")->toString();
    $MONOLOG_ERROR_FILE = $_PATH_ROOT->join('logs')->joinName("{$DATETIME_YMD}__error.log")->toString();
    $MONOLOG_ERROR_MYSQL = $_PATH_ROOT->join('logs')->joinName("{$DATETIME_YMD}__mysql.log")->toString();

    $LOGGER = new Logger( "imaginaria.{$app_instance}" );
    $LOGGER->pushHandler( new StreamHandler( $MONOLOG_STATS_FILE, Logger::DEBUG ) );
    $LOGGER->pushHandler( new StreamHandler( $MONOLOG_ERROR_FILE, Logger::ERROR, false ) );

    $oProfiler = ProfilerSimple::getInstance(Config::Get('path.root.server') . '/logs/' . Config::Get('sys.logs.profiler_file'), Config::Get('sys.logs.profiler'));
    $iTimeId = $oProfiler->Start('full_time');

    $oRouter = Router::getInstance();
    $oRouter->Exec();

} catch (Exception $e) {
    dump($e->getMessage());
    dump($e->getTrace());
}

$oProfiler->Stop($iTimeId);
