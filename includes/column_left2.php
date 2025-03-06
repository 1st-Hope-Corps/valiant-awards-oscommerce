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
    include(DIR_WS_BOXES . 'categories2.php');
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
		<!--<table cellpadding="0" cellspacing="0" border="0">
			<tr><td><a href="<?php echo tep_href_link('index.php?cPath=3')?>"><?php echo tep_image(DIR_WS_IMAGES.'m18.gif')?></a></td></tr>
			<tr><td height=3></td></tr>
			<tr><td><a href="<?php echo tep_href_link('index.php?cPath=5')?>"><?php echo tep_image(DIR_WS_IMAGES.'m19.gif')?></a></td></tr>
		</table>-->
		
		<div style="margin-top:5px;">
			<a href="<?php echo tep_href_link('index.php?cPath=5')?>"><?php echo tep_image(DIR_WS_IMAGES.'m19.gif')?></a>
		</div>
		
		<div style="border:1px solid #3ECA26; width:184px; color:#3ECA26; padding:0 8px 0px 10px; margin-top:5px;">
			<?php
			if ($_SESSION["sStoreType"] == "store"){
				?>
				<p><b>This service will become available once you are eligible.</b></p>
				
				<p>The HopeNet My eStore is your personal online store where you can sell digital goods such as eBooks, artwork, music, 
				and videos that you have created. You cannot sell real products through your My eStore.</p>
				
				<p>To get started, you need to first create some digital products and then upload them to your eStore. To upload your 
				digital products click on the “Upload Product” button on this page, and then carefully follow the instructions.</p>
				
				<p>Once your digital products are uploaded you should then go back to your eStore and verify that your products are 
				being displayed properly.  You may also want to do some test purchases in your store using your Hope Bucks to make 
				sure that everything is working correctly.</p>
				
				<p>When a customer purchases your products the Hope Bucks will immediately appear in your My eBank account, and you 
				can also login to your control panel to see how many products have been purchased, who purchased them and when were 
				they purchased.</p>
				<?php
			}else{
				?>
				<p>The HopeNet eCommissary is an online store where you can buy both digital goods such as eBooks and artwork and real 
				products such as school and computer supplies.</p>
				
				<p>To get started, just click on the Commissary link below the Categories header in the left hand column of this page. 
				You will then see two options to choose from – Digital Products or Real Products. Click on the link of your choice, such 
				as Digital Products  and you will see a list of sub categories. Click on the sub category of your choice such as Artwork 
				and you will see a page of the available products in the primary display panel in the center column of this page.</p>
				
				<p>To learn more about a specific product just click on the “Details” button, and if you want to buy the product click on 
				"Add to Cart". Next - click on the "Click to Buy" button and then follow the instructions. The final step is to verify 
				your bank account and other info and to click on “Confirm your Order” button. You will then see a page that says: 
				"Your Order has Been Processed" and on the page you will see a link to download your digital artwork. You can download 
				the product immediately or if you decide to wait you will have 3 days until the link expires. You are given 5 tries to 
				download the product just in case there is a problem.</p>
				
				<p>If you are purchasing a Real Product you follow the same basic steps as above but you must pick up your product from 
				the Hope Cybrary on the date specified on the "Your Order has Been Processed" page.</p>
				<?php
			}
			?>
		</div>
	</td>
	<td><?php echo tep_image(DIR_WS_IMAGES.'part_l.gif')?></td></tr>
</table>
