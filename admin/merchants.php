<?php
require('includes/application_top.php');
?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
	<title><?php echo TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
	<script language="javascript" src="includes/general.js"></script>
</head>

<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">

<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
	<tr>
	    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
			<table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
				<!-- left_navigation //-->
				<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
				<!-- left_navigation_eof //-->
			</table>
		</td>
		<td width="100%" valign="top">
			<!-- body_text //-->
			<table style="width:100%;" cellspacing="0" cellpadding="0">
				<tr>
					<td>
						<?php echo tep_draw_form('search', 'merchants.php', '', 'get'); ?>
						<?php
						echo tep_hide_session_id();
						if (isset($_GET["cID"])) echo tep_draw_hidden_field("cID", $_GET["cID"]);
						if (isset($_GET["product_page"])) echo tep_draw_hidden_field("product_page", $_GET["product_page"]);
						echo tep_draw_hidden_field("selected_box", "merchants");
						?>
							<table border="0" width="100%" cellspacing="0" cellpadding="0">
								<tr>
									<td class="pageHeading">
										<?php echo (!isset($_GET["cID"])) ? "Merchants":"Merchant's Products" ?>
									</td>
									<td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, 40); ?></td>
									<td class="smallText" align="right"><?php echo 'Search ' . tep_draw_input_field('search'); ?></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
				<tr>
					<td>
						<table style="width:80%;" cellspacing="0" cellpadding="2">
							<tr class="dataTableHeadingRow">
								<td class="dataTableHeadingContent">Last Name</td>
								<td class="dataTableHeadingContent">First Name</td>
								<td class="dataTableHeadingContent" style="text-align:center;">Product Count</td>
								<td class="dataTableHeadingContent" style="text-align:right;">Action</td>
							</tr>
							<?php
							$search = "";
							
							if (isset($_GET['search']) && tep_not_null($_GET['search']) && !isset($_GET["cID"])){
								$keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
								$search = "where customers_lastname like '%" . $keywords . "%' or customers_firstname like '%" . $keywords . "%' or customers_email_address like '%" . $keywords . "%'";
							}
							
							if (isset($_GET["cID"]) && tep_not_null($_GET['cID'])){
								$search = "where customers_id = ".$_GET["cID"];
							}
							
							$merchants_query_raw = "select customers_id, ning_id, customers_lastname, customers_firstname from customers ".$search." order by customers_lastname, customers_firstname";
							
							$merchants_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $merchants_query_raw, $merchants_query_numrows);
							$merchants_query = tep_db_query($merchants_split->SQL_QUERY);
							
							while ($merchants = tep_db_fetch_array($merchants_query)){
								$ning_id = $merchants["ning_id"];
								$products_query_raw = "select count(products_id) AS iProdCount from products where ning_id = '".$ning_id."'";
								$products_query = tep_db_query($products_query_raw);
								$products = tep_db_fetch_array($products_query);
								?>
								
								<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" title="Click to View Products Uploaded by this Merchant." onclick="document.location.href='<?php echo tep_href_link('merchants.php', tep_get_all_get_params(array('cID','page')) . 'product_page=1&cID=' . $merchants['customers_id']) ?>'">
									<td class="dataTableContent"><?php echo $merchants['customers_lastname'] ?></td>
									<td class="dataTableContent"><?php echo $merchants['customers_firstname'] ?></td>
									<td class="dataTableContent" style="text-align:center;"><?php echo $products['iProdCount'] ?></td>
									<td class="dataTableContent" style="text-align:right;"><a href="<?php echo tep_href_link('merchants.php', tep_get_all_get_params(array('cID','page','search','product_page')) . 'product_page=1&cID=' . $merchants['customers_id']) ?>" title="View Products">View Products</a></td>
								</tr>
								
								<?php
							}
							
							if (isset($_GET["cID"]) && tep_not_null($_GET['cID'])){
								$search = "";
								
								if (isset($_GET['search']) && tep_not_null($_GET['search'])){
									$keywords = tep_db_input(tep_db_prepare_input($_GET['search']));
									$search = "and (B.products_name like '%" . $keywords . "%' or B.products_description like '%" . $keywords . "%')";
								}
								
								$product_query_raw = "select A.products_id, A.ning_id, A.products_image, A.products_price, A.products_date_added, B.products_name, B.products_description from products A inner join products_description B on B.products_id = A.products_id where A.ning_id = '".$ning_id."' and B.language_id = 1 ".$search." order by A.products_date_added DESC";
								
								$product_split = new splitPageResults($_GET['product_page'], MAX_DISPLAY_SEARCH_RESULTS, $product_query_raw, $product_query_numrows);
								$product_query = tep_db_query($product_split->SQL_QUERY);
								?>
								
								<tr>
									<td colspan="4">
										<table style="width:100%; padding-top:10px;" cellspacing="0" cellpadding="2">
											<tr class="dataTableHeadingRow">
												<td class="dataTableHeadingContent" style="width:120px;">Image</td>
												<td class="dataTableHeadingContent" style="width:70%;">Product Name</td>
												<td class="dataTableHeadingContent" style="text-align:right;">Price</td>
												<td class="dataTableHeadingContent" style="text-align:right;">Date Added</td>
											<tr>
											
											<?php
											while ($product = tep_db_fetch_array($product_query)){
												?>
												
												<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
													<td class="dataTableContent" style=" vertical-align:top;"><img src="<?php echo DIR_WS_CATALOG_IMAGES.$product["products_image"] ?>" style="width:100px;" /></td>
													<td class="dataTableContent" style="vertical-align:top;">
														<?php echo "<strong style=\"font-size:1.2em;\">".$product["products_name"]."</strong><br />".$product["products_description"] ?>
													</td>
													<td class="dataTableContent" style="text-align:right; vertical-align:top;">$ <?php echo number_format($product["products_price"], 2, ".", ",") ?></td>
													<td class="dataTableContent" style="text-align:right; vertical-align:top;"><?php echo tep_date_short($product["products_date_added"]) ?></td>
												</tr>
												<tr class="dataTableRow">
													<td colspan="4" style="height:10px;"></td>
												</tr>
												
												<?php
											}
											?>
											
										</table>
									</td>
								</tr>
								
								<?php
							}
							?>
							<tr>
								<td colspan="4">
									<table style="width:100%;" cellspacing="0" cellpadding="0">
										<td class="smallText" valign="top">
											<?php
											if (isset($_GET["cID"]) && tep_not_null($_GET['cID'])){
												echo $product_split->display_count($product_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['product_page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS);
											}else{
												echo $merchants_split->display_count($merchants_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS);
											}
											?>
										</td>
										<td class="smallText" align="right">
											<?php
											if (isset($_GET["cID"]) && tep_not_null($_GET['cID'])){
												echo $product_split->display_links($product_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['product_page'], tep_get_all_get_params(array('page', 'product_page', 'info', 'x', 'y')), 'product_page');
											}else{
												echo $merchants_split->display_links($merchants_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y')));
											}
											?>
										</td>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- body_text_eof //-->
		</td>
	</tr>
</table>

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php') ?>
<!-- footer_eof //-->

</body>

<?php require(DIR_WS_INCLUDES . 'application_bottom.php') ?>