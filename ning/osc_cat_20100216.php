<?php
require("osc.php");

# Retrives the child Categories of a specified parent Category
function GetCat($iInputParentID=0){
	global $oConn;
	
	$sqlCat = "SELECT c.categories_id, cd.categories_name, c.parent_id 
				FROM categories c, categories_description cd 
				WHERE c.parent_id = ".$iInputParentID." 
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

$iParentID = (isset($_POST["i"])) ? $_POST["i"]:0;

echo GetCat($iParentID);
?>
