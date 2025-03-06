<?php
/*
  $Id: info_shopping_cart.php,v 1.19 2003/02/13 03:01:48 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_INFO_SHOPPING_CART);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<link rel=stylesheet href=stylesheet.css> 
</head>
<body topmargin=0 bottommargin=0 leftmargin=0 rightmargin=0 marginwidth=0 marginheight=0>

<table cellspacing=0 cellpadding=0 width=100% align=center><tr><td class=bg1 width=100% valign=top> 
  <table cellspacing=0 cellpadding=0  align=center border=0 style=" width:342px;">
   <tr><td width=240><?php echo tep_image(DIR_WS_IMAGES.'m34.gif')?></td>
   <td width=100>
    <table cellspacing=0 cellpadding=0 border=0 style=" width:100px;">
     <tr><td height=27></td></tr>
     <tr><td><a class=ch16  href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></td></tr>
    </table>
   </td></tr>
   <tr><td colspan=2><?php echo tep_image(DIR_WS_IMAGES.'m35.gif')?></td></tr>
   <tr><td colspan=2 width=339 height=2></td></tr>
   <tr><td colspan=2 width=339 height=29>
    
			<table border="0" cellspacing="0" cellpadding="0">
				<tr><td class="cont_header_txt" style="background:url(images/m36.gif) left top no-repeat; border:0px;"><b><?php echo HEADING_TITLE; ?></b></td></tr>
			</table>
	 </td></tr>
   <tr><td colspan=2 height=100%>
   	<table cellpadding="0" cellspacing="0" border="0" style=" width:339px;">
		<tr><td  class=ch7>
			<table cellspacing=0 cellpadding=0 border="0" align=center style=" width:295px;">
			 <tr><td height=14></td></tr>
			 <tr><td><br style="line-height:1px;"><br style="line-height:5px;">
			 <b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?><br style="line-height:1px;"><br style="line-height:15px;">
			 <b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?><br style="line-height:1px;"><br style="line-height:15px;">
			 <b><i><?php echo SUB_HEADING_TITLE_1; ?></i></b><br><?php echo SUB_HEADING_TEXT_1; ?><br style="line-height:1px;"><br style="line-height:15px;">
			 <div align="right"><a style=" color:#46484a" href="javascript:window.close();"><?php echo TEXT_CLOSE_WINDOW; ?></a></div><br style="line-height:1px;"><br style="line-height:15px;">         
			</td></tr>
		   </table>		
		</td></tr>
	</table>
   

		   
  </td></tr>
  <tr><td colspan=2><?php echo tep_image(DIR_WS_IMAGES.'m37.gif')?></td></tr>
  <tr><td height=9></td></tr>
  <tr><td colspan=2><?php echo tep_image(DIR_WS_IMAGES.'m35.gif')?></td></tr>
  </table></td></tr>
  <tr><td width=100% class=bg2 align=center>
  <table cellspacing=0 cellpadding=0 align=center style=" width:310px;" class="footer"><tr><td colspan=2 height=1></td></tr>
  <tr><td colspan=2 height=12></td></tr>
  <tr><td colspan=2 style=" text-align:center;"><?php echo FOOTER_TEXT_BODY?></td></tr>
  <tr><td colspan=2 width=339 height=25></td></tr>
 </table></tr></td></table>




</html>
<?php
  require("includes/counter.php");
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
