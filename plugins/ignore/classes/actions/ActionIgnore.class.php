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

class PluginIgnore_ActionIgnore extends ActionPlugin {
	protected $oUserCurrent=null;
	protected $sMenuHeadItemSelect='ignore';
	protected $sMenuItemSelect='ignore';
    protected $sMenuEvent = '';

	public function Init() {
		$this->oUserCurrent=$this->User_GetUserCurrent();
	}

	protected function RegisterEvent() {
	}

	/**********************************************************************************
	************************ РЕАЛИЗАЦИЯ ЭКШЕНА ***************************************
	**********************************************************************************
	*/

    
	public function EventShutdown() {
		$this->Viewer_Assign('sMenuHeadItemSelect',$this->sMenuHeadItemSelect);
		$this->Viewer_Assign('sMenuItemSelect',$this->sMenuItemSelect);
		$this->Viewer_Assign('sMenuEvent',$this->sMenuEvent);
	}
}














