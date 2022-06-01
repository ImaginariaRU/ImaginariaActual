<?php

define('LS_VERSION', '3.0');
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/html; charset=utf-8');
header('X-Powered-By: LiveStreet CMS');

$LIVESTREET_INSTALL_PATH = getenv('LIVESTREET_INSTALL_PATH');
if (false === $LIVESTREET_INSTALL_PATH) {
    $LIVESTREET_INSTALL_PATH = dirname(__FILE__);
}

set_include_path(get_include_path() . PATH_SEPARATOR . $LIVESTREET_INSTALL_PATH);
chdir($LIVESTREET_INSTALL_PATH);

require_once 'vendor/autoload.php';

// Получаем объект конфигурации
require_once("./config/loader.php");

if (Config::Get('global.maintenance') === true) {
    die(file_get_contents(Config::Get('path.root.server') . '/templates/maintenance.html' ) );
}

/**
 * Заводим двигатель
 */
require_once(Config::Get('path.root.engine') . "/classes/Engine.class.php");

$oProfiler = ProfilerSimple::getInstance(Config::Get('path.root.server') . '/logs/' . Config::Get('sys.logs.profiler_file'), Config::Get('sys.logs.profiler'));
$iTimeId = $oProfiler->Start('full_time');

try {
    $oRouter = Router::getInstance();
    $oRouter->Exec();
} catch (Exception $e) {
    dump($e->getMessage());
    dump($e->getTrace());
}

$oProfiler->Stop($iTimeId);
