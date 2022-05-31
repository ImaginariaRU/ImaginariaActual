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

class PluginIgnore_ModuleComment_MapperComment extends PluginIgnore_Inherit_ModuleComment_MapperComment {
	public function GetCommentsOnlineIgnore($sTargetType,$aExcludeTargets,$iLimit,$aUserId) {
		$sql = "SELECT 					
					c.target_id	
				FROM 
					".Config::Get('db.table.comment')." c
					LEFT JOIN
                        ".Config::Get('db.table.comment_online')." o
                        ON c.target_id = o.target_id AND c.target_type = o.target_type 
				WHERE 								
					c.target_type = ?
					AND		
					c.comment_delete = 0
					AND
					c.comment_publish = 1
                    AND
                    c.user_id NOT IN(?a)
					{ AND c.target_parent_id NOT IN(?a) }
                GROUP BY c.target_id
				ORDER by c.comment_date desc
				LIMIT 0, ?d ";
		$aTargets=array();
		if ($aRows=$this->oDb->select(
			$sql,$sTargetType,
            $aUserId,
			(count($aExcludeTargets)?$aExcludeTargets:DBSIMPLE_SKIP),
			$iLimit
		)
		) {
			foreach ($aRows as $aRow) {
				$aTargets[]=$aRow['target_id'];
			}
		}
		$aComments=array();
        if ($aTargets) {
    		$sql = "SELECT 					
    					c.comment_id, c.target_id
    				FROM 
    					".Config::Get('db.table.comment')." c
    				WHERE 										
    					c.target_type = ?
    					AND					
    					c.target_id IN(?a)
    					AND		
    					c.comment_delete = 0
    					AND
    					c.comment_publish = 1
                        AND
                        c.user_id NOT IN(?a)
    				ORDER by c.comment_date desc";
    		$aTmp=array();
    		if ($aRows=$this->oDb->select(
    			$sql,$sTargetType,$aTargets,
                $aUserId
    		)
    		) {
    			foreach ($aRows as $aRow) {
                    if (!isset($aTmp[$aRow['target_id']])) {
                        $aTmp[$aRow['target_id']] = 1;
        				$aComments[]=$aRow['comment_id'];
                    }
    			}
    		}
        }
		return $aComments;
	}
}




