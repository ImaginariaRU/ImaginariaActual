<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginConfigengine_ActionConfigengine extends ActionPlugin
{

    protected $oUserCurrent = null;
    protected $iCurrentPage = 1;
    protected $iPerPage = 20;
    protected $sSettingsFileExtension = 'ces';

    // ---

    public function Init()
    {
        if (!$this->oUserCurrent = $this->User_GetUserCurrent() or !$this->oUserCurrent->isAdministrator()) {
            return Router::Action('error');
        }
        $this->SetDefaultEvent('index');
        $this->PreparePaging();
        $this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.configengine.Title'));
    }

    // ---

    public function PreparePaging()
    {
        if (!$this->iCurrentPage = intval(preg_replace('#^page(\d+)$#', '\1', $this->getParam(0)))) {
            $this->iCurrentPage = 1;
        }
        $this->iPerPage = Config::Get('plugin.configengine.Plugins_Count_Per_Page');
    }

    // --- pages ---

    public function IndexEvent()
    {
        $aConfigs = $this->PluginConfigengine_Config_GetConfigsList(
            $this->iCurrentPage,
            $this->iPerPage
        );
        $aPaging = $this->Viewer_MakePaging(
            $aConfigs ['count'],
            $this->iCurrentPage,
            $this->iPerPage,
            Config::Get('pagination.pages.count'),
            Router::GetPath('configengine') . $this->sCurrentEvent
        );
        $this->Viewer_Assign('aPaging', $aPaging);
        $this->Viewer_Assign('aCEConfigs', $aConfigs ['result']);
    }

    // ---

    public function ExportEvent()
    {
        $this->Security_ValidateSendForm();
        if (!$sPluginName = (string)$this->getParam(0)) return Router::Location(Router::GetPath('configengine'));
        $aPluginConfig = $this->PluginConfigengine_Config_GetConfig($sPluginName, true);
        $sFileExport = serialize($aPluginConfig [0]);

        // set needed headers for saving file by user
        header($_SERVER ["SERVER_PROTOCOL"] . ' 200 OK');
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Last-Modified: ' . gmdate('r', time()));
        /*
         * нужно считать длину всех символов, но не в мультибайтовой кодировке,
         * т.к. тогда часть данных будет обрезана т.к. фактически длина данных больше чем указано при ипользовании ф-й mb_
         */
        //header ('Content-Length: ' . mb_strlen ($sFileExport, 'utf-8'));
        header('Content-Length: ' . strlen($sFileExport));
        header('Connection: close');
        header('Content-Disposition: attachment; filename="' . $sPluginName . '_settings.' . $this->sSettingsFileExtension . '";');
        echo $sFileExport;
        exit ();
    }

    // ---

    public function ImportEvent()
    {
        $this->Security_ValidateSendForm();
        $bReturnToReferer = getRequest('bReturnToReferer');
        $sThisPluginNameOnly = getRequest('sThisPluginNameOnly');
        if (!isset ($_FILES ['settings']) or
            !$aFile = $_FILES ['settings'] or
            $aFile ['size'] == 0 or
            $aFile ['error'] != 0 or
            !$sFileData = @file_get_contents($aFile ['tmp_name']) or
            ($aData = @unserialize($sFileData)) === false or
            ($sThisPluginNameOnly and $aData ['pluginname'] != $sThisPluginNameOnly)
        ) {
            $this->Message_AddErrorSingle($this->Lang_Get('plugin.configengine.Wrong_File'), $this->Lang_Get('error'), true);
        } else {
            // lets save settings
            $this->PluginConfigengine_Config_SaveConfig($aData ['pluginname'], $aData ['autoload'], $aData ['serialized']);
            $this->Message_AddNoticeSingle(
                $this->Lang_Get(
                    'plugin.configengine.Import_Was_Successful',
                    array('pluginname' => $aData ['pluginname'])
                ),
                'Ok',
                true
            );
        }
        $sPath = ($bReturnToReferer and isset ($_SERVER ['HTTP_REFERER'])) ? $_SERVER ['HTTP_REFERER'] : Router::GetPath('configengine');
        return Router::Location($sPath);
    }

    // ---

    public function DeletePluginSettingsEvent()
    {
        $this->Viewer_SetResponseAjax('json');
        if (!$sPluginName = (string)getRequest('pluginname')) return false;
        $this->PluginConfigengine_Config_DeletePluginSettings($sPluginName);
    }

    // --- ajax ---

    protected function RegisterEvent()
    {
        // page
        $this->AddEvent('index', 'IndexEvent');
        $this->AddEvent('export', 'ExportEvent');
        $this->AddEvent('import', 'ImportEvent');

        // ajax
        $this->AddEvent('deleteplugin', 'DeletePluginSettingsEvent');
    }

}
