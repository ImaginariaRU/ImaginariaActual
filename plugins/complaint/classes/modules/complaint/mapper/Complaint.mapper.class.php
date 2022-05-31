<?php

/*-------------------------------------------------------
*
*   CMS LiveStreet (1.x)
*   Plugin Complaint v1.0.0
*   Сделано руками @ Сергей Сарафанов (sersar)
*   ВК: vk.com/sersar | E-mail: sersar@ukr.net
*
---------------------------------------------------------
*/

class PluginComplaint_ModuleComplaint_MapperComplaint extends Mapper
{
    /**
     * Добавляем жалобу
     *
     * @param PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint
     * @return bool
     */
    public function AddComplaint(PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint)
    {
        $oComplaint->setDateAdd(date('Y-m-d H:i:s'));

        $sql = "INSERT INTO " . Config::Get('db.table.complaint.complaint') . "
			(
			user_id,
            topic_id,
            complaint_text,
			complaint_date_add
			)
			VALUES(?d, ?d, ?, ?)
		";
        if ($iId = $this->oDb->query($sql, $oComplaint->getUserId(), $oComplaint->getTopicId(), $oComplaint->getText(), $oComplaint->getDateAdd())) {
            return $iId;
        }
        return false;
    }

    /**
     * Обновляем жалобу
     *
     * @param PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint
     * @return bool
     */
    public function UpdateComplaint(PluginComplaint_ModuleComplaint_EntityComplaint $oComplaint)
    {
        $sql = "UPDATE " . Config::Get('db.table.complaint.complaint') . "
			SET complaint_status = ?d
			WHERE complaint_id = ?d
		";
        if ($this->oDb->query($sql, $oComplaint->getStatus(), $oComplaint->getId())) {
            return true;
        }
        return false;
    }

    /**
     * Удаляем жалобу
     *
     * @param integer $iComplaintId
     * @return bool
     */
    public function DeleteComplaint($iComplaintId)
    {
        $sql = "DELETE FROM
                    " . Config::Get('db.table.complaint.complaint') . "
                WHERE
                    complaint_id = ?d
    		";
        if ($this->oDb->query($sql, $iComplaintId)) {
            return true;
        }
        return false;
    }

    /**
     * Поиск по-фильтру жалобы
     * @param array $aFilter
     * @param integer $iCount
     * @param integer $iCurrPage
     * @param integer $iPerPage
     * @return array
     */
    public function GetComplaintsByFilter($aFilter, &$iCount, $iCurrPage, $iPerPage)
    {
        if (!isset($aFilter['order'])) {
            $aFilter['order'] = 'a.complaint_date_add desc';
        }
        if (!is_array($aFilter['order'])) {
            $aFilter['order'] = array($aFilter['order']);
        }
        $sql = "SELECT
                    a.*
                FROM
                    " . Config::Get('db.table.complaint.complaint') . " as a
                WHERE
                    1=1
					{ AND user_id = ?d }
					{ AND topic_id = ?d }
                ORDER BY " .
            implode(', ', $aFilter['order']) . "
                LIMIT
                    ?d, ?d";
        $aComplaints = array();
        if ($aRows = $this->oDb->selectPage($iCount, $sql,
            isset($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP,
            isset($aFilter['topic_id']) ? $aFilter['topic_id'] : DBSIMPLE_SKIP,
            ($iCurrPage - 1) * $iPerPage, $iPerPage)) {
            foreach ($aRows as $aRow) {
                $aComplaints[$aRow['complaint_id']] = $aRow;
            }
        }
        return $aComplaints;
    }

    /**
     * Поиск по-фильтру всех жалоб
     * @param array $aFilter
     * @return array
     */
    public function GetAllComplaintsByFilter($aFilter)
    {
        if (!isset($aFilter['order'])) {
            $aFilter['order'] = 'a.complaint_date_add desc';
        }
        if (!is_array($aFilter['order'])) {
            $aFilter['order'] = array($aFilter['order']);
        }
        $sql = "SELECT
                    a.*
                FROM
                    " . Config::Get('db.table.complaint.complaint') . " as a
                WHERE
                    1=1
					{ AND user_id = ?d }
					{ AND topic_id = ?d }
                ORDER BY " .
            implode(', ', $aFilter['order']) . "
                ";
        $aComplaints = array();
        if ($aRows = $this->oDb->select($sql,
            isset($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP,
            isset($aFilter['topic_id']) ? $aFilter['topic_id'] : DBSIMPLE_SKIP
        )) {
            foreach ($aRows as $aRow) {
                $aComplaints[$aRow['complaint_id']] = $aRow;
            }
        }
        return $aComplaints;
    }

    /**
     * Количество жалоб по-фильтру
     * @param array $aFilter
     * @return integer
     */
    public function GetCountComplaintsByFilter($aFilter)
    {
        $sql = "SELECT
                    count(a.complaint_id) as count
                FROM
                    " . Config::Get('db.table.complaint.complaint') . " as a
                WHERE
                        1=1
					{ AND user_id = ?d }
					{ AND topic_id = ?d }
					";
        if ($aRow = $this->oDb->selectRow($sql,
            isset($aFilter['user_id']) ? $aFilter['user_id'] : DBSIMPLE_SKIP,
            isset($aFilter['topic_id']) ? $aFilter['topic_id'] : DBSIMPLE_SKIP
        )) {
            return $aRow['count'];
        }
        return false;
    }
}