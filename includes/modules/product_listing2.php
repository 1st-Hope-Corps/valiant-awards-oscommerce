<?php
/*
  $Id: product_listing.php,v 1.44 2003/06/09 22:49:59 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>

<?php echo tep_draw_title_top();?>

				<?php echo $breadcrumb->trail(' &raquo; ')?>
			
<?php echo tep_draw_title_bottom();?>	
								
<? tep_draw_heading_top_3();?>

<?php
//exit($listing_sql);
  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  if ( ($listing_split->number_of_rows > 0) && ( (PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '2') ) ) {
?>
<?php echo tep_draw_result_top_1(); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="result">
  <tr>
    <td><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="result_right" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php echo tep_draw_result_bottom_1(); ?>
<?php echo tep_draw_result_top(); ?>

<?php
  }

$info_box_contents = array();
  $list_box_contents = array();
$my_row = 0;
$my_col = 0;


  for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'PRODUCT_LIST_MODEL':
        $lc_text = TABLE_HEADING_MODEL;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_NAME':
        $lc_text = TABLE_HEADING_PRODUCTS;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_MANUFACTURER':
        $lc_text = TABLE_HEADING_MANUFACTURER;
        $lc_align = '';
        break;
      case 'PRODUCT_LIST_PRICE':
        $lc_text = TABLE_HEADING_PRICE;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_QUANTITY':
        $lc_text = TABLE_HEADING_QUANTITY;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_WEIGHT':
        $lc_text = TABLE_HEADING_WEIGHT;
        $lc_align = 'right';
        break;
      case 'PRODUCT_LIST_IMAGE':
        $lc_text = TABLE_HEADING_IMAGE;
        $lc_align = 'center';
        break;
      case 'PRODUCT_LIST_BUY_NOW':
        $lc_text = TABLE_HEADING_BUY_NOW;
        $lc_align = 'center';
        break;
    }

    if ( ($column_list[$col] != 'PRODUCT_LIST_BUY_NOW') && ($column_list[$col] != 'PRODUCT_LIST_IMAGE') ) {
      $lc_text = tep_create_sort_heading($HTTP_GET_VARS['sort'], $col+1, $lc_text);
    }

    $list_box_contents[0][] = array('align' => $lc_align,
                                    'params' => 'class="productListing-heading"',
                                    'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_split->number_of_rows > 0) {
    $rows = 0;
    $listing_query = tep_db_query($listing_split->sql_query);
    while ($listing = tep_db_fetch_array($listing_query)) {
      $rows++;

      if (($rows/2) == floor($rows/2)) {
        $list_box_contents[] = array('params' => 'class="productListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="productListing-odd"');
      }

      $cur_row = sizeof($list_box_contents) - 1;

      for ($col=0, $n=sizeof($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $lc_align = '';
            $lc_text = '&nbsp;' . $listing['products_model'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_NAME':
            $lc_align = '';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
//FILENAME_PRODUCT_INFO
            $p_name = $lc_text = '<a href="' . tep_href_link('product_info2.php', 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            } else {
            $p_name = $lc_text = '<a href="' . tep_href_link('product_info2.php', ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . $listing['products_name'] . '</a>';
            }
            break;
          case 'PRODUCT_LIST_MANUFACTURER':
            $lc_align = '';
            $lc_text = '&nbsp;<a href="' . tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $listing['manufacturers_id']) . '">' . $listing['manufacturers_name'] . '</a>&nbsp;';
            break;
          case 'PRODUCT_LIST_PRICE':
            $lc_align = 'right';
            if (tep_not_null($listing['specials_new_products_price'])) {
			$p_price = $lc_text = '<s>' .  $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</s>&nbsp; <span class="productSpecialPrice">' . $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
            } else {
           $p_price = $lc_text = '<span class="productSpecialPrice">' . $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])) . '</span>';
            }
            break;
          case 'PRODUCT_LIST_QUANTITY':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_quantity'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_WEIGHT':
            $lc_align = 'right';
            $lc_text = '&nbsp;' . $listing['products_weight'] . '&nbsp;';
            break;
          case 'PRODUCT_LIST_IMAGE':
            $lc_align = 'center';
            if (isset($HTTP_GET_VARS['manufacturers_id'])) {
              $p_pic = '<a href="' . tep_href_link('product_info2.php', 'manufacturers_id=' . $HTTP_GET_VARS['manufacturers_id'] . '&products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            } else {
              $p_pic = '<a href="' . tep_href_link('product_info2.php', ($cPath ? 'cPath=' . $cPath . '&' : '') . 'products_id=' . $listing['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>';
            }
            break;
          case 'PRODUCT_LIST_BUY_NOW':
            $lc_align = 'center';
            $lc_text = '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $listing['products_id']) . '">' . tep_image_button('button_buy_now.gif', IMAGE_BUTTON_BUY_NOW) . '</a>&nbsp;';
            break;
        }
		
$product_query = tep_db_query("select products_description, products_id from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$listing['products_id'] . "' and language_id = '" . (int)$languages_id . "'");
      $product = tep_db_fetch_array($product_query);
	  
       	$p_desc = substr(strip_tags($product['products_description']), 0, MAX_DESCR_1);
        $p_id = $product['products_id'];
/*		$list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => '',
                                               'text'  => $lc_text); */

 }
		
		
 $info_box_contents[$my_row][$my_col] = array('align' => 'center',
                                           'params' => ' style=" width:50%;"',
                                           'text' => '
			'.tep_draw_prod_top().'
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td>'.$p_pic.'</td>
							<td>'.tep_draw_separator('spacer.gif', '16', '1').'</td>
							<td width="100%">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr><td height="21" style="vertical-align:middle;">'.$p_name.'</td></tr>
									<tr><td height="41" style="vertical-align:middle;">'.$p_desc.'</td></tr>
									<tr><td height="23" style="vertical-align:middle;">'.$p_price.'</td></tr>
								</table>
							</td>
						</tr>
					</table>
				</td></tr>
				<tr><td height="13"></td></tr>
				<tr><td align="right"><a href="' . tep_href_link('product_info2.php?products_id='.$p_id) . '">'.tep_image_button("button_details.gif").'</a><br style="line-height:1px;"><br style="line-height:5px;"><a href="'.tep_href_link("products_new2.php","action=buy_now&products_id=".$p_id).'">'.tep_image_button('button_add_to_cart1.gif').'</a></td></tr>
				<tr><td height="3"></td></tr>
			</table>

			'.tep_draw_prod_bottom());

    $my_col ++;
    if ($my_col > 1) {
      $my_col = 0;
 	$my_row ++;
      }
    }

new contentBox($info_box_contents); 
//    new productListingBox($list_box_contents);
 } else {  ?>
 
<br style="line-height:11px;">

<?php  /*  echo tep_draw_infoBox_top();  */ ?>


				<table cellpadding="0" cellspacing="0" class="product">
					<tr><td class="padd_22"><?php echo TEXT_NO_PRODUCTS ?></td></tr>
				</table>


<br style="line-height:1px;"><br style="line-height:10px;">					
<?php
	
 /*  echo tep_draw_infoBox_bottom();  */
			
			
  }
  if ( ($listing_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3')) ) {
?>

<?php echo tep_draw_result_bottom(); ?>
<?php echo tep_draw_result_top_2(); ?>
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="result">
  <tr>
    <td><?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
    <td class="result_right" align="right"><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
  </tr>
</table>
<?php echo tep_draw_result_bottom_2(); ?>
<?php
  }
?>
