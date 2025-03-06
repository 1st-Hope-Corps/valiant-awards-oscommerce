<?php
require("osc.php");

$sEmail = $_REQUEST["q"];

$sqlCustomer = "SELECT COUNT(customers_id) 
				FROM customers 
				WHERE customers_email_address = '".$sEmail."'";

echo $oConn->Scalar($sqlCustomer);
?>