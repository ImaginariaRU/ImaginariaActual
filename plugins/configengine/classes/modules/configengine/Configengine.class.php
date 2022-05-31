<?php
/*
	Configengine plugin
	(P) PSNet, 2008 - 2013
	http://psnet.lookformp3.net/
	http://livestreet.ru/profile/PSNet/
	https://catalog.livestreetcms.com/profile/PSNet/
	http://livestreetguide.com/developer/PSNet/
*/

class PluginConfigengine_ModuleConfigengine extends Module
{

    public function Init()
    {
    }

    // ---

    // Little wrapper for Cache module, version 2.1
    // Can call user function through cache (check if already cache exists or call function and save result)
    //
    // Usage example:
    // GetUsingCache ('ClassName::FunctionName', 'tag1, tag2', 60 * 60 * 1, 'SELECT * FROM table LIMIT 1', $aParams2);
    // or
    // GetUsingCache (array ($this -> oMapper, 'FunctionName'), 'tag1, tag2', 60 * 60 * 1, 'SELECT * FROM table', $aParam2, $aParam3);


    public function GetUsingCache(
        $FunctionToCall = null,
        $aTags = array(),
        $iTime = 3600                         // 60 * 60 * 1 == 1 hour
    )
    {
        $Params = array_slice(func_get_args(), 3);

        $QueryHash = '';
        foreach ($Params as $Item) {
            $QueryHash .= serialize($Item);
        }

        if ((is_array($FunctionToCall)) and (isset ($FunctionToCall [1])) and (is_string($FunctionToCall [1]))) {
            $FuncName = $FunctionToCall [1];
        } else {
            $FuncName = (string)$FunctionToCall;
        }
        $Tags = (array)$aTags;
        if (!is_array($aTags)) {
            $Tags = str_replace(' ', '', $aTags);
            $Tags = explode(',', $Tags);
        }
        $Time = (int)$iTime;

        $Cache_Key = $FuncName . '_' . $QueryHash;
        if (false === ($Data = $this->Cache_Get($Cache_Key))) {
            $Data = call_user_func_array($FunctionToCall, $Params);
            if ($Data !== false) {
                $this->Cache_Set($Data, $Cache_Key, $Tags, $Time);
            }
        }
        return $Data;
    }

}
