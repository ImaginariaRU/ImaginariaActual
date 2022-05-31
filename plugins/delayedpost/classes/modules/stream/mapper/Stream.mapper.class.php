<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleStream_MapperStream extends PluginDelayedpost_Inherit_ModuleStream_MapperStream
{

    //*********************************************************************************
    public function Read($aEventTypes, $aUsersList, $iCount, $iFromId)
    {
        $sDateNow = date("Y-m-d H:i:s");
        $table = Config::Get('db.table.stream_event');

        $sql = "SELECT * FROM {$table}
				WHERE
					event_type IN (?a) 
					{ AND user_id IN (?a) }
					AND publish = 1
					AND date_added <= '{$sDateNow}'
					{ AND id < ?d }	
				ORDER BY id DESC
				{ LIMIT 0,?d }";

        $aReturn = array();

        $aRows = $this->oDb->select($sql,
            $aEventTypes,
            (!is_null($aUsersList) and count($aUsersList) > 0) ? $aUsersList : DBSIMPLE_SKIP,
            !is_null($iFromId) ? $iFromId : DBSIMPLE_SKIP,
            !is_null($iCount) ? $iCount : DBSIMPLE_SKIP);

        if ($aRows) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('Stream_Event', $aRow);
            }
        }
        return $aReturn;
    }


}