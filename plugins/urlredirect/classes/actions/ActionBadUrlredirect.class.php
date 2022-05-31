<?php
/*
  Urlredirect plugin
  (P) PSNet, 2008 - 2012
  http://psnet.lookformp3.net/
  http://livestreet.ru/profile/PSNet/
  http://livestreetcms.com/profile/PSNet/
*/

class PluginUrlredirect_ActionBadUrlredirect extends PluginUrlredirect_ActionUrlredirect
{
    public function Init()
    {
    }

    // ---

    protected function RegisterEvent()
    {
        $this->AddEventPreg('/(.*)+/iu', 'ShowMessageToUserDontGo');
    }

    // ---

    protected function ShowMessageToUserDontGo()
    {
        $sURLDontGo = $this->GetCleanURL();

        // check for referer (so black SEO links from pingators will not work now)
        if (!$this->ReferedFromThisSite()) {
            $sURLDontGo = Config::Get('path.root.web');
            $this->Viewer_Assign('bNotCorrectReferer', true);
        }

        $this->Viewer_Assign('BadURL', $sURLDontGo);
        $this->SetTemplateAction('dontgopage');
    }

    // ---

    protected function EventNotFound()
    {
        $this->Message_AddErrorSingle($this->Lang_Get('system_error_404'), 'Redirect error #2');
        return Router::Action('error');
    }

}
