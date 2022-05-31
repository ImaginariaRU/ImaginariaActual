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

class PluginIgnore_ModuleComment extends PluginIgnore_Inherit_ModuleComment {
    protected $oMapper;

    public function Init() {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

	public function GetCommentsOnline($sTargetType,$iLimit) {
		$args =  func_get_args();
		if (!call_user_func_array(array('parent',__FUNCTION__),$args)) {
			return false;
		}
        $sTargetType = $args[0];
        $iLimit      = (int)$args[1];
        //
        if (in_array($sTargetType, array('topic','link','question','photoset')) and $this->oUserCurrent and ($aUserId = $this->PluginIgnore_Ignore_GetCommentIgnoreByUserId($this->oUserCurrent->getId()))) {
    		$aCloseBlogs = ($this->oUserCurrent)
    			? $this->Blog_GetInaccessibleBlogsByUser($this->oUserCurrent)
    			: $this->Blog_GetInaccessibleBlogsByUser();
    
    		$s=serialize($aCloseBlogs);
    
    		if (false === ($data = $this->Cache_Get("comment_online_ignore_{$this->oUserCurrent->getId()}_{$sTargetType}_{$s}_{$iLimit}"))) {
    			$data = $this->oMapper->GetCommentsOnlineIgnore($sTargetType,$aCloseBlogs,$iLimit,$aUserId);
    			$this->Cache_Set($data, "comment_online_ignore_{$this->oUserCurrent->getId()}_{$sTargetType}_{$s}_{$iLimit}", array("comment_online_update_{$sTargetType}"), 60*60*24*1);
    		}
    		$data=$this->GetCommentsAdditionalData($data);
        } else {
            $data = parent::GetCommentsOnline($sTargetType,$iLimit);
        }
		return $data;
	}
}















