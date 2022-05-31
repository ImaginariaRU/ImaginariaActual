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

class PluginIgnore_HookIgnore extends Hook {
    protected $bIgnorePostMyTopic = false;
    
	public function RegisterHook() {
        if (class_exists('MobileDetect') && MobileDetect::IsMobileTemplate()) {
            return;
        }
        if ($oUserCurrent=$this->User_GetUserCurrent() and $oUserCurrent->isAdministrator()) {
            $this->AddHook('template_menu_settings_settings_item', 'MenuSettingsTpl', __CLASS__);
        }
		$this->AddHook('template_comment_tree_begin','CommentTreeBegin');
		$this->AddHook('template_profile_top_begin','ProfileTop');
		$this->AddHook('topic_show','IgnorePostMyTopic');
		$this->AddHook('template_profile_whois_item_end','Profile');
		$this->AddHook('template_comment_action','HideComments');
		$this->AddHook('template_comment_action','IgnoreReplyComments');
		$this->AddHook('template_comment_action','IgnoreComments');
		$this->AddHook('template_html_head_end','TplIgnorePostMyTopic');
		$this->AddHook('template_html_head_end','TplIgnorePostMyWall');
	}
    
    public function MenuSettingsTpl() {
    	return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'menu.setting.ignore.tpl');
    }

    public function CommentTreeBegin($aData) {
    	if (!in_array($aData['sTargetType'], array('topic','link','question','photoset')))
            return;
        if (!Config::Get('plugin.ignore.ignore_target_reply_my_comment') and !Config::Get('plugin.ignore.ignore_target_post_comment_my_topic'))
            return;
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent)
            return;
        if (!($oTopic = $this->Topic_GetTopicById($aData['iTargetId'])))
            return;
        $this->Viewer_Assign("iTargetId",$aData['iTargetId']);
        $this->Viewer_Assign("sTargetType",$aData['sTargetType']);
        if ($oUserCurrent->getId() == $oTopic->getUserId() or $oUserCurrent->isAdministrator())
            $this->Viewer_Assign("sTargetAdmin",1);
        else
            $this->Viewer_Assign("sTargetAdmin",0);
        $aIgnoreUser = array();
		$aResult=$this->PluginIgnore_Ignore_GetTargetItemsByUserIdAndTargetIdAndTargetType($oUserCurrent->getId(), $aData['iTargetId'], $aData['sTargetType']);
		foreach ($aResult as $oIgnore) {
            $aIgnoreUser[$oIgnore->getTargetUserId()] = $oIgnore->getTargetUserId();
		}
        $aIgnoreUser = $this->User_GetUsersByArrayId($aIgnoreUser);
		$this->Viewer_Assign('aIgnoreUser',$aIgnoreUser);
		$this->Viewer_Assign('iCount',count($aIgnoreUser));
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_target.tpl');
    }

	public function ProfileTop($aData) {
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent)
            return;
        $oUser = $aData['oUserProfile'];
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($oUserCurrent->getId(), $oUser->getId());
        $this->Viewer_Assign("oIgnore",$oIgnore);
        $this->Viewer_Assign("bIgnore", $oIgnore ? false : true);
		return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'profile_top.tpl');
	}
    
	public function Profile($aData) {
        $oUser = $aData['oUserProfile'];
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent)
            return;
        $aIgnoreTo = $this->PluginIgnore_Ignore_GetUserIgnored($oUser->getId(), false);
		if ($oUserCurrent->getId() != $oUser->getId() and $aIgnoreTo) {
            foreach ($aIgnoreTo as $k => $v)
                if (!$aIgnoreTo[$k]->getProfileShow())
                    unset($aIgnoreTo[$k]);
		}
		if ($oUserCurrent->getId() == $oUser->getId()) {
            $aIgnoreFrom = $this->PluginIgnore_Ignore_GetTargetIgnored($oUser->getId(), false);
            foreach ($aIgnoreFrom as $k => $v)
                if (!$aIgnoreFrom[$k]->getProfileShow())
                    unset($aIgnoreFrom[$k]);
		}
        else
            $aIgnoreFrom = array();
        $this->Viewer_Assign("bProfileIgnore",($oUserCurrent->getId() == $oUser->getId()) ? true : false);
        $this->Viewer_Assign("aProfileIgnoreTo",$aIgnoreTo);
        $this->Viewer_Assign("iCountProfileIgnoreTo",count($aIgnoreTo));
        $this->Viewer_Assign("aProfileIgnoreFrom",$aIgnoreFrom);
        $this->Viewer_Assign("iCountProfileIgnoreFrom",count($aIgnoreFrom));
		return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'profile_whois.tpl');
	}
    
	public function HideComments($aData) {
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent)
            return;
        $oComment = $aData['comment'];
        if ($oComment->getDelete())
            return;
        $oUser = $oComment->getUser();
        $this->Viewer_Assign("oComment",$oComment);
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($oUserCurrent->getId(), $oUser->getId(), 'user');
        if ($oIgnore and $oIgnore->getIsHideMeComments()) {
            if ($oIgnore->getCommentRating() > 0 and $oIgnore->getCommentRating() < $oComment->getRating())
                return;
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'hide_comments.tpl');
        }
	}
    
	public function IgnoreReplyComments($aData) {
        $oComment = $aData['comment'];
        $oUser = $oComment->getUser();
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent or $oUserCurrent->getId() == $oUser->getId())
            return;
        $this->Viewer_Assign("oComment",$oComment);
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($oUser->getId(), $oUserCurrent->getId(), 'target');
        if ($oIgnore and $oIgnore->getIsIgnoreRyplyMyComment())
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_reply_comments.tpl');
        $oIgnore = $this->PluginIgnore_Ignore_GetIgnoreTarget($oUser->getId(), $oUserCurrent->getId(), $oComment->getTargetId(), $oComment->getTargetType(), 'target');
        if ($oIgnore and $oIgnore->getIsIgnoreTargetRyplyMyComment())
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_reply_comments.tpl');
	}
    
	public function IgnoreComments($aData) {
        $oComment = $aData['comment'];
        $oUser = $oComment->getUser();
        $this->Viewer_Assign("oComment",$oComment);
        $this->Viewer_Assign("oUser",$oUser);
        return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_comments.tpl');
	}
    
    public function IgnorePostMyTopic($aData) {
        $oTopic = $aData['oTopic'];
        $oUser = $oTopic->getUser();
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent or $oUserCurrent->getId() == $oUser->getId())
            return;
        $this->Viewer_Assign("oTopic",$oTopic);
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($oUser->getId(), $oUserCurrent->getId());
        if ($oIgnore and $oIgnore->getIsIgnorePostMyTopic()) {
            $this->bIgnorePostMyTopic = true;
            return;
        }
        $oIgnore=$this->PluginIgnore_Ignore_GetTargetByUserIdAndTargetUserIdAndTargetIdAndTargetType($oUser->getId(), $oUserCurrent->getId(), $oTopic->getId(), $oTopic->getType());
        if ($oIgnore and $oIgnore->getIsIgnoreTargetPostMyTopic()) {
            $this->bIgnorePostMyTopic = true;
            return;
        }
	}
    
    public function TplIgnorePostMyTopic() {
        if ($this->bIgnorePostMyTopic)
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_post_my_topic.tpl');
	}
    
    public function TplIgnorePostMyWall() {
		$oUserCurrent=$this->User_GetUserCurrent();
		if (!$oUserCurrent or Router::GetAction() != 'profile')
            return;
		if (!($oUserProfile=$this->User_GetUserByLogin(Router::GetActionEvent())))
			return;
		if ($oUserCurrent->getId() == $oUserProfile->getId())
            return;
        $oIgnore = $this->PluginIgnore_Ignore_GetUserIgnoresByTargetId($oUserProfile->getId(), $oUserCurrent->getId());
        if ($oIgnore and $oIgnore->getIsIgnorePostMyWall())
            return $this->Viewer_Fetch(Plugin::GetTemplatePath('ignore').'ignore_post_my_wall.tpl');
	}
    
}



















