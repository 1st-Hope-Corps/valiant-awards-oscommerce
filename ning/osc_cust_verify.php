<?php
require("osc.php");

$sQuery = $_REQUEST["q"];
$sWhichField = (is_valid_email_address($sQuery)) ? "customers_email_address":"ning_id";

$sqlCustomer = "SELECT COUNT(customers_id) 
				FROM customers 
				WHERE ".$sWhichField." = '".$sQuery."'";

echo $oConn->Scalar($sqlCustomer);
?>