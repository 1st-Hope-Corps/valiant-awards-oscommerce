<?php
/*
  $Id: also_purchased_products.php,v 1.21 2003/02/12 23:55:58 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  if (isset($HTTP_GET_VARS['products_id'])) {
    $orders_query = tep_db_query("select p.products_id, p.products_image from " . TABLE_ORDERS_PRODUCTS . " opa, " . TABLE_ORDERS_PRODUCTS . " opb, " . TABLE_ORDERS . " o, " . TABLE_PRODUCTS . " p where opa.products_id = '" . (int)$HTTP_GET_VARS['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$HTTP_GET_VARS['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and p.products_status = '1' group by p.products_id order by o.date_purchased desc limit " . MAX_DISPLAY_ALSO_PURCHASED);
    $num_products_ordered = tep_db_num_rows($orders_query);
    if ($num_products_ordered >= MIN_DISPLAY_ALSO_PURCHASED) {
?>

<?php tep_draw_separate(); ?>
<? /*  tep_draw_heading_top();  */ ?>
<!-- also_purchased_products //-->
<?php
      $info_box_contents = array();
      $info_box_contents[] = array('text' => TEXT_ALSO_PURCHASED_PRODUCTS);

      new contentBoxHeading($info_box_contents);

/*  tep_draw_separate();  */

	tep_draw_heading_top_1();
			 


      $row = 0;
      $col = 0;
      $info_box_contents = array();
      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_name'] = tep_get_products_name($orders['products_id']);
        $info_box_contents[$row][$col] = array('align' => 'center',
                                               'params' => ' style="width:50%;"',
                                               'text' => ''.tep_draw_prod_top().'<br style="line-height:1px;"><br style="line-height:5px;">
											   
											  <table cellpadding="0" cellspacing="0" border="0">
											  		<tr><td align="center">
													<span><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . $orders['products_name']. '</a></span>
													<br style="line-height:1px;"><br style="line-height:15px;"><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $orders['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $orders['products_image'], $orders['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td></tr>
											  </table> 
											   '.tep_draw_prod_bottom());

        $col ++;
        if ($col > 1) {
          $col = 0;
          $row ++;
        }
      }

      new contentBox($info_box_contents); 
?>

<? tep_draw_heading_bottom_1(); ?>

<?php /*  tep_draw_heading_bottom();  */?>	

<?php tep_draw_separate(); ?>
<!-- also_purchased_products_eof //-->
<?php
    }
  }
?>
