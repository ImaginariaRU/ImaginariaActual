<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginConfigengine_ModuleConfig extends Module
{
    protected $oMapper = null;

    // ---

    public function Init()
    {
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

    //
    // --- Public Interface (wrappers) ---
    //

    // Stores plugin`s config into DB
    public function SaveMyConfig($oPluginSomeClass, $iAutoLoad = 1)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->SaveConfig($this->GetPluginNameToLower($oPluginSomeClass), $iAutoLoad);
    }

    // ---

    // Manual getting plugin`s config from DB and applying it

    public function SaveConfig($sPluginName, $iAutoLoad, $sSerializedConfig = null)
    {
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'ce_autoload',
                'ce_config_' . $sPluginName,
                'ce_all_configs'
            )
        );
        if (is_null($sSerializedConfig)) {
            $sSerializedConfig = serialize(Config::Get('plugin.' . $sPluginName));
        }
        return $this->oMapper->SaveConfig($sPluginName, $sSerializedConfig, $iAutoLoad);
    }

    // ---

    // Manual saving some data by text key that dont need to store in config of plugin

    protected function GetPluginNameToLower($oPluginSomeClass)
    {
        return strtolower(Engine::GetPluginName($oPluginSomeClass));
    }

    // ---

    // Manual receiving some data for plugin by text key

    public function GetMyConfig($oPluginSomeClass)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->GetConfig($this->GetPluginNameToLower($oPluginSomeClass));
    }

    // ---

    // Manual deleting some data by text key

    public function GetConfig($sPluginName, $bReturnDataOnly = false)
    {
        $sFilter = "
			`pluginname` = '" . $sPluginName . "'
		";

        $aConfig = $this->PluginConfigengine_Configengine_GetUsingCache(
            array(
                $this->oMapper,
                'GetConfig'
            ),
            'ce_config_' . $sPluginName,
            60 * 60 * 24 * 7,        // cache live time - 7 days
            $sFilter, 1, 1
        );
        if ($aConfig ['count'] != 0) {
            if ($bReturnDataOnly) {
                return $aConfig ['result'];
            } else {
                $this->MergeSettingsAndApply($aConfig ['result']);
            }
        }
        return true;
    }

    // ---

    // Manual receiving some data for plugin by like request by minimal text key%

    protected function MergeSettingsAndApply($aResult)
    {
        $aActivePlugins = $this->Plugin_GetActivePlugins();
        foreach ($aResult as $Res) {
            $sPluginName = $Res ['pluginname'];
            $sSerialized = $Res ['serialized'];

            // check if this plugin is activated
            if (!in_array($sPluginName, $aActivePlugins)) continue;

            $aOriginalSettingsFromConfig = Config::Get('plugin.' . $sPluginName);
            $aSavedSettingsFromDB = unserialize($sSerialized);

            $aMixedSettings = array_merge($aOriginalSettingsFromConfig, $aSavedSettingsFromDB);
            Config::Set('plugin.' . $sPluginName, $aMixedSettings);
        }
    }

    // ---

    // Manual deleting some data by like request by minimal text key%

    public function SaveMyData($oPluginSomeClass, $sKey, $aMixedData = null)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->SaveData($this->GetPluginNameToLower($oPluginSomeClass), $sKey, $aMixedData);
    }

    //
    // --- Internal low level functions ---
    //

    protected function SaveData($sPluginName, $sKey, $aMixedData)
    {
        if (empty ($sKey)) return false;
        $sPluginNameKey = $this->GetPluginKeyForData($sPluginName, $sKey);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'ce_data_' . $sPluginNameKey,
                'ce_data_like'
            )
        );
        $sSerializedData = serialize($aMixedData);
        return $this->oMapper->SaveConfig($sPluginNameKey, $sSerializedData, 0);
    }

    // ---

    protected function GetPluginKeyForData($sPluginName, $sKey)
    {
        return Config::Get('plugin.configengine.plugins_data_signature_prefix') . $sPluginName . "_" . (string)$sKey;
    }

    // ---

    // public for "import" functions

    public function GetMyData($oPluginSomeClass, $sKey)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->GetData($this->GetPluginNameToLower($oPluginSomeClass), $sKey);
    }

    // ---

    // public for "export" functions

    protected function GetData($sPluginName, $sKey)
    {
        if (empty ($sKey)) return false;
        $sPluginNameKey = $this->GetPluginKeyForData($sPluginName, $sKey);
        $sFilter = "
			`pluginname` = '" . $sPluginNameKey . "'
		";

        $aData = $this->PluginConfigengine_Configengine_GetUsingCache(
            array(
                $this->oMapper,
                'GetConfig'
            ),
            'ce_data_' . $sPluginNameKey,
            60 * 60 * 24 * 7,        // cache live time - 7 days
            $sFilter, 1, 1
        );
        if ($aData ['count'] != 0) {
            return unserialize($aData ['result'] [0] ['serialized']);
        }
        return false;
    }

    // ---

    public function DeleteMyData($oPluginSomeClass, $sKey)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->DeleteData($this->GetPluginNameToLower($oPluginSomeClass), $sKey);
    }

    // ---

    protected function DeleteData($sPluginName, $sKey)
    {
        if (empty ($sKey)) return false;
        $sPluginNameKey = $this->GetPluginKeyForData($sPluginName, $sKey);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'ce_data_' . $sPluginNameKey,
                'ce_data_like'
            )
        );
        $sFilter = "
			`pluginname` = '" . $sPluginNameKey . "'
		";
        return $this->oMapper->DeleteConfig($sFilter);
    }

    // ---

    public function GetMyDataByLike($oPluginSomeClass, $sKey)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->GetDataByLike($this->GetPluginNameToLower($oPluginSomeClass), $sKey);
    }

    // ---

    protected function GetDataByLike($sPluginName, $sKey)
    {
        if (empty ($sKey)) return false;
        $sPluginNameKey = $this->GetPluginKeyForData($sPluginName, $sKey);
        $sFilter = "
			`pluginname` LIKE '" . $sPluginNameKey . "%'
		";

        $aData = $this->PluginConfigengine_Configengine_GetUsingCache(
            array(
                $this->oMapper,
                'GetConfig'
            ),
            array(
                'ce_data_like_' . $sPluginNameKey,
                'ce_data_like'
            ),
            60 * 60 * 24 * 7,        // cache live time - 7 days
            $sFilter
        );
        if ($aData ['count'] != 0) {
            for ($i = 0; $i < count($aData ['result']); $i++) {
                $aData ['result'] [$i] ['data'] = unserialize($aData ['result'] [$i] ['serialized']);
            }
            return $aData ['result'];
        }
        return array();
    }

    // ---

    public function DeleteMyDataByLike($oPluginSomeClass, $sKey)
    {
        if (!is_object($oPluginSomeClass)) return false;
        return $this->DeleteDataByLike($this->GetPluginNameToLower($oPluginSomeClass), $sKey);
    }

    //
    // --- Engine autoload function (dont use it, its for engine init) ---
    //

    // gets all configs that need to load on engine start

    protected function DeleteDataByLike($sPluginName, $sKey)
    {
        if (empty ($sKey)) return false;
        $sPluginNameKey = $this->GetPluginKeyForData($sPluginName, $sKey);
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'ce_data_' . $sPluginNameKey,
                'ce_data_like'
            )
        );
        $sFilter = "
			`pluginname` LIKE '" . $sPluginNameKey . "%'
		";
        return $this->oMapper->DeleteConfig($sFilter, PHP_INT_MAX);
    }

    // ---

    // parses received from DB data into objects and merges them with original plugin(s) config(s) (for getting new keys)

    public function AutoLoadConfigs()
    {
        $sFilter = "
			`autoload` = 1
		";

        $aAutoLoadConfigs = $this->PluginConfigengine_Configengine_GetUsingCache(
            array(
                $this->oMapper,
                'GetConfig'
            ),
            'ce_autoload',
            60 * 60 * 24 * 7,        // cache live time - 7 days
            $sFilter
        );
        if ($aAutoLoadConfigs ['count'] != 0) {
            $this->MergeSettingsAndApply($aAutoLoadConfigs ['result']);
        }
        return true;
    }

    // --- Configs listing functions ---

    // gets all configs by page

    public function GetConfigsList($iPage, $iPerPage)
    {
        $aConfigs = $this->PluginConfigengine_Configengine_GetUsingCache(
            array(
                $this->oMapper,
                'GetConfig'
            ),
            'ce_all_configs',
            60 * 60 * 24 * 7,        // cache live time - 7 days
            null, $iPage, $iPerPage
        );
        return $aConfigs;
    }

    // ---

    // delete config from DB by plugin name
    public function DeletePluginSettings($sPluginName)
    {
        if (empty ($sPluginName)) return false;
        $this->Cache_Clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG, array(
                'ce_autoload',
                'ce_config_' . $sPluginName,
                'ce_all_configs'
            )
        );
        $sFilter = "
			`pluginname` = '" . $sPluginName . "'
		";
        return $this->oMapper->DeleteConfig($sFilter);
    }

}

?>