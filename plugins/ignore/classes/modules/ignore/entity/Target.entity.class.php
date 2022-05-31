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

class PluginIgnore_ModuleIgnore_EntityTarget extends EntityORM
{
	protected $aExtra=null;
    
	protected $aRelations = array(
		'user'=>array(self::RELATION_TYPE_BELONGS_TO,'ModuleUser_EntityUser','user_id')
	);
    
    public function Init() {
        parent::Init();
    }

	public function getTargetUser() {
		return $this->User_GetUserById ( $this->getTargetUserId () );
	}
	public function getIsIgnoreTargetRyplyMyComment() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_TARGET_REPLY_MY_COMMENT, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_target_reply_my_comment');
	}
	public function getIsIgnoreTargetPostMyTopic() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_TARGET_POST_MY_TOPIC, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_target_post_comment_my_topic');
	}
	public function getIgnoreTypes() {
        $sTypes = $this->getTypes();
		return empty($sTypes) ? array() : explode('|', $sTypes);
	}
    
    public function setReason($data) {
        $data = trim(htmlentities(strip_tags($data), ENT_QUOTES, 'UTF-8'));
        $data = func_check($data,'text',0,240) ? $data : substr($data,0,240);
        $this->_aData['ignore_reason']=$data;
    }
    
	protected function setExtraValue($sName,$data) {
		$this->aExtra[$sName]=$data;
		$this->setExtra($this->aExtra);
	}
	protected function getExtraValue($sName) {
		if (isset($this->aExtra[$sName])) {
			return $this->aExtra[$sName];
		}
		return null;
	}
}















