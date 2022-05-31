<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginUrlredirect_ModuleText extends PluginUrlredirect_Inherit_ModuleText
{

    private static function CheckThisURLOnDefinedLists($aMatches)
    {
        // build full link of foreign site (http://site.com)
        $sForeignSiteFullLink = 'http' . $aMatches [2] . '://' . $aMatches [3];

        // get current site name (site.com)
        $sForeignSiteName = parse_url($sForeignSiteFullLink, PHP_URL_HOST);

        // declare vars from config settings
        $sSiteURL = Config::Get('path.root.web');
        $sPluginURL = Config::Get('plugin.urlredirect.URL_For_Good_Sites');
        $sPluginURLForBadSites = Config::Get('plugin.urlredirect.URL_For_Bad_Sites');

        $sOpenInNewWindow = (Config::Get('plugin.urlredirect.Open_All_Foreign_Links_In_New_Window') ? 'target="_blank"' : '');

        $sSpecialClassName = Config::Get('plugin.urlredirect.Add_Special_Class_For_All_Foreign_Links');
        $sSpecialClass = ($sSpecialClassName ? ('class="' . $sSpecialClassName . '"') : '');

        //
        // --- check for direct links (white list) ---
        //
        $bDoDirectLink = false;

        $aTrustedSites = Config::Get('plugin.urlredirect.Always_Trusted_Sites');
        for ($ic = 0; $ic < count($aTrustedSites); $ic++) {
            if (preg_match($aTrustedSites [$ic], $sForeignSiteName)) {
                $bDoDirectLink = true;
            }
        }

        if ($bDoDirectLink) {
            return '<a' . $aMatches [1] . 'href="' . $sForeignSiteFullLink . '"' . $aMatches [4] . ' ' . $sOpenInNewWindow . ' ' . $sSpecialClass . '>' . $aMatches [5] . '</a>';
        }

        // build links with base64, only for black and general lists
        if (Config::Get('plugin.urlredirect.Wrap_Links_In_Base64')) {
            $sForeignSiteFullLink = base64_encode($sForeignSiteFullLink);
        }

        //
        // --- check for bad links (black list) ---
        //
        $bBadReputationSite = false;

        $aBadSites = Config::Get('plugin.urlredirect.Sites_With_Bad_Reputation');
        for ($ic = 0; $ic < count($aBadSites); $ic++) {
            if (preg_match($aBadSites [$ic], $sForeignSiteName)) {
                $bBadReputationSite = true;
            }
        }

        if ($bBadReputationSite) {
            return '<a' . $aMatches [1] . 'href="' . $sSiteURL . '/' . $sPluginURLForBadSites . '/' . $sForeignSiteFullLink . '"' . $aMatches [4] . ' ' . $sOpenInNewWindow . ' ' . $sSpecialClass . '>' . $aMatches [5] . '</a>';
        }

        //
        // --- if site is not in black or white list - do just normal direct page (general list) ---
        //
        return '<a' . $aMatches [1] . 'href="' . $sSiteURL . '/' . $sPluginURL . '/' . $sForeignSiteFullLink . '"' . $aMatches [4] . ' ' . $sOpenInNewWindow . ' ' . $sSpecialClass . '>' . $aMatches [5] . '</a>';
    }

    // ---
    /*
        /engine/modules/text/Text.class.php upgraded from Tabun codebase
        const ACT_CREATE = 1;
        const ACT_FIX    = 2;
        const ACT_UPDATE = 3;
    */
    public function Parser($sText, $actionType = -1)
    {
        $sNewResult = parent::Parser($sText);
        $sNewResult = $this->MakeCoolURLs($sNewResult);
        return $sNewResult;
    }

    // ---

    private function MakeCoolURLs($sText)
    {
        $sSiteHost = $_SERVER ['HTTP_HOST'];
        if (strtolower(substr($sSiteHost, 0, 4)) == 'www.') {
            $sSiteHost = substr($sSiteHost, 4);
        }

        return preg_replace_callback('#<a([^<]*)href=["\']http([s]?)://(?![a-z0-9.-]*' . quotemeta($sSiteHost) . '[\/]?)([^"\']*)["\']([^<]*)>(.*)</a>#ismU', 'PluginUrlredirect_ModuleText::CheckThisURLOnDefinedLists', $sText);
    }

}
