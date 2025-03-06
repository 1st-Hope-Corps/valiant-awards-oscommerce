<?php
class hopebank{
	var $code, $title, $description, $enabled;
	
	/*
	 Sets initial member variables and module status (enabled/disabled).
	 Define the form_action_url variable which is your gateway url (that will recieve the POST data).
	 Include any static variables you may require in your code here.
	 */
	function hopebank(){
		global $order;
		
		$this->code = 'hopebank';
		$this->title = "Hope Bank";
		$this->public_title = "Hope Bank";
		$this->description = "Hope Bank Payment Module";
		$this->sort_order = MODULE_PAYMENT_HOPE_SORT_ORDER;
		$this->enabled = (MODULE_PAYMENT_HOPE_STATUS == 'True') ? true:false;

		if ((int)MODULE_PAYMENT_HOPE_ORDER_STATUS_ID > 0) $this->order_status = MODULE_PAYMENT_HOPE_ORDER_STATUS_ID;

		if (is_object($order)) $this->update_status();
	}
	
	/*
	 Here you can implement using payment zones. This will make the module to be available to that specific zone ONLY.
	 
	 Called by module's class constructor, checkout_confirmation.php, checkout_process.php.
	 */
	function update_status(){
		global $order;

		if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_HOPE_ZONE > 0) ){
			$check_flag = false;
			$check_query = tep_db_query("SELECT zone_id FROM " . TABLE_ZONES_TO_GEO_ZONES . " WHERE geo_zone_id = '" . MODULE_PAYMENT_HOPE_ZONE . "' AND zone_country_id = '" . $order->billing['country']['id'] . "' ORDER BY zone_id");
			
			while ($check = tep_db_fetch_array($check_query)){
				if ($check['zone_id'] < 1){
					$check_flag = true;
					break;
				} elseif ($check['zone_id'] == $order->billing['zone_id']){
					$check_flag = true;
					break;
				}
			}

			if ($check_flag == false) $this->enabled = false;
		}
	}
	
	/*
	 Here you may define client side javascript that will verify any input fields you use in the payment method selection page.
	 
	 Called by checkout_payment.php.
	 */
	function javascript_validation(){
		return false;
	}
	
	/*
	 This function outputs the payment method title/text and if required, the input fields.
	 
	 Called by checkout_payment.php.
	 */
	function selection() {
		return array("id" => $this->code, "module" => $this->public_title);
	}
	
	/*
	 Use this function implement any checks of any conditions after payment method has been selected. You most probably don't need to implement 
	 anything here.
	 
	 Called by checkout_confirmation.php before any page output.
	 */
	function pre_confirmation_check(){
		return false;
	}
	
	/*
	 Implement any checks or processing on the order information before proceeding to payment confirmation. You most probably don't need to implement 
	 anything here.
	 
	 Called by checkout_confirmation.php.
	 */
	function confirmation(){
		$aAccountDetails = $this->_hope_details();
		$aConfirmation = array(
							"fields" => array(
											array("title" => "Hope Bank Account", "field" => tep_draw_input_field("hope_account", $aAccountDetails["account_number"]))
										)
						);
		
		return $aConfirmation;
	}
	
	/*
	 Outputs the html form hidden elements sent as POST data to the payment gateway.
	 
	 Called by checkout_confirmation.php.
	 */
	function process_button(){
		return false;
	}
	
	/*
	 This is where you will implement any payment verification.
	 
	 Called by checkout_process.php before order is finalised.
	 */
	function before_process(){
		global $order, $_REQUEST;
		
		$sBankAccount = $_REQUEST["hope_account"];
		$mTotalOrder = $order->info["subtotal"];
		$aAccountDetails = $this->_hope_details();
		
		if ($aAccountDetails["account_number"] != $sBankAccount){
			$sErrorQuery = "payment_error=".$this->code."&error=".urlencode("Invalid hope bank account.")."&hope_account=".$sBankAccount;
			tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $sErrorQuery, 'SSL', true, false));
		}else{
			$aBalances = $this->_hope_post("balance", array("key" => $aAccountDetails["account_number"], "pass" => $aAccountDetails["account_pass"]));
			
			if ($aBalances["RETURN"]["BALANCE"] < $mTotalOrder){
				$sErrorQuery = "payment_error=".$this->code."&error=".urlencode("Insufficient funds.")."&hope_account=".$aAccountDetails["account_number"];
				tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, $sErrorQuery, "SSL", true, false));
			}else{
				$sRefs = "";
				$order->info["hope_account"] = substr($aAccountDetails["account_number"], 0, 4)."-".str_repeat("X", 6).substr($aAccountDetails["account_number"], -4);
				
				for ($x=0; $x<count($order->products); $x++){
					$sContentType = (isset($order->products[$x]["attributes"])) ? "virtual":"physical";
					
					$aReqParam = array(
									"key" => $aAccountDetails["account_number"], 
									"pass" => $aAccountDetails["account_pass"],
									"amount" => $order->products[$x]["price"],
									"recipient" => "P85L-1235534669",
									"description" => "Payment to ".STORE_NAME." for ".$order->products[$x]["name"]." x".$order->products[$x]["qty"]." (".$sContentType.")"
								);
					
					$aResponse = $this->_hope_post("pay", $aReqParam);
					$sRefs .= ($sRefs != "") ? ", ":"";
					$sRefs .= $aResponse["RETURN"]["REF"];
				}
			}
		}
	}

	function validation_process($total){
		global $order;
		$aAccountDetails = $this->_hope_details();
		$error = false;

		$aBalances = $this->_hope_post("balance", array("key" => $aAccountDetails["account_number"], "pass" => $aAccountDetails["account_pass"]));
		if ($aBalances["RETURN"]["BALANCE"] < $total){
			$error = 'Insufficient funds.';
		}

		return $error;
	}
	
	/*
	 Here you may implement any post proessing of the payment/order after the order has been finalised. At this point you now have a reference to the 
	 created osCommerce order id and you would typically update any custom database tables you may have for your module. You most probably don't need 
	 to implement anything here.
	 
	 Called by checkout_process.php after order is finalised.
	 */
	function after_process(){
		global $order, $insert_id, $_SESSION;
		
		$bPhysical = false;
		$bVirtual = false;
		
		for ($i=0; $i<count($order->products); $i++){
			if (!isset($order->products[$i]["attributes"])){
				$bPhysical = true;
			}else{
				$bVirtual = true;
			}
		}
		
		if (!$bPhysical && $bVirtual){
			$iStatusId = 4;
		}elseif ($bPhysical && !$bVirtual){
			$iStatusId = 5;
		}else{
			$iStatusId = 6;
		}
		
		$sqlUpdate1 = "UPDATE orders SET orders_status = ".$iStatusId." WHERE orders_id = ".$insert_id;
		$sqlUpdate2 = "UPDATE orders_status_history SET orders_status_id = ".$iStatusId." WHERE orders_id = ".$insert_id;
		
		tep_db_query($sqlUpdate1);
		tep_db_query($sqlUpdate2);
	}
	
	/*
	 For more advanced error handling. When your module logic returns any errors you will redirect to checkout_payment.php with some error information.
	 When implemented corretly, this function can be used to genereate the proper error texts for particular errors. The redirect must be formatted 
	 like this:
	 
	 tep_redirect(tep_href_link(FILENAME_CHECKOUT_PAYMENT, 'payment_error=' . $this->code.'&error='.urlencode('some error'), 'NONSSL', true, false));
	 */
	function get_error() {
		global $_REQUEST;

		$aError = array("title" => "Hope Bank Error", "error" => stripslashes(urldecode($_REQUEST["error"])));

		return $aError;
	}
	
	/*
	 Standard functionlity for osCommerce to see if the module is installed.
	 */
	function check() {
		if (!isset($this->_check)){
			$check_query = tep_db_query("SELECT configuration_value FROM " . TABLE_CONFIGURATION . " WHERE configuration_key = 'MODULE_PAYMENT_HOPE_STATUS'");
			$this->_check = tep_db_num_rows($check_query);
		}
		
		return $this->_check;
	}
	
	/*
	 This is where you define module's configurations (displayed in admin).
	 */
	function install(){
		tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Hope Bank module', 'MODULE_PAYMENT_HOPE_STATUS', 'True', 'Do you want to accept Hope Bank payments?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
		tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort order of display.', 'MODULE_PAYMENT_HOPE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
		tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) VALUES ('Set Order Status', 'MODULE_PAYMENT_HOPE_ORDER_STATUS_ID', '4', 'Set the status of orders made with this payment module to this value', '6', '0', 'tep_cfg_pull_down_order_statuses(', 'tep_get_order_status_name', now())");
		tep_db_query("INSERT INTO " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Payment Zone', 'MODULE_PAYMENT_HOPE_ZONE', '0', 'If a zone is selected, only enable this payment method for that zone.', '6', '2', 'tep_get_zone_class_title', 'tep_cfg_pull_down_zone_classes(', now())");
		
		/*
		 Add the fllwing status to your database.
		 Just the orders_status_id if that's already taken by your other status and modify function after_process using the applicable orders_status_id
		 */
		//INSERT INTO orders_status (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) VALUES(4, 1, 'Delivered (Download)', 1, 1);
		//INSERT INTO orders_status (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) VALUES(5, 1, 'Waiting to be Picked up', 1, 0);
		//INSERT INTO orders_status (orders_status_id, language_id, orders_status_name, public_flag, downloads_flag) VALUES(6, 1, 'Mixed (for Download and Pick-up)', 1, 1);
	}
	
	/*
	 Standard functionality to uninstall the module.
	 */
	function remove(){
		tep_db_query("DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
	}
	
	/*
	 This array must include all the configuration setting keys defined in your install() function.
	 */
	function keys(){
		return array("MODULE_PAYMENT_HOPE_STATUS", "MODULE_PAYMENT_HOPE_SORT_ORDER", "MODULE_PAYMENT_HOPE_ORDER_STATUS_ID", "MODULE_PAYMENT_HOPE_ZONE");
	}
	
	/*
	 Returns the bank account and bank password of the user
	 */
	function _hope_details(){
		include_once(DIR_WS_CLASSES."hope_connect.php");
		
		$sqlAccount = "SELECT account_number, account_pass, account_creation 
						FROM bank_users 
						WHERE uid = ".$_SESSION["iMerchantId"];
		
		$oConn = new hope_Connect("172.17.0.2", "hopenet", "root", "jsppassword");
		$aBankResult = $oConn->Query($sqlAccount);

		$result = ($oConn->NumRows() == 1) ? $aBankResult[0]:false;;

		$oConn->DBClose();
		
		$oConn = new hope_Connect("172.17.0.2", "devmain", "root", "jsppassword");

		return $result;
	}
	
	/*
	 The cURL wrapper to post data to Hope Bank
	 */
	function _hope_post($sModule, $aVarFields){
		$aRequest = array(
						"key" => "de4931a928077bd537c88903915beb60", 
						"pass" => "be87bd8999a6276faebe2ce6455bd3e6a96abef8",
						"module" => $sModule, 
						"vars" => $aVarFields
					);
		
		$sRequestJSON = json_encode($aRequest);
		
		# Initialize cURL
		$oCURL = curl_init();
		curl_setopt($oCURL, CURLOPT_URL, "http://hopenet.local/bank/gateway.php");
		curl_setopt($oCURL, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($oCURL, CURLOPT_POST, 1);
		curl_setopt($oCURL, CURLOPT_POSTFIELDS, "q=".$sRequestJSON);
curl_setopt($oCURL, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($oCURL, CURLOPT_SSL_VERIFYPEER, FALSE);		
		# Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL not to print out the results of 
		# its query. Instead, it will return the results as a string return value from curl_exec() 
		# instead of the usual true/false.
		curl_setopt($oCURL, CURLOPT_RETURNTRANSFER, 1);
		
		$sResponse = curl_exec($oCURL);
		return json_decode($sResponse, true);
	}
}
