<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ICQ: 172440790 | E-mail: sersar@ukr.net
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/


class PluginIgnore_ActionSettings extends PluginIgnore_Inherit_ActionSettings {

	public function Init() {
        parent::Init();
		//$this->Viewer_AddMenu('ignore',$this->getTemplatePathPlugin().'menu.ignore.tpl');
	}

	protected function RegisterEvent() {
		parent::RegisterEvent();
		$this->AddEventPreg('/^ignore/i','EventIgnore');
	}

	/**********************************************************************************
	 ************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	 **********************************************************************************
	 */

	protected function EventIgnore() {
		$this->sMenuItemSelect='ignore';
		$this->sMenuSubItemSelect='ignore';
		$oUserCurrent=$this->User_GetUserCurrent();
        $aIgnore = $this->PluginIgnore_Ignore_GetUserIgnored($oUserCurrent->getId(), false);
        $aUsersList = array();
        foreach ($aIgnore as $oIgnore)
            $aUsersList[] = $oIgnore->getTarget();
        $this->Viewer_Assign("aUsersList",$aUsersList);
		$this->Viewer_AddHtmlTitle($this->Lang_Get('plugin.ignore.ignore'));
		$this->SetTemplateAction('ignore');
	}
    
	public function EventShutdown() {
		parent::EventShutdown();
	}
}






