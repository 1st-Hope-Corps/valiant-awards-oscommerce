<?php
require("osc.php");

# Retrives the child Categories of a specified parent Category
function GetCountry(){
	global $oConn;
	
	$sqlCountry = "SELECT countries_id, countries_name, countries_iso_code_2 
					FROM countries 
					ORDER BY countries_id";
	
	$aCountryResultSet = $oConn->Query($sqlCountry);
	$iCountryCount = $oConn->NumRows();
	
	$sCountryData = "";
	
	if ($iCountryCount > 0){
		for ($x=0; $x<$iCountryCount; $x++){
			$sCountryData .= $aCountryResultSet[$x]["countries_id"].",".$aCountryResultSet[$x]["countries_name"].",".$aCountryResultSet[$x]["countries_iso_code_2"]."|";
		}
	}
	
	return $sCountryData;
}

echo GetCountry();
?>
