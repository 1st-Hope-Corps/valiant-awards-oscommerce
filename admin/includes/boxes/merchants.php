<?php
/*
  $Id: merchants.php 1304 2008-10-15 $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- merchants //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => "Merchants",
                     'link'  => tep_href_link("merchants.php", 'selected_box=merchants'));

  if ($selected_box == 'merchants') {
    $contents[] = array();
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- merchants_eof //-->
