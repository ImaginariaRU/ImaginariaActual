<?php
/**
 * Blacklist - проверка E-Mail пользователей на наличие в базах спамеров.
 *
 * Версия:    1.2
 * Автор:    Karel Wintersky
 *
 **/

class PluginBlacklist_ModuleBlacklist_MapperBlacklist extends Mapper
{

    /**
     * @param $sType
     * @param $sContent
     * @param $bResult
     * @param $iService
     * @return bool
     */
    public function AddResult($sType, $sContent, $bResult, $iService)
    {
        $sDate = date("Y-m-d H:i:s");
        $iResult = ($bResult ? 1 : 0);

        $table = Config::Get('db.table.blacklist');

        $sql = "INSERT INTO {$table}
                    (content, type, service, date, result)
                VALUES
                    (?, ?, ?d, ?, ?d)
                ON DUPLICATE KEY UPDATE
                    date = ?,
                    result = ?d
                ";
        if ($this->oDb->query($sql, $sContent, $sType, $iService, $sDate, $iResult, $sDate, $iResult)) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param $aWhere
     * @return bool
     */
    public function Check($aWhere)
    {
        $sWhere = '';
        if (is_array($aWhere)) {
            foreach ($aWhere as $sType => $sContent) {
                if (!empty($sWhere)) {
                    $sWhere .= " OR ";
                }
                $sWhere .= "(content = '{$sContent}' AND type = '{$sType}')";
            }
        }
        if (!empty($sWhere)) {
            $sDate = date("Y-m-d H:00:00", time() - Config::Get('plugin.blacklist.recheck_time'));

            $table = Config::Get('db.table.blacklist');

            $sql = "SELECT * FROM {$table} WHERE (" . $sWhere . ") AND date >=  '" . $sDate . "'";
            if ($aRow = $this->oDb->select($sql)) {
                return $aRow;
            }
        }
        return false;
    }
}