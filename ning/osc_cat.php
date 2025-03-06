<?php
require("osc.php");

# Retrives the child Categories of a specified parent Category
function GetCat($iInputParentID=0, $iInputUID){
	global $oConn;
	
	if ($iInputParentID == 0 && $iInputUID != 1){
		$sWhereClause = "AND c.categories_id != 1";
	}elseif ($iInputParentID == 0 && $iInputUID == 1){
		$sWhereClause = "AND c.categories_id = 1";
	}else{
		$sWhereClause = "";
	}
	
	$sqlCat = "SELECT c.categories_id, cd.categories_name, c.parent_id 
				FROM categories c, categories_description cd 
				WHERE c.parent_id = ".$iInputParentID." 
					".$sWhereClause."
					AND c.categories_id = cd.categories_id 
					AND cd.language_id = 1 
				ORDER BY sort_order, cd.categories_name";
	
	$aCatResultSet = $oConn->Query($sqlCat);
	$iCatCount = $oConn->NumRows();
	
	$aCatData = array();
	$sCatData = "";
	
	if ($iCatCount > 0){
		for ($x=0; $x<$iCatCount; $x++){
			if ($x > 0) $sCatData .= "|";
			$sCatData .= $aCatResultSet[$x]["categories_id"].",".$aCatResultSet[$x]["parent_id"].",".$aCatResultSet[$x]["categories_name"];
		}
	}
	
	return $sCatData;
}

$iParentID = (isset($_REQUEST["i"])) ? $_REQUEST["i"]:0;
$iUID = (isset($_REQUEST["uid"])) ? $_REQUEST["uid"]:0;

echo GetCat($iParentID, $iUID);
?>
