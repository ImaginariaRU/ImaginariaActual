<?php
/*-------------------------------------------------------
*
*   kEditComment.
*   Copyright © 2012 Alexei Lukin
*
*--------------------------------------------------------
*
*   Official site: http://kerbystudio.ru
*   Contact e-mail: kerby@kerbystudio.ru
*
---------------------------------------------------------
*/

class PluginEditcomment_ModuleEditcomment_MapperEditcomment extends Mapper
{
    public function HasAnswers($sId)
    {
        $table = Config::Get('db.table.comment');

        $sql = "
        SELECT
            comment_id
        FROM
            {$table}
        WHERE
            comment_pid=?d	AND comment_delete=0 AND comment_publish=1
        LIMIT 0,1 ;
        ";

        if ($this->oDb->selectRow($sql, $sId)) {
            return true;
        }
        return false;
    }
}
