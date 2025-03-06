<?php
/*
  $Id: shopping_cart.php,v 1.73 2003/06/09 23:03:56 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

	if ($cart->count_contents() > 0) {
  	include(DIR_WS_CLASSES . 'payment.php');
  	$payment_modules = new payment('hopebank');

    if (isset($_SESSION["iMerchantId"])) {
      $payment_validation_process = $$payment->validation_process($cart->show_total());
    }
	}
  
  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_SHOPPING_CART);

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link(FILENAME_SHOPPING_CART));

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<link rel="stylesheet" href="includes/mobile.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<meta content="width=device-width, initial-scale=1" name="viewport" />
<link rel="stylesheet" href="includes/mobile.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
  	<td valign="top">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
	</td>
<!-- body_text //-->
    <td width="100%" valign="top"><?php echo tep_draw_form('cart_quantity', tep_href_link(FILENAME_SHOPPING_CART, 'action=update_product')); ?><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td>
		
<?php tep_draw_heading_top(); ?>

<?php new contentBoxHeading_ProdNew($info_box_contents);?>

<?php tep_draw_heading_top_4();?>

<?php
  if ($cart->count_contents() > 0) {
?>
<?php
    $info_box_contents = array();
    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => ' class="shop_cart" style="width:15%;"',
                                    'text' => ''.TABLE_HEADING_REMOVE.'');

    $info_box_contents[0][] = array('align' => 'center',
									'params' => ' class="shop_cart" style="width:40%;"',
                                    'text' => ''.TABLE_HEADING_PRODUCTS.'');

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => ' class="shop_cart" style="width:20%;"',
                                    'text' => ''.TABLE_HEADING_QUANTITY.'');

    $info_box_contents[0][] = array('align' => 'center',
                                    'params' => ' class="shop_cart" style="width:25%;"',
                                    'text' => ''.TABLE_HEADING_TOTAL.'');

    $any_out_of_stock = 0;
    $products = $cart->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
// Push all attributes information in an array
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        while (list($option, $value) = each($products[$i]['attributes'])) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_OPTIONS_VALUES . " poval, " . TABLE_PRODUCTS_ATTRIBUTES . " pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (($i/2) == floor($i/2)) {
        $info_box_contents[] = array('params' => 'class=""');
      } else {
        $info_box_contents[] = array('params' => 'class=""');
      }

      $cur_row = sizeof($info_box_contents) - 1;

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => 'class="" valign="top"',
                                             'text' => '<br style="line-height:1px;"><br style="line-height:21px;">' .tep_draw_checkbox_field('cart_delete[]', $products[$i]['id']));

      $products_name = '
											<table cellpadding="0" cellspacing="0" border="0" style=" width:150px;">
												<tr><td height="10"></td></tr>
												<tr><td align="center"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $products[$i]['id']) . '">' . tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr>
												<tr><td align="center" height="30" style=" vertical-align:middle;padding:5px 0px 5px 0px;"><em>' . $products[$i]['name'] . '</em>';
					 

							  
      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }
		
		$bVirtualItem = false;
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        reset($products[$i]['attributes']);
        while (list($option, $value) = each($products[$i]['attributes'])) {
		  if ($products[$i][$option]['products_options_name'] == "Download") $bVirtualItem = true;
          $products_name .= '<br style="line-height:1px;"><br style="line-height:5px;"><small style="color:#006600;"><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }	
		$products_name .= '</td></tr>

											</table>
								';
					 

	  
	  
	  
	  
      $info_box_contents[$cur_row][] = array('align' => 'center',
      										'params' => '',
                                             'text' => ''.$products_name);

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => '',
                                             'text' => '<br style="line-height:1px;"><br style="line-height:21px">' . (($bVirtualItem) ? '<span class="productSpecialPrice" style="color:#006600;">'.$products[$i]['quantity'].'</span>'.tep_draw_hidden_field('cart_quantity[]', $products[$i]['quantity']):tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'size="4" class="se3"')) . tep_draw_hidden_field('products_id[]', $products[$i]['id']));

      $info_box_contents[$cur_row][] = array('align' => 'center',
                                             'params' => '',
                                             'text' => '<br style="line-height:1px;"><br style="line-height:24px"><span class="productSpecialPrice" style="color:#006600;">' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</span>');
		$bVirtualItem = false;
	}

    new productListingBox($info_box_contents);
?>

<?php
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></td>
      </tr>
	</table>
<?php
      } else {
?>
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td class="stockWarning" align="center"><br><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></td>
      </tr>
  </table>
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
	  <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '1'); ?></td>
      </tr>
  </table>

<?php
      }
    }
?>
											<table cellpadding="0" cellspacing="0" border="0"><tr><td style="height:3px;" class="bg_gg"><?php echo tep_draw_separator('spacer.gif', '1', '1'); ?></td></tr></table>
												<table cellspacing="0" cellpadding="0" border="0" class="shop_cart" style=" background:#e2f3c7;">
													<tr>
														<td width="75%" align="right" class="shop_cart" style="vertical-align:middle;text-align:right; height:28px;"><b style="color:#006600;"><?php echo SUB_TITLE_SUB_TOTAL; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
														<td width="25%" align="center" style=" vertical-align:middle;">														
															<span class="productSpecialPrice" style="color:#006600;"><?php echo $currencies->format($cart->show_total()); ?></span>
														</td>
													</tr>
												</table>
												<table cellspacing="0" cellpadding="0" border="0" style=" background:#e2f3c7;">
													<tr><td height="9"></td></tr>
													<tr>
														<td><?php echo tep_draw_separator('pixel_trans.gif', '25', '1'); ?><?php echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE_CART); ?><?php echo tep_draw_separator('pixel_trans.gif', '5', '1'); ?><?php
    $back = sizeof($navigation->path)-2;
    if (isset($navigation->path[$back])) {
 echo '<a href="' . tep_href_link($navigation->path[$back]['page'], tep_array_to_string($navigation->path[$back]['get'], array('action')), $navigation->path[$back]['mode']) . '">' . tep_image_button('button_continue_shopping1.gif', IMAGE_BUTTON_CONTINUE_SHOPPING) . '</a>'; 
    }
?><?php echo tep_draw_separator('pixel_trans.gif', '5', '1'); ?><?php echo '<a href="' . tep_href_link(FILENAME_CHECKOUT_SHIPPING, '', 'SSL') . '">' . tep_image_button('button_checkout.gif', IMAGE_BUTTON_CHECKOUT) . '</a>'; ?></td>
													</tr>
													<tr><td height="16"></td></tr>
												</table>
<?php tep_draw_heading_bottom_4();?>



<?php
	    $initialize_checkout_methods = $payment_modules->checkout_initialization_method();
	
	    if (!empty($initialize_checkout_methods)) {
	?>
    <table cellpadding="0" cellspacing="0" border="0">
	      <tr>
	        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	      </tr>
	      <tr>
	        <td align="right" class="main" style="padding-right: 50px;"><?php echo TEXT_ALTERNATIVE_CHECKOUT_METHODS; ?></td>
	      </tr>
	<?php
	      reset($initialize_checkout_methods);
	      while (list(, $value) = each($initialize_checkout_methods)) {
	?>
	      <tr>
	        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
	      </tr>
	      <tr>
	        <td align="right" class="main"><?php echo $value; ?></td>
	      </tr>
      </table>    
	<?php
	      }
	    }
	 	  } else {
?>
     
    <br style="line-height:1px;"><br style="line-height:5px;">

	
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
			  	<td></td>
				<td align="center" class="main"><br><?php new infoBox_77(array(array('text' => TEXT_CART_EMPTY))); ?></td>
				<td></td>
			  </tr>
			  <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?><br><br></td>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              </tr>
			  <tr><td colspan="3" height="5"></td></tr>
            </table>
			
			
<?php tep_draw_heading_bottom_3();?>		
<?php
  }
?>



<?php tep_draw_heading_bottom();?>

    </table>
	

	
	</form></td>
	
<!-- body_text_eof //-->
    <td valign="top">
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
      <h2 id="mobile-header-title"><a href="/index.php">Cart</a></h2>
    </div>
    <div style="" id="hamburger-container">
      <a href="/shopping_cart.php" style="color:#000">
        <span><?= $cart->count_contents() ?></span>
        <i class="fas fa-cart-shopping fa-flip-horizontal mobile-menu-action"></i>
      </a>
    </div>
  </div>
  <div id="mobile-content">
    <!-- <div class="h2" style="text-align:center;">Cart</div> -->
    <form action="/shopping_cart.php?action=update_product" method="POST" id="update_product_form">
      <div class="rewards-cart-container">
        <?php
        if(sizeof($products) > 0){
        ?>
        <?php for ($i=0, $n=sizeof($products); $i<$n; $i++) { ?>
          <div class="products-mobile-cart-items-container">
            <div>
              <div style="margin-bottom: 10px;">
                <div style="float: left;width: 30%;">
                  <div class="product-cart-img">
                    <?= tep_image(DIR_WS_IMAGES . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) ?>
                  </div>
                </div>
                <div style="float: left;width: 55%;">
                  <div>
                    <?= $products[$i]['name'] ?>
                  </div>
                  <div style="margin-top:53px">
                    <?php

                    $p_price = '<span >'.$currencies->display_price($products[$i]['price'], tep_get_tax_rate($products[$i]['tax_class_id'])).'</span>';

                    ?>
                    <?= $p_price ?>
                  </div>
                </div>
                <div style="float: right;width: 15%;text-align: left;" class="quantity-and-amount-section" price="<?= $products[$i]['price'] ?>">
                  <div>
                    <!-- <input type="number" name="cart_quantity[]" value="<?= $products[$i]['quantity'] ?>"> -->
                    <select name="cart_quantity[]" class="cart_quantity">
                      <?php

                        for ($quantities_index=1; $quantities_index <= 5; $quantities_index++) { 
                          ?>
                            <option <?= $quantities_index == $products[$i]['quantity'] ? 'selected': '' ?> value="<?= $quantities_index ?>"><?= $quantities_index ?></option>
                          <?
                        }

                      ?>
                    </select>
                    <input type="hidden" name="products_id[]" value="<?= $products[$i]['id'] ?>">
                  </div>
                  <div style="margin-top:48px" class="total-price-per-quantity">
                    <?= $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) ?>
                  </div>
                </div>
                <div style="clear: both;"></div>
                <?php

                    if (STOCK_CHECK == 'true') {
                      $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
                      if (tep_not_null($stock_check)) {
                        $any_out_of_stock = 1;

                        echo $stock_check;
                      }
                    }
                  ?>
                <div style="margin-top: 10px;margin-bottom: 10px;font-size: 0.8rem;">
                  <input type="checkbox" name="cart_delete[]" value="<?= $products[$i]['id'] ?>" style="display: none;"> 
                  <div>
                    <a style="cursor:pointer;color:#2f80ED;text-decoration: underline;" class="remove-product-cart" cart-product-id="<?= $products[$i]['id'] ?>"><i class="fas fa-trash"></i> Remove</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
        <div style="margin-top: 20px;">
          <div style="font-size:1.1rem;font-weight: bold;">
            Overall Total: <span id="total-cart-price"><?php echo $currencies->format($cart->show_total()); ?></span>
          </div>
          <div style="margin-top:15px">
            
            <?php
            if ($payment_validation_process === false) {
              ?>
                <a href="/index.php" class="btn">Cancel</a>
                <input type="submit" class="primary" value="Continue to Buy" class="update">
              <?php
            }else{
              ?>
              <div style="margin-bottom: 20px;color: red;">
                <?= $payment_validation_process ?>
              </div>
              <a href="/index.php" class="btn">Cancel</a>
              <?php
            }
            ?>
            <!-- <a href="/checkout_shipping.php">Continue to Buy</a> -->
          </div>
        </div>
        <?php
        }else{
          ?>
          <div style="color: red;margin-bottom: 30px;"> Your cart is empty! </div>
          <a href="/index.php" class="btn primary">Go back to product catalog</a>
          <?php
        }
        ?>
      </div>
    </form>
  </div>
  <?php require_once(DIR_WS_INCLUDES . '/mobile_footer.php'); ?>

</div>
<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.remove-product-cart').click(function(){
      let productCartItemContainer = $(this).closest('.products-mobile-cart-items-container');
      let checkboxToDelete = productCartItemContainer.find('input[type="checkbox"]');

      checkboxToDelete.prop('checked', true);
      // productCartItemContainer.hide();

      let update_shipping_cart = '<input type="text" name="update_shipping_cart" value="1" style="display:none">';
      $('.rewards-cart-container').append(update_shipping_cart);

      $('#update_product_form').submit();
    });

    $('.cart_quantity').change(function(){
      let quantity = $(this).val();
      let quantityAmountSection = $(this).closest('.quantity-and-amount-section');
      let price = parseInt(quantityAmountSection.attr('price'));
      let finalPrice = price * quantity;
      finalPrice = 'V' + (finalPrice.toFixed(2));


      quantityAmountSection.find('.total-price-per-quantity').text(finalPrice);

      getCartTotalPrice();

    })
  })

  function getCartTotalPrice()
  {
    let finalPrice = 0;

    $('.quantity-and-amount-section').each(function(){
      quantity = $(this).find('.cart_quantity').val();
      price = parseInt($(this).attr('price'));

      finalPrice += price * quantity;
    });

    $('#total-cart-price').text('V' + finalPrice.toFixed(2));
  }
</script>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
