<?php
require("osc.php");

$sReferer = $_SERVER["HTTP_REFERER"];
$aURI = parse_url($sReferer);
$sHost = $aURI["host"];

if (isset($_POST["bSaveFromNing"]) && $sHost == NING_NETWORK){
	$iMemberID = $_POST["iMemberID"];
	$sProductName = addslashes($_POST["sProductName"]);
	$sManufacturer = addslashes($_POST["sManufacturer"]);
	$iCatID = $_POST["iCatID"];
	$sModel = addslashes($_POST["sModel"]);
	$sProductCondition = $_POST["sProductCondition"];
	$sDescription = addslashes($_POST["sDescription"]);
	$mPrice = $_POST["mPrice"];
	$iQuantity = $_POST["iQuantity"];
	$bWithImage = false;
	$sRedirectTo = $_POST["sRedirectTo"];
	
	# --BEGIN Check if there is an Image to upload with no error and a valid filesize
	if ($_FILES["sProductImage"]["error"] == 0 && $_FILES["sProductImage"]["size"] > 0){
		$sImageType = $_FILES["sProductImage"]["type"];
		$iImageSize = $_FILES["sProductImage"]["size"];
		$sImageName = addslashes(str_replace(" ", "_", strtolower(basename($_FILES["sProductImage"]["name"]))));
		$sTempImageName = $_FILES["sProductImage"]["tmp_name"];
		$sImageExtName = substr($sImageName, strlen($sImageName)-3, 3);
		$sImageName = time()."_".$iMemberID."_".$sImageName;
		$sImagePath = "../".NING_IMAGE_DIR."/".$sImageName;
		
		if (($sImageType == "image/jpeg" || $sImageType == "image/pjpeg") && $sImageExtName == "jpg" && $iImageSize <= $_POST["MAX_FILE_SIZE"]){
			if (move_uploaded_file($sTempImageName, $sImagePath)) $bWithImage = true;
		}
	}
	# --END Check if there is an Image to upload with no error and a valid filesize
		
	$sDBImage = ($bWithImage) ? "'ning/".$sImageName."'":"NULL";
	
	$sqlProducts = "INSERT INTO products 
						VALUES(
							NULL,
							'".$iMemberID."',
							".$iQuantity.",
							'".$sModel."',
							".$sDBImage.",
							'".$mPrice."',
							'".date("Y-m-d H:i:s")."',
							NULL,
							NULL,
							'1.00',
							1,
							2,
							NULL,
							0
						)";
	
	if ($oConn->Execute($sqlProducts)){
		$iProdID = $oConn->LastID();
		
		$sqlProductsDesc = "INSERT INTO products_description 
								VALUES(
									".$iProdID.",
									1,
									'".$sProductName."',
									'".$sDescription."',
									NULL,
									0
								)";
		
		$sqlProductsCat = "INSERT INTO products_to_categories 
								VALUES(
									".$iProdID.",
									".$iCatID."
								)";
		
		$oConn->Execute($sqlProductsDesc);
		$oConn->Execute($sqlProductsCat);
		
		header("Location: ".$sRedirectTo."?s=1");
	}
}
?>