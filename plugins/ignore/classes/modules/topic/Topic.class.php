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

class PluginIgnore_ModuleTopic extends PluginIgnore_Inherit_ModuleTopic {
    protected $oMapper;

    public function Init()
    {
        parent::Init();
        $this->oMapper = Engine::GetMapper(__CLASS__);
    }

	public function GetTopicsLast($iCount) {
		$args =  func_get_args();
		if (!call_user_func_array(array('parent',__FUNCTION__),$args)) {
			return false;
		}
        $iCount = $args[0];
		$aFilter=array(
			'blog_type' => array(
				'personal',
				'open',
			),
			'topic_publish' => 1
		);
        if ($this->oUserCurrent and ($aUserId = $this->PluginIgnore_Ignore_GetTopicIgnoreByUserId($this->oUserCurrent->getId())))
            $aFilter['ignore_user'] = $aUserId;
		/**
		 * Если пользователь авторизирован, то добавляем в выдачу
		 * закрытые блоги в которых он состоит
		 */
		if($this->oUserCurrent) {
			$aOpenBlogs = $this->Blog_GetAccessibleBlogsByUser($this->oUserCurrent);
			if(count($aOpenBlogs)) $aFilter['blog_type']['close'] = $aOpenBlogs;
		}
		$aReturn=$this->GetTopicsByFilter($aFilter,1,$iCount);
		if (isset($aReturn['collection'])) {
			return $aReturn['collection'];
		}
		return false;
	}
}














