<?php
/*
  $Id: column_left.php,v 1.15 2003/07/01 14:34:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<table border="0" cellspacing="0" cellpadding="0" class="box_width_left">
	<tr><td width="100%">
		<table border="0" cellspacing="0" cellpadding="0">
<?php
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_categories_box();
  } else {
    include(DIR_WS_BOXES . 'categories.php');
  }
  
	if ($_SESSION["sStoreType"] == "store") include(DIR_WS_BOXES . 'merchants.php');
// ------------------------------------------------- 
// require(DIR_WS_BOXES . 'information.php');
// ------------------------------------------------- 
// require(DIR_WS_BOXES . 'whats_new.php');
// -------------------------------------------------    
  if ((USE_CACHE == 'true') && empty($SID)) {
    echo tep_cache_manufacturers_box();
  } else {
//  include(DIR_WS_BOXES . 'manufacturers.php');
  }
// -------------------------------------------------  
  
// require(DIR_WS_BOXES . 'search.php');
// -------------------------------------------------    
	if (isset($HTTP_GET_VARS['products_id'])) {
		if (basename($PHP_SELF) != FILENAME_TELL_A_FRIEND) include(DIR_WS_BOXES . 'tell_a_friend.php');
	}   else {
//		include(DIR_WS_BOXES . 'specials.php');
	}
// -------------------------------------------------  

  

//  if (isset($HTTP_GET_VARS['products_id'])) include(DIR_WS_BOXES . 'manufacturer_info.php');
// -------------------------------------------------  
//  if (tep_session_is_registered('customer_id')) include(DIR_WS_BOXES . 'order_history.php');
// -------------------------------------------------  
  if (isset($HTTP_GET_VARS['products_id'])) {
    if (tep_session_is_registered('customer_id')) {
      $check_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "' and global_product_notifications = '1'");
      $check = tep_db_fetch_array($check_query);
      if ($check['count'] > 0) {
//   	include(DIR_WS_BOXES . 'best_sellers.php');
      } else {
// 	    include(DIR_WS_BOXES . 'product_notifications.php');
      }
      } else {
//      include(DIR_WS_BOXES . 'product_notifications.php');
      }
      } else {
//      include(DIR_WS_BOXES . 'best_sellers.php');
      }
// -------------------------------------------------  
// require(DIR_WS_BOXES . 'reviews.php');
// -------------------------------------------------  
  if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
   // include(DIR_WS_BOXES . 'languages.php');
   // include(DIR_WS_BOXES . 'currencies.php');
  }
	// require(DIR_WS_BOXES . 'shopping_cart.php');
// -------------------------------------------------  
?>
		</table>
		<table cellpadding="0" cellspacing="0" border="0">
		      <tr><td height=4></td></tr>
			  <tr><td><a href="<?php echo tep_href_link('index.php?cPath=3')?>"><?php echo tep_image(DIR_WS_IMAGES.'m18.gif')?></a></td></tr>
			  <tr><td height=3></td></tr>
			  <tr><td><a href="<?php echo tep_href_link('index.php?cPath=5')?>"><?php echo tep_image(DIR_WS_IMAGES.'m19.gif')?></a></td></tr>
		
			  <tr><td height=8></td></tr>

		</table>
	</td>
	<td><?php echo tep_image(DIR_WS_IMAGES.'part_l.gif')?></td></tr>
</table>
