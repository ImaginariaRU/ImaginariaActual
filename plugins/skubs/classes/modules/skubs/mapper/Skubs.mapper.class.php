<?php

class PluginSkubs_ModuleSkubs_MapperSkubs extends Mapper
{
    public function GetSkubStat($sUserId, $iLimit)
    {
        $table_topic = Config::Get('db.table.topic');
        $table_blog = Config::Get('db.table.blog');

        $sql = "SELECT 
					b.*,
					count(*) as blog_skubs
				FROM 
					{$table_topic} as t,
					{$table_blog} as b	
				WHERE 	
					t.user_id = ?d
					AND
					t.blog_id = b.blog_id
					AND				
					b.blog_type<>'personal'							
				GROUP BY t.blog_id
				ORDER by blog_skubs desc
				LIMIT 0, ?d 
				;	
					";
        $aReturn = array();
        if ($aRows = $this->oDb->select($sql, $sUserId, $iLimit)) {
            foreach ($aRows as $aRow) {
                $aReturn[] = Engine::GetEntity('Blog', $aRow);
            }
        }
        return $aReturn;
    }
}

