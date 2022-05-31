<?php

class PluginSHistory_ModuleSessions_MapperSessions extends Mapper
{

    function isCurrent($user_id, $session_key)
    {
        $table = Config::Get('db.table.shistory');

        $sql = "SELECT 
                    `id` 
                FROM 
                    {$table}
                WHERE 
				    `user_id` = ? 
				AND 
				    `session_key` = ?
			";

        if ($aRow = $this->oDb->selectRow($sql, $user_id, $session_key)) {
            return true;
        }

        return false;
    }

    function addCurrent($user_id, $session_key, $date, $time, $os, $browser, $ip)
    {
        $table_shistory = Config::Get('db.table.shistory');

/*
        $sql = "SELECT COUNT( * ) as cnt FROM {$table} WHERE `user_id` = ?";

        if ($sCnt = $this->oDb->selectRow($sql, $user_id)) {
            if ($sCnt['cnt'] >= Config::Get('shistoryLimit')) {
                $this->oDb->query("DELETE FROM {$table} WHERE `user_id` = ? ORDER BY id LIMIT 1", $user_id);
            }
        }
*/
        // применена оптимизация https://stackoverflow.com/a/578884/5127037

        $sql = "
        DELETE 
            i1.*
        FROM    
            {$table_shistory} i1
        LEFT JOIN
        (
            SELECT  
                id
            FROM    
                {$table_shistory} ii
            ORDER BY 
                id DESC
            LIMIT ?d
        ) i2
        ON      
            i1.id = i2.id
        WHERE   
            i2.id IS NULL
        AND `user_id` = ?d
         
        ";
        $this->oDb->query($sql, Config::Get('shistoryLimit'), $user_id);

        $sql = "
			INSERT INTO `{$table_shistory}`
				(
					`user_id`,
					`enter_date`,
					`enter_time`,
					`session_key`,
					`user_ip`,
					`user_os`,
					`user_agent`
				)
					VALUES 
				(
					?,?,?,?,?,?,?
				)
			";
        return $this->oDb->query($sql, $user_id, $date, $time, $session_key, $ip, $os, $browser);

    }

    function getHistoryRows($user_id)
    {
        $table_shistory = Config::Get('db.table.shistory');
        $history_limit = Config::Get('shistoryLimit');

        $sql = "SELECT 
					`enter_date`,
					`enter_time`,
					`user_ip`,
					`user_os`,
					`user_agent`
				FROM {$table_shistory}
				WHERE
					`user_id` = ? 
				ORDER BY id DESC
				LIMIT {$history_limit}
			";

        $aReturn = array();
        if ($aRows = $this->oDb->select($sql, $user_id)) {
            foreach ($aRows as $aRow)
                $aReturn[] = $aRow;
        }
        return $aReturn;

    }
}

