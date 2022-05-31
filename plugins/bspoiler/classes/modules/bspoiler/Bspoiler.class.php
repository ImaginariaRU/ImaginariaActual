<?php
/*-------------------------------------------------------
*
*	Plugin Spoiler
*	Shpinev Konstantin
*	Contact e-mail: thedoublekey@gmail.com
*
*---------------------------------------------------------
*
*	Spoiler :: Plugin
*	Modified by fedorov mich © 2014
*	[ LS :: 1.0.3 | Habra Style ]
*
*/

class PluginBspoiler_ModuleBspoiler extends PluginBspoiler_Inherit_ModuleText
{
    /*
     * /engine/modules/text/Text.class.php upgraded from Tabun codebase
    const ACT_CREATE = 1;
    const ACT_FIX    = 2;
    const ACT_UPDATE = 3;
    */

    public function Parser($sText, $actionType = -1)
    {
        $sResult = parent::Parser($sText);
        $sResult = $this->SpoilerParser($sResult);
        return $sResult;
    }

    private function SpoilerParser($sText)
    {
        $aMatches = array();
        while (preg_match('/<spoiler title="(.+?)">/', $sText, $aMatches) !== false && count($aMatches) > 1) {
            $sTitle = $aMatches[1];
            $sText = str_replace("<spoiler title=\"$sTitle\">",
                '<div><b class="spoiler-title">' . $sTitle . '</b><div class="spoiler-body">',
                $sText);
            $sText = str_replace("</spoiler>", '</div></div>', $sText);
        }
        return $sText;
    }

    protected function JevixConfig()
    {
        parent::JevixConfig();

        $aTags = array_keys($this->oJevix->tagsRules);
        $aTags[] = 'spoiler';
        $this->oJevix->cfgAllowTags($aTags);
        $this->oJevix->cfgAllowTagParams('spoiler', array('title'));
    }
}
