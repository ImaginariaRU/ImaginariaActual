<?php


class PluginCastuser_ModuleCast_MapperCast extends Mapper
{

    protected $oDb;

	public function castExist($sTarget,$iTargerId,$iUserId){
	    $table = Config::Get('plugin.castuser.db.table.user_cast_history');
	    
		$sql = "SELECT 
					COUNT(*) as cast_count	 
				FROM 
	 				{$table} AS tc
	 			WHERE
	 				target = ?
	 			AND 
	 				target_id = ?d
	 			AND 
	 				user_id = ?d
			";

		$iExistCount = 0;
		
		if ($aRows=$this->oDb->select($sql,$sTarget,$iTargerId,$iUserId)) {
			foreach ($aRows as $iItem) {
				$iExistCount = $iItem['cast_count'];
			}
		}

		return $iExistCount;	
	}
    
	public function saveExist($sTarget,$iTargerId,$iUserId){
	    $table = Config::Get('plugin.castuser.db.table.user_cast_history');
		$sql="
			INSERT INTO 
				{$table}
			VALUES (NULL, ?, ?d, ?d);
		";
		
		return $this->oDb->query(
			$sql,
			$sTarget,
			$iTargerId,
			$iUserId
		);		
		
	}
	
}