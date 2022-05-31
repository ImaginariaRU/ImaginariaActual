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

class PluginIgnore_ModuleIgnore_EntityIgnore extends EntityORM
{
	protected $_aDataMore = null;

	protected $aRelations = array(
		'user'=>array(self::RELATION_TYPE_BELONGS_TO,'ModuleUser_EntityUser','user_id')
	);
    
    public function Init() {
        parent::Init();
    }

	public function getTarget() {
		return $this->User_GetUserById ( $this->getTargetId () );
	}
	public function getIsIgnorePostMePM() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_ME_PM, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_post_me_pm');
	}
	public function getIsHideMeComments() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_HIDE_ME_COMMENT, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_hide_me_comments');
	}
	public function getIsIgnoreRyplyMyComment() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_REPLY_MY_COMMENT, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_reply_my_comment');
	}
	public function getIsIgnorePostMyTopic() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_MY_TOPIC, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_post_comment_my_topic');
	}
	public function getIsIgnorePostMyWall() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_POST_MY_WALL, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_post_my_wall');
	}
	public function getIsHideMeTopics() {
        return in_array(PluginIgnore_ModuleIgnore::IGNORE_HIDE_ME_TOPIC, $this->getIgnoreTypes()) and Config::Get('plugin.ignore.ignore_hide_me_topics');
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
    
	public function getCommentRating() {
		return (int)$this->getExtract('comment_rating');
	}
	public function getProfileShow() {
		return (int)$this->getExtract('profile_show');
	}
    
	public function setCommentRating($data) {
		$this->getPack('comment_rating', $data);
	}
	public function setProfileShow($data) {
		$this->getPack('profile_show', $data);
	}
    
	public function getExtraData() {
		return is_array($this->_aDataMore) ? serialize($this->_aDataMore) : serialize(array());
	}
    
	public function getExtract($sKey) {
        if ($this->_aDataMore === null)
            $this->_aDataMore = $this->getExtra() ? unserialize(stripslashes($this->getExtra())) : array();
		if (isset($this->_aDataMore[$sKey])) {
			return $this->_aDataMore[$sKey];
		}
		return null;
	}
    
	public function getPack($sKey, $data) {
        if ($this->_aDataMore === null)
            $this->_aDataMore = $this->getExtra() ? unserialize(stripslashes($this->getExtra())) : array();
		$this->_aDataMore[$sKey]=$data;
		$this->setExtra( is_array($this->_aDataMore) ? serialize($this->_aDataMore) : serialize(array()) );
	}
}















