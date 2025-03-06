<?php
require("osc.php");

$sReferer = $_SERVER["HTTP_REFERER"];
$aURI = parse_url($sReferer);
$sHost = (isset($_POST["sDomainNing"])) ? $_POST["sDomainNing"]:$aURI["host"];

// echo "<pre>";
// print_r($aURI);
// echo "</pre>";

// echo $sHost." = ".NING_NETWORK_MYGIZMOZ."<br/>";
// echo $_FILES["sProductImage"]["name"]."<br/>";
// foreach ($_POST as $sVar=>$sVal){
	// echo $sVar." = ".$sVal."<br/>";
// }
//exit;

function CreateZip($aFiles=array(), $sOutputPath= "", $bOverwrite=false){
	// if the zip file already exists and overwrite is false, return false
	if (file_exists($sOutputPath) && !$bOverwrite) return false;
	
	$aValidFiles = array();
	
	// if files were passed in...
	if (is_array($aFiles)){
		// cycle through each file
		foreach($aFiles as $sFile) {
			//make sure the file exists
			if (file_exists($sFile)) $aValidFiles[] = $sFile;
		}
	}else{
		return false;
	}
	
	if (count($aValidFiles) > 0){
		// create the archive
		$oZip = new ZipArchive();
		
		if ($oZip->open($sOutputPath, $bOverwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true){
			return false;
		}
		
		//add the files to the archive and delete the originals
		foreach($aValidFiles as $sFile) {
			$oZip->addFile($sFile);
			unlink($sFile);
		}
		
		// debug
		//echo 'The zip archive contains ',$oZip->numFiles,' files with a status of ',$oZip->status;
		
		// close the zip -- done!
		$oZip->close();
		
		// check to make sure the file exists
		return file_exists($sOutputPath);
	}else{
		return false;
	}
}

if (isset($_POST["bSaveFromNing"]) && ($sHost == HOPE_WWW || $sHost == HOPE_DRUPAL)){
	$sProductType = $_POST["sProductType"];
	$iMemberID = $_POST["iMemberID"];
	$sProductName = addslashes($_POST["sProductName"]);
	$sManufacturer = addslashes($_POST["sManufacturer"]);
	$iCatID = $_POST["iCatID"];
	$sModel = addslashes($_POST["sModel"]);
	$sProductCondition = $_POST["sProductCondition"];
	$sDescription = addslashes($_POST["sDescription"]);
	$mPrice = $_POST["mPrice"];
	$iQuantity = $_POST["iQuantity"];
	$sPhotoEmbed = (trim($_POST["sPhotoEmbed"]) != "") ? "'".addslashes($_POST["sPhotoEmbed"])."'":"NULL";
	$sVideoEmbed = (trim($_POST["sVideoEmbed"]) != "") ? "'".addslashes($_POST["sVideoEmbed"])."'":"NULL";;
	
	$bWithImage = false;
	$bWithItem = false;
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
		
	# --BEGIN Check if there is Downloadable file that was uploaded with no error and a valid filesize
	if ($sProductType == "download" && $_FILES["sProductToDownload"]["error"] == 0 && $_FILES["sProductToDownload"]["size"] > 0){
		$sItemName = addslashes(str_replace(" ", "_", strtolower(basename($_FILES["sProductToDownload"]["name"]))));
		$sItemExtName = substr($sItemName, strlen($sItemName)-3, 3);
		$sTempItemName = $_FILES["sProductToDownload"]["tmp_name"];
		$sItemName = time()."_".$iMemberID."_".$sItemName;
		$sItemPath = "../download/".$sItemName;
		
		if ($sItemExtName == "zip"){
			if (move_uploaded_file($sTempItemName, $sItemPath)) $bWithItem = true;
		}else{
			$sItemPath = "../download/".$sItemName;
			$sZipName = substr($sItemName, 0, strlen($sItemName)-4).".zip";
			$sZipPath = "../download/".$sZipName;
			
			if (move_uploaded_file($sTempItemName, $sItemPath)){
				$bZipZuccess = CreateZip(array($sItemPath), $sZipPath);
				$bWithItem = $bZipZuccess;
			}
		}
	}
	# --END Check if there is Downloadable file that was uploaded with no error and a valid filesize
	
	/* echo "<pre>";
	print_r($_FILES["sProductToDownload"]);
	echo "</pre>";
	
	echo ($bWithItem) ? "true":"false";
	echo "<br/>product type: ".$sProductType;
	echo "<br/>item name: ".$sItemName;
	echo "<br/>item ext: ".$sItemExtName;
	echo "<br/>item path: ".$sItemPath;
	echo "<br/>zip name: ".$sZipName;
	echo "<br/>zip path: ".$sZipPath;
	
	exit; */
	
	$sDBImage = ($bWithImage) ? "'ning/".$sImageName."'":"NULL";
	
	$sqlProducts = "INSERT INTO products 
						VALUES(
							NULL,
							'".$iMemberID."',
							".$sPhotoEmbed.",
							".$sVideoEmbed.",
							".$iQuantity.",
							'".$sModel."',
							".$sDBImage.",
							'".$mPrice."',
							'".date("Y-m-d H:i:s")."',
							NULL,
							NULL,
							'0.00',
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
		
		if ($bWithItem && $sProductType == "download"){
			$sqlProductAttr = "INSERT INTO products_attributes 
									VALUES(
										NULL,
										".$iProdID.",
										6,
										14,
										'0.00',
										'+'
									)";
			
			$oConn->Execute($sqlProductAttr);
			$iProdAttrId = $oConn->LastID();
			
			$sqlProductAttrDL = "INSERT INTO products_attributes_download 
									VALUES(
										".$iProdAttrId.",
										'".$sZipName."',
										3,
										5
									)";
			
			$oConn->Execute($sqlProductAttrDL);
		}
		
		if ($sRedirectTo != ""){
			//header("Location: ".$sRedirectTo."?s=1");
			?>
			<script type="text/javascript" language="JavaScript">
			top.location.href = "<?php echo $sRedirectTo."?s=1" ?>";
			</script>
			<?php
		}else{
			echo "Your product has been posted successfully. Please allow 1-2 hours for this to appear in our store.<br />Thank you for using this service.";
		}
	}
}else{
	echo "Unauthorized domain.";
}
?>