<?php
/*
  $Id: new_products.php,v 1.34 2003/06/09 22:49:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- new_products //-->
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => sprintf(TABLE_HEADING_NEW_PRODUCTS, strftime('%B')));

//  new contentBoxHeading($info_box_contents);

  if ( (!isset($new_products_category_id)) || ($new_products_category_id == '0') ) {
    $new_products_query = tep_db_query("select p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  } else {
    $new_products_query = tep_db_query("select distinct p.products_id, p.products_image, p.products_tax_class_id, pd.products_name, if(s.status, s.specials_new_products_price, p.products_price) as products_price from " . TABLE_PRODUCTS . " p left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_CATEGORIES . " c where p.ning_id = ".$_SESSION["iMerchantId"]." and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$new_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . MAX_DISPLAY_NEW_PRODUCTS);
  }

  $row = 0;
  $col = 0;
  
  $info_box_contents = array();
  while ($new_products = tep_db_fetch_array($new_products_query)) {
  
  // $new_products['products_name'] = tep_get_products_name($new_products['products_id']);
	
// ----------	
  $product_query = tep_db_query("select products_description, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$new_products['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
  $product = tep_db_fetch_array($product_query);
  $p_id = $product['products_id'];	
	  
  $p_pic = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $new_products['products_image'], $new_products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
  $p_name = '<a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $new_products['products_id']) . '" class="products-mobile-link">' . $new_products['products_name'] . '</a>';
  
  $desc = strip_tags($product['products_description']);
  $p_desc = ''.substr(strip_tags($product['products_description']), 0, 18) . (strlen($desc) > 18 ? '...' : '');
  // $p_desc = $product['products_description'];
  
  $p_price = '<span class="productSpecialPrice">'.$currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id'])).'</span>';

  ?>
  <div>
    <div class="products-mobile-link-container" style="text-align:center;">
      <?= $p_name ?>
    </div>
    <div style="margin-top: 16px;">
      <?= $p_pic ?>
    </div>
    <div class="products-mobile-info">
      <div class="products-mobile-description-container">
        <?= $p_desc ?>
      </div>
      <div class="products-mobile-price-container">
        <div style="float: left;width: 50%;"><?= $p_price ?></div>
        <div style="float: right;width: 50%;text-align: right;">
          <?php
          if (isset($_SESSION["iMerchantId"])) {
          ?>
          <a href="<?= tep_href_link("products_new.php","action=buy_now&products_id=".$p_id) ?>">Add to cart</a>
          <?php

          }else{
            ?>
            <a href="#" onclick="event.preventDefault();alert('Please login to continue.')">Add to cart</a>
            <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>
  <?php

    $col ++;
    if ($col > 1) {
      $col = 0;
      $row ++;
    }
  }

  // new contentBox($info_box_contents);
?>
<!-- new_products_eof //-->
