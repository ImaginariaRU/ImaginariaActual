<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

// --- main class ---

class ConfigEngine
{

    // --- functions for working with plugin config ---

    static public function SaveMyConfig($oPluginSomeClass, $iAutoLoad = 1)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_SaveMyConfig($oPluginSomeClass, $iAutoLoad);
    }

    // ---

    static public function GetMyConfig($oPluginSomeClass)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_GetMyConfig($oPluginSomeClass);
    }

    // --- functions for working with other data ---

    static public function SaveMyData($oPluginSomeClass, $sKey, $aMixedData = null)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_SaveMyData($oPluginSomeClass, $sKey, $aMixedData);
    }

    // ---

    static public function GetMyData($oPluginSomeClass, $sKey)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_GetMyData($oPluginSomeClass, $sKey);
    }

    // ---

    static public function DeleteMyData($oPluginSomeClass, $sKey)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_DeleteMyData($oPluginSomeClass, $sKey);
    }

    // ---

    static public function GetMyDataByLike($oPluginSomeClass, $sKey)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_GetMyDataByLike($oPluginSomeClass, $sKey);
    }

    // ---

    static public function DeleteMyDataByLike($oPluginSomeClass, $sKey)
    {
        $oEngine = Engine::getInstance();
        return $oEngine->PluginConfigengine_Config_DeleteMyDataByLike($oPluginSomeClass, $sKey);
    }

}

// --- short alias ---

class CE extends ConfigEngine
{

}
