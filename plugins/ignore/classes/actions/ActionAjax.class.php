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

class PluginIgnore_ActionAjax extends PluginIgnore_Inherit_ActionAjax
{
    protected function RegisterEvent(){
        parent::RegisterEvent();
		$this->AddEventPreg('/^ignore$/i','/^target$/','/^window$/','EventIgnoreTargetWindow');
		$this->AddEventPreg('/^ignore$/i','/^target$/','EventIgnoreTarget');
		$this->AddEventPreg('/^ignore$/i','/^setting$/','EventIgnoreSetting');
		$this->AddEventPreg('/^ignore$/i','/^window$/','EventIgnoreWindow');
    }

	protected function EventIgnoreTargetWindow() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=(int)getRequestStr('idUser');
		$sTargetId=getRequestStr('iTargetId');
		$sTargetType=getRequestStr('sTargetType');
		if (!in_array($sTargetType, array('topic','link','question','photoset'))) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
        if ($sUserId) {
    		if ($this->oUserCurrent->getId()==$sUserId) {
    			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
    			return;
    		}
    		if( !$oUser=$this->User_GetUserById($sUserId) ) {
    			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
    			return;
    		}
            $oIgnore = $this->PluginIgnore_Ignore_GetTargetByUserIdAndTargetUserIdAndTargetIdAndTargetType($this->oUserCurrent->getId(), $oUser->getId(), $sTargetId, $sTargetType);
        } else {
            $oIgnore = null;
        }
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign("oIgnore",$oIgnore);
        $oViewer->Assign("bIgnore", $oIgnore ? false : true);
        $oViewer->Assign("iUserId",$sUserId);
        $oViewer->Assign("iTargetId",$sTargetId);
        $oViewer->Assign("sTargetType",$sTargetType);
        if (!($oTopic = $this->Topic_GetTopicById($sTargetId)))
            return;
        if ($this->oUserCurrent->getId() == $oTopic->getUserId() or $this->oUserCurrent->isAdministrator())
            $oViewer->Assign("sTargetAdmin",1);
        else
            $oViewer->Assign("sTargetAdmin",0);
		$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath('ignore').'ignore_target_window.tpl');
		$this->Viewer_AssignAjax('sText',$sTextResult);
    }
    
	protected function EventIgnoreTarget() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=(int)getRequestStr('idUser');
		$sLogin=getRequestStr('sLogin');
		$sData=getRequestStr('data','');
		$sReason=getRequestStr('reason','');
		$sTargetId=getRequestStr('iTargetId');
		$sTargetType=getRequestStr('sTargetType');
		if (!$sTargetId or !in_array($sTargetType, array('topic','link','question','photoset')) or (!$sUserId and empty($sData))) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
		if ($sUserId and !$oUser=$this->User_GetUserById($sUserId)) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
		if (!$sUserId and !$oUser=$this->User_GetUserByLogin($sLogin) ) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
		if ($this->oUserCurrent->getId()==$oUser->getId()) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
        $aIgnore = explode('|', $sData);
        if (empty($sData)) {
    		if ($oIgnore=$this->PluginIgnore_Ignore_GetTargetByUserIdAndTargetUserIdAndTargetIdAndTargetType($this->oUserCurrent->getId(), $oUser->getId(), $sTargetId, $sTargetType))
                $oIgnore->Delete();
            $aIgnoreUser = array();
    		$aResult=$this->PluginIgnore_Ignore_GetTargetItemsByUserIdAndTargetIdAndTargetType($this->oUserCurrent->getId(), $sTargetId, $sTargetType);
    		foreach ($aResult as $oIgnore)
                $aIgnoreUser[$oIgnore->getTargetUserId()] = $oIgnore->getTargetUserId();
            $aIgnoreUser = $this->User_GetUsersByArrayId($aIgnoreUser);
            $aTmp = array();
            foreach ($aIgnoreUser as $oTargetUser)
                $aTmp[] = "<a href=\"#\" onclick=\"ls.ignore.showIgnoreTarget(this, {$sTargetId},'{$sTargetType}', {$oTargetUser->getId()}); return false;\">{$oTargetUser->getLogin()}</a>";
            $this->Viewer_AssignAjax('sUsers',(count($aTmp)) ? implode(', ', $aTmp) : '');
    		$this->Viewer_AssignAjax('iCount',count($aTmp));
            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_delete_user_ignore'));
            return;
        }
		if ($oIgnore=$this->PluginIgnore_Ignore_GetTargetByUserIdAndTargetUserIdAndTargetIdAndTargetType($this->oUserCurrent->getId(), $oUser->getId(), $sTargetId, $sTargetType))
            $oIgnore->Delete();
    
		$oIgnore = LS::Ent('PluginIgnore_Ignore_Target');
		$oIgnore->setUserId($this->oUserCurrent->getId());
		$oIgnore->setTargetUserId($oUser->getId());
		$oIgnore->setTargetId($sTargetId);
		$oIgnore->setTargetType($sTargetType);
		$oIgnore->setTypes($sData);
		$oIgnore->setReason($sReason);
		$oIgnore->setDateAdd(date('Y-m-d H:i:s'));
		$oIgnore->Add();
        $aIgnoreUser = array();
		$aResult=$this->PluginIgnore_Ignore_GetTargetItemsByUserIdAndTargetIdAndTargetType($this->oUserCurrent->getId(), $sTargetId, $sTargetType);
		foreach ($aResult as $oIgnore)
            $aIgnoreUser[$oIgnore->getTargetUserId()] = $oIgnore->getTargetUserId();
        $aIgnoreUser = $this->User_GetUsersByArrayId($aIgnoreUser);
        $aTmp = array();
        foreach ($aIgnoreUser as $oTargetUser)
            $aTmp[] = "<a href=\"#\" onclick=\"ls.ignore.showIgnoreTarget(this, {$sTargetId},'{$sTargetType}', {$oTargetUser->getId()}); return false;\">{$oTargetUser->getLogin()}</a>";
        $this->Viewer_AssignAjax('sUsers',(count($aTmp)) ? implode(', ', $aTmp) : '');
		$this->Viewer_AssignAjax('iCount',count($aTmp));
        if ($sUserId)
            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_update_user_ignore'));
        else
            $this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_add_user_ignore'));
    }
    
	protected function EventIgnoreSetting() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=getRequestStr('idUser');
		$sData=getRequestStr('data','');
		$sReason=getRequestStr('reason','');
		$iRating=abs((int)getRequestStr('rating'));
		$iProfileShow=((int)getRequestStr('profileshow')) ? 1 : 0;
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
		if( !$oUser=$this->User_GetUserById($sUserId) ) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
        $aIgnore = explode('|', $sData);
        if (empty($sData)) {
            if ($this->PluginIgnore_Ignore_DeleteUserIgnore($this->oUserCurrent->getId(), $sUserId)) {
    			$this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_delete_user_ignore'));
                $this->PluginIgnore_Ignore_TalkIgnore($sUserId, 'delete');
                $this->PluginIgnore_Ignore_UpdateCountUserIgnore($sUserId);
            }
            return;
        }
        if ($this->PluginIgnore_Ignore_UpdateUserIgnore($this->oUserCurrent->getId(), $sUserId, $aIgnore, $sReason, $iRating, $iProfileShow)) {
			$this->Message_AddNoticeSingle($this->Lang_Get('plugin.ignore.notice_update_user_ignore'));
            $this->PluginIgnore_Ignore_UpdateCountUserIgnore($sUserId);
			return;
        } else {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
        }
    }
    
	protected function EventIgnoreWindow() {
		if (!$this->oUserCurrent) {
			$this->Message_AddErrorSingle($this->Lang_Get('need_authorization'),$this->Lang_Get('error'));
			return;
		}
		$sUserId=getRequestStr('idUser');
		if ($this->oUserCurrent->getId()==$sUserId) {
			$this->Message_AddErrorSingle($this->Lang_Get('error'),$this->Lang_Get('error'));
			return;
		}
		if( !$oUser=$this->User_GetUserById($sUserId) ) {
			$this->Message_AddErrorSingle($this->Lang_Get('user_not_found'),$this->Lang_Get('error'));
			return;
		}
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($this->oUserCurrent->getId(), $oUser->getId());
		$oViewer=$this->Viewer_GetLocalViewer();
		$oViewer->Assign("oIgnore",$oIgnore);
        $oViewer->Assign("bIgnore", $oIgnore ? false : true);
        $oViewer->Assign("oUserProfile",$oUser);
		$sTextResult=$oViewer->Fetch(Plugin::GetTemplatePath('ignore').'ignore_window.tpl');
		$this->Viewer_AssignAjax('sText',$sTextResult);
    }
}


















