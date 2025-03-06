<?php
# $Id: merchants.php 2145 2008-10-17 $

$merchant_query = tep_db_query("select ning_id, concat(customers_lastname, ', ', customers_firstname) as merchant_name from customers order by customers_lastname");

if ($number_of_rows = tep_db_num_rows($merchant_query)){
	?>
	
	<!-- merchants -->
	
	<tr>
		<td>
			<?php
			$info_box_contents = array();
			$info_box_contents[] = array('text' => "Merchants");
			
			new infoBoxHeading($info_box_contents, false, false);
			
			$merchants_array = array();
			$merchants_array[] = array('id' => '', 'text' => PULL_DOWN_DEFAULT);
			
			while ($merchants = tep_db_fetch_array($merchant_query)){
				//$merchant_name = (strlen($merchants['merchant_name']) > MAX_DISPLAY_MANUFACTURER_NAME_LEN) ? substr($merchants['merchant_name'], 0, MAX_DISPLAY_MANUFACTURER_NAME_LEN) . "..":$merchants['merchant_name'];
				if ($_SESSION["iMerchantId"] == $merchants['ning_id'] && $_SESSION["sStoreType"] == "comm") continue;
				
				$merchant_name = ucwords($merchants['merchant_name']);
				$merchants_array[] = array('id' => $merchants['ning_id'], 'text' => $merchant_name);
			}
			
			$info_box_contents = array();
			$info_box_contents[] = array(
										'form' => tep_draw_form('merchants', tep_href_link(FILENAME_DEFAULT, '', 'NONSSL', false), 'get'),
										'text' => tep_draw_pull_down_menu('merchant_id', $merchants_array, (isset($_GET['merchant_id']) ? $_GET['merchant_id']:''), 'onChange="this.form.submit();" size="' . MAX_MANUFACTURERS_LIST . '" style="width: 100%"') . tep_hide_session_id()
									);
			
			new infoBox($info_box_contents);
			?>
		</td>
	</tr>
	
	<!-- merchants_eof -->
	
	<?php
}
?>