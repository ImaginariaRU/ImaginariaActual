<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginUrlredirect_ActionUrlredirect extends ActionPlugin
{
    public function Init()
    {
    }

    // ---

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/(.*)+/iu', 'ShowMessageToUser');
    }

    // ---

    protected function ShowMessageToUser()
    {
        $sURLToGo = $this->GetCleanURL();

        // check for referer (so black SEO links from pingators will not work now)
        if (!$this->ReferedFromThisSite()) {
            $sURLToGo = Config::Get('path.root.web');
            $this->Viewer_Assign('bNotCorrectReferer', true);
        }

        $this->Viewer_Assign('URL', $sURLToGo);
        $this->SetTemplateAction('redirectpage');
    }

    // ---

    protected function GetCleanURL()
    {
        $sURL = stristr($_SERVER ['REQUEST_URI'], 'http');
        if ((empty ($sURL)) and (Config::Get('plugin.urlredirect.Wrap_Links_In_Base64'))) {
            $sBase64Hash = Config::Get('path.root.web') . $_SERVER ['REQUEST_URI'];
            $sBase64Hash = str_replace(Router::GetPath(Config::Get('plugin.urlredirect.URL_For_Good_Sites')), '', $sBase64Hash);
            $sBase64Hash = str_replace(Router::GetPath(Config::Get('plugin.urlredirect.URL_For_Bad_Sites')), '', $sBase64Hash);
            $sURL = base64_decode($sBase64Hash);
        }
        return $sURL;
    }

    // ---

    protected function ReferedFromThisSite()
    {
        // if protection is not enabled - always satisfy the condition
        if (!Config::Get('plugin.urlredirect.Check_For_Referer')) {
            return true;
        }
        if (isset ($_SERVER ['HTTP_REFERER'])) {
            $sFullReferer = $_SERVER ['HTTP_REFERER'];
            $sFullReferer = explode('/', $sFullReferer);
            $sFullReferer = $sFullReferer [0] . '//' . $sFullReferer [2];
            if ($sFullReferer == Config::Get('path.root.web')) {
                return true;
            }
        }
        return false;
    }

    // ---

    protected function EventNotFound()
    {
        $this->Message_AddErrorSingle($this->Lang_Get('system_error_404'), 'Redirect error');
        return Router::Action('error');
    }

}
