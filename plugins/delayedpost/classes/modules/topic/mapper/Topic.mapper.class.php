<?php
/*
  Delayed post plugin
  (P) Rafrica.net Studio, 2010 - 2012
  http://we.rafrica.net/
*/

class PluginDelayedpost_ModuleTopic_MapperTopic extends PluginDelayedpost_Inherit_ModuleTopic_MapperTopic
{

    /**
     * @param $sTag
     * @param $aExcludeBlog
     * @param $iCount
     * @param $iCurrPage
     * @param $iPerPage
     * @return array
     */
    public function GetTopicsByTag($sTag, $aExcludeBlog, &$iCount, $iCurrPage, $iPerPage)
    {
        $sDateNow = date("Y-m-d H:i:s");
        $table_topic_tag = Config::Get('db.table.topic_tag');
        $table_topic = Config::Get('db.table.topic');

        $sql = "				
							SELECT 		
								topic_id										
							FROM 
								{$table_topic_tag}								
							WHERE 
								topic_tag_text = ? 	
								{ AND blog_id NOT IN (?a) }
								AND topic_id IN (
								    SELECT topic_id FROM {$table_topic} 
                                    WHERE {$table_topic}.topic_date_add <= '{$sDateNow}')
                            ORDER BY topic_id DESC	
                            LIMIT ?d, ?d ";

        $aTopics = array();

        $aRows = $this->oDb->selectPage(
            $iCount, $sql, $sTag,
            (is_array($aExcludeBlog) && count($aExcludeBlog)) ? $aExcludeBlog : DBSIMPLE_SKIP,
            ($iCurrPage - 1) * $iPerPage, $iPerPage
        );

        if ($aRows) {
            foreach ($aRows as $aTopic) {
                $aTopics[] = $aTopic['topic_id'];
            }
        }
        return $aTopics;
    }

    /**
     * @return mixed
     */
    public function GetNextDelayedTopicDate()
    {
        $table = Config::Get('db.table.topic');

        $sDateNow = date("Y-m-d H:i:s");

        $sQuery = "SELECT topic_date_add FROM {$table}
						WHERE topic_date_add > '{$sDateNow}'
						ORDER BY topic_date_add ASC
						LIMIT 1	";

        return $this->oDb->SelectRow($sQuery);
    }

    /**
     * @param $aFilter
     * @return string
     */

    protected function buildFilter($aFilter)
    {
        $sDateNow = date("Y-m-d H:i:s"); //fcku php.ini

        if (isset($aFilter['delayed']) && ($aFilter['delayed'])) {
            $sWhere = parent::buildFilter($aFilter) . " AND t.topic_date_add > '" . $sDateNow . "'";
        } else {
            $sWhere = parent::buildFilter($aFilter) . " AND t.topic_date_add <= '" . $sDateNow . "'";
        }

        return $sWhere;
    }


}
