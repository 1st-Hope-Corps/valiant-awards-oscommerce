<?php
/*
  $Id: footer.php,v 1.26 2003/02/10 22:30:54 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require(DIR_WS_INCLUDES . 'counter.php');
?>
  </td></tr>

  <tr><td height=6></td></tr>
  <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m32.gif')?></td></tr>
  <tr><td height=6></td></tr>
  <tr><td width=646>
   <table cellspacing=0 cellpadding=0 class="footer">
    <tr><td width=200></td>
    <td width=446 align=right class=ch12><?php echo FOOTER_TEXT_BODY?>&nbsp;&nbsp;</td></tr>

   </table>
  </td></tr>
  <tr><td height=42></td></tr>
 </table>

<?php
  if ($banner = tep_banner_exists('dynamic', '468x50')) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><?php /*  echo tep_display_banner('static', $banner);  */ ?></td>
  </tr>
</table>
<?php
  }
?>
