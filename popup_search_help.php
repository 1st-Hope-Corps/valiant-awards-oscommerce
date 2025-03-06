<?php
/*
  $Id: popup_search_help.php,v 1.4 2003/06/05 23:26:23 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_ADVANCED_SEARCH);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
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
				<tr><td class="cont_header_txt" style="background:url(images/m36.gif) left top no-repeat; border:0px;"><b><?php echo HEADING_SEARCH_HELP?></b></td></tr>
			</table>
	 </td></tr>
   <tr><td colspan=2 height=100%>
   	<table cellpadding="0" cellspacing="0" border="0" style=" width:339px;">
		<tr><td  class=ch7>
			<table cellspacing=0 cellpadding=0 border="0" align=center style=" width:295px;">
			 <tr><td height=14></td></tr>
			 <tr><td><br style="line-height:1px;"><br style="line-height:5px;"><?php echo TEXT_SEARCH_HELP?><br style="line-height:1px;"><br style="line-height:15px;">
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
</body>
</html>
<?php require('includes/application_bottom.php'); ?>
