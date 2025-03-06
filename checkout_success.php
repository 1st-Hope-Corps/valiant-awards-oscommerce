<?php
/*
  $Id: checkout_success.php,v 1.49 2003/06/09 23:03:53 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

// if the customer is not logged on, redirect them to the shopping cart page
  if (!tep_session_is_registered('customer_id')) {
    tep_redirect(tep_href_link(FILENAME_SHOPPING_CART));
  }

  if (isset($HTTP_GET_VARS['action']) && ($HTTP_GET_VARS['action'] == 'update')) {
    $notify_string = '';

    if (isset($HTTP_POST_VARS['notify']) && !empty($HTTP_POST_VARS['notify'])) {
      $notify = $HTTP_POST_VARS['notify'];

      if (!is_array($notify)) {
        $notify = array($notify);
      }

      for ($i=0, $n=sizeof($notify); $i<$n; $i++) {
        if (is_numeric($notify[$i])) {
          $notify_string .= 'notify[]=' . $notify[$i] . '&';
        }
      }

      if (!empty($notify_string)) {
        $notify_string = 'action=notify&' . substr($notify_string, 0, -1);
      }
    }

    tep_redirect(tep_href_link(FILENAME_DEFAULT, $notify_string));
  }

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CHECKOUT_SUCCESS);

  $breadcrumb->add(NAVBAR_TITLE_1);
  $breadcrumb->add(NAVBAR_TITLE_2);

  $global_query = tep_db_query("select global_product_notifications from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customer_id . "'");
  $global = tep_db_fetch_array($global_query);

  if ($global['global_product_notifications'] != '1') {
    $orders_query = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by date_purchased desc limit 1");
    $orders = tep_db_fetch_array($orders_query);

    $products_array = array();
    $products_query = tep_db_query("select orders_products.products_id, orders_products.products_quantity, orders_products.products_name , products.products_image , products_description.products_description , orders_products.products_coupon from " . TABLE_ORDERS_PRODUCTS . " LEFT JOIN products ON products.products_id = orders_products.products_id LEFT JOIN products_description ON products.products_id = products_description.products_id where orders_id = '" . (int)$orders['orders_id'] . "' order by orders_products.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name'],
                                'products_quantity' => $products['products_quantity'],
                                'products_description' => $products['products_description'],
                                'coupon' => $products['products_coupon'],
                                'image' => $products['products_image']);
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<link rel="stylesheet" href="includes/mobile.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td class="col_left">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
   </td>
<!-- body_text //-->
    <td width="100%" class="col_center"><?php echo tep_draw_form('order', tep_href_link(FILENAME_CHECKOUT_SUCCESS, 'action=update', 'SSL')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>

<?php tep_draw_heading_top();?>
		
<?php new contentBoxHeading_ProdNew($info_box_contents);?>

<?php tep_draw_heading_top_1();?>
		
		<table border="0" width="100%" cellspacing="4" cellpadding="2">
          <tr style="display:none">
            <td valign="top" align="center"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_man_on_board.gif', HEADING_TITLE); ?></td></tr>
			
            <tr><td valign="top" class="main"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?>
			
			<div align="center" class="pageHeading"><?php echo HEADING_TITLE; ?></div>
			
			

<?php
if (!strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO)) {
// Get last order id for checkout_success
	$oOrders = tep_db_query("select orders_id from " . TABLE_ORDERS . " where customers_id = '" . (int)$customer_id . "' order by orders_id desc limit 1");
	$aOrders = tep_db_fetch_array($oOrders);
	$iLastOrderId = $aOrders['orders_id'];
} else {
	$iLastOrderId = $HTTP_GET_VARS['order_id'];
}
$oDownloads = tep_db_query("select date_format(o.date_purchased, '%Y-%m-%d') as date_purchased_day, opd.download_maxdays, op.products_name, opd.orders_products_download_id, opd.orders_products_filename, opd.download_count, opd.download_maxdays from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_ORDERS_PRODUCTS_DOWNLOAD . " opd, " . TABLE_ORDERS_STATUS . " os where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = '" . (int)$iLastOrderId . "' and o.orders_id = op.orders_id and op.orders_products_id = opd.orders_products_id and opd.orders_products_filename != '' and o.orders_status = os.orders_status_id and os.downloads_flag = '1' and os.language_id = '" . (int)$languages_id . "'");

if ($_SESSION["sStoreType"] == "comm" && tep_db_num_rows($oDownloads) == 0){
	echo '<div style="margin-left:25px; padding-top:10px; width:360px; text-align:left; font-size:1.3em; font-weight:bold;" align="center">You may pickup your order in 2 -3 days at the Hope Cybrary:<br><br>
			2695 South Avenue<br>
			corner of San Francisco Street,
			Barangay Olympia, Makati City
			</div>';
}

if (DOWNLOAD_ENABLED == 'true') include(DIR_WS_MODULES . 'downloads.php');

?>
<br>
<?php
  if ($global['global_product_notifications'] != '1') {
    // echo TEXT_NOTIFY_PRODUCTS . '<br><p class="productsNotifications" style="color:#0C2F01;">';
    echo '<p>';

    $products_displayed = array();
    for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
      if (!in_array($products_array[$i]['id'], $products_displayed)) {
        // echo tep_draw_checkbox_field('notify[]', $products_array[$i]['id']) . ' ' . $products_array[$i]['text'] . '<br>';
        echo tep_image(DIR_WS_IMAGES . $products_array[$i]['coupon'], $products_array[$i]['name'], '100%', '100%') . '<br>'. '<br>'. '<br>';
        $products_displayed[] = $products_array[$i]['id'];
      }
    }

    echo '</p>';
  } else {
    echo TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
  }
?>
            <h3><?php echo TEXT_THANKS_FOR_SHOPPING; ?></h3></td>
          </tr>
        </table>
		
     <table cellpadding="0" cellspacing="0" border="0">
	 	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
      	<tr><td align="right" class="main bg_input"><?php echo tep_image_submit('button_continue_shop2.gif', IMAGE_BUTTON_CONTINUE); ?></td></tr>
      	<tr><td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td></tr>
	 </table>
	  
      
	  <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="right"><?php echo tep_draw_separator('pixel_silver.gif', '1', '5'); ?></td>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
              </tr>
            </table></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
            <td width="25%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%"><?php echo tep_draw_separator('pixel_silver.gif', '100%', '1'); ?></td>
                <td width="50%"><?php echo tep_image(DIR_WS_IMAGES . 'checkout_bullet.gif'); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_DELIVERY; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_PAYMENT; ?></td>
            <td align="center" width="25%" class="checkoutBarFrom"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></td>
            <td align="center" width="25%" class="checkoutBarCurrent"><?php echo CHECKOUT_BAR_FINISHED; ?></td>
          </tr>
       </table>
		
<?php tep_draw_heading_bottom_1();?>
      		
<?php tep_draw_heading_bottom();?>
	
		</td>
      </tr>
    </table></form></td>
<!-- body_text_eof //-->
    <td class="col_right">
<!-- right_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
<!-- right_navigation_eof //-->
    </td>
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->

<div id="mobile-container">

  <div id="mobile-header-container">
    <div style="width: 30%;">
      <img id="mobile-header-image" src="images/1st_Hope_Logo.png" style="width: 100px;">
    </div>
    <div id="mobile-header-title-container"  style="width: 45%;text-align: center;">
      <h2 id="mobile-header-title"><a href="/index.php">Checkout Payment Success</a></h2>
    </div>
    <div style="" id="hamburger-container">
      <a href="/shopping_cart.php" style="color:#000">
        <span><?= $cart->count_contents() ?></span>
        <i class="fas fa-cart-shopping fa-flip-horizontal mobile-menu-action"></i>
      </a>
    </div>
  </div>
  <div id="mobile-content">
    <div class="h2" style="text-align:center;">Your Award has been processed</div>
    <div class="rewards-products-container">
      
        <?php
          if ($global['global_product_notifications'] != '1') {
            // echo TEXT_NOTIFY_PRODUCTS . '<br><p class="productsNotifications" style="color:#0C2F01;">';
            $products_displayed = array();
            for ($i=0, $n=sizeof($products_array); $i<$n; $i++) {
              if (!in_array($products_array[$i]['id'], $products_displayed)) {
                ?>
                <div style="width:85%;padding: none;" class="product-coupon-item product-coupon-success-item">
                  <?php
                  // var_dump($products_array[$i]);exit;
                  echo tep_image(DIR_WS_IMAGES . $products_array[$i]['coupon'], $products_array[$i]['name'], '50%', 'auto') . '<br>';
                  $products_displayed[] = $products_array[$i]['id'];

                  ?>
                  <p><a href="<?= DIR_WS_IMAGES . $products_array[$i]['coupon'], $products_array[$i]['name'] ?>" download>Download Here</a></p>
                </div>
                <?php
              }
            }
          } else {
            echo TEXT_SEE_ORDERS . '<br><br>' . TEXT_CONTACT_STORE_OWNER;
          }
        ?>
      </div>
    </div>
  </div>
  <?php require_once(DIR_WS_INCLUDES . '/mobile_footer.php'); ?>

</div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>

