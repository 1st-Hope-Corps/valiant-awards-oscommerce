<?php
/*
  $Id: header.php,v 1.42 2003/06/10 18:20:38 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

// check if the 'install' directory exists, and warn of its existence
  if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/install')) {
      $messageStack->add('header', WARNING_INSTALL_DIRECTORY_EXISTS, 'warning');
    }
  }

// check if the configure.php file is writeable
  if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) && (is_writeable(dirname($HTTP_SERVER_VARS['SCRIPT_FILENAME']) . '/includes/configure.php')) ) {
      $messageStack->add('header', WARNING_CONFIG_FILE_WRITEABLE, 'warning');
    }
  }

// check if the session folder is writeable
  if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (STORE_SESSIONS == '') {
      if (!is_dir(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NON_EXISTENT, 'warning');
      } elseif (!is_writeable(tep_session_save_path())) {
        $messageStack->add('header', WARNING_SESSION_DIRECTORY_NOT_WRITEABLE, 'warning');
      }
    }
  }

// check session.auto_start is disabled
  if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
      $messageStack->add('header', WARNING_SESSION_AUTO_START, 'warning');
    }
  }

  if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(DIR_FS_DOWNLOAD)) {
      $messageStack->add('header', WARNING_DOWNLOAD_DIRECTORY_NON_EXISTENT, 'warning');
    }
  }

  if ($messageStack->size('header') > 0) {
    echo $messageStack->output('header');
  }
?>

<style type="text/css">
table.Header{
	background-image: url(/images/header_gradient.gif);
	background-repeat: repeat-x; 

	width:660px;
	color:#283F4B;
	background-color:#b1c8d4;
}
td.MenuTab{
	font-family:verdana;
	font-size:8pt;
	font-weight:bold;
}
td.MenuTabOver{
	font-family:verdana;
	font-size:8pt;
	font-weight:bold;
	background-color:#bed6e3;
}
a.Tab{
	text-decoration:none;
	color:#283F4B;
}
a.Tab:hover{
	text-decoration:none;
	color:#283F4B;
}
</style>

<table border="0" cellspacing="0" cellpadding="0" class="Header" align="center">
	<tr>
		<td style="font-family:times; font-size:26pt; padding-left:5px; padding-bottom:20px;">myGizmoz</td>
	</tr>
	<tr>
		<td>
			<table border="0" cellspacing="2" cellpadding="6">
				<tr>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com">Main</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/invite">Invite</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/profiles">My Page</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/profiles/members">Members</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/events">Events</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/forum">Forum</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/photo">Photo</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/video">Video</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/product">Products</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/store">Mall/Store</a></td>
					<td class="MenuTab" onmouseover="this.className='MenuTabOver';" onmouseout="this.className='MenuTab';"><a class="Tab" href="http://mygizmoz.ning.com/chat">Chat</a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<!-- start -->
 <table cellspacing=0 cellpadding=0 width=647 align=center style="width:647px;">
  <tr><td height=21></td></tr>

  <tr><td valign=top style=" border-right:8px solid #ffffff;">
   <table cellspacing=0 cellpadding=0>
    <tr><td><a href="<?php echo tep_href_link('index.php')?>"><?php echo tep_image(DIR_WS_IMAGES.'m01.gif')?></a></td>
    <td valign=top>
     <table cellspacing=0 cellpadding=0 border="0" style="width:1px;">
      <tr><td><img src=images/m02.gif width=403 height=8></td></tr>
      <tr><td class=ch1>
       <table cellspacing=0 cellpadding=0>
        <tr><td height=4></td></tr>

        <tr><td width=118 class=ch2>
         <table cellspacing=0 cellpadding=0>
          <tr><td class=ch3><?php echo BOX_HEADING_LANGUAGES?>:</td></tr>
          <tr><td height=2></td></tr>
          <tr><td><?php
 if (!isset($lng) || (isset($lng) && !is_object($lng))) {
 include(DIR_WS_CLASSES . 'language.php');
 $lng = new language;
 }
 $languages_string = '';
 reset($lng->catalog_languages);
 while (list($key, $value) = each($lng->catalog_languages)) {
 $languages_string .= '<a href="' . tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image(DIR_WS_LANGUAGES . $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a>&nbsp;&nbsp;';
 }
 echo $languages_string;
?></td></tr> 
         <tr><td height=9></td></tr>

          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m08.gif')?></td></tr>
          <tr><td height=6></td></tr>
          <tr><td class=ch3><?php echo BOX_HEADING_CURRENCIES?>:</td></tr>
          <tr><td height=6></td></tr>
          <tr><td><?php
    echo tep_draw_form('currencies', tep_href_link(basename($PHP_SELF), '', $request_type, false), 'get');
    reset($currencies->currencies);
    $currencies_array = array();
    while (list($key, $value) = each($currencies->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }
    $hidden_get_variables = '';
    reset($HTTP_GET_VARS);
    while (list($key, $value) = each($HTTP_GET_VARS)) {
      if ( ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y') ) {
        $hidden_get_variables .= tep_draw_hidden_field($key, $value);
      }
    }
    echo tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onChange="this.form.submit();" style="width:100px; font-size: 9px"') . $hidden_get_variables . tep_hide_session_id();
    echo '</form>';
?></td></tr>
         </table>

        </td>
        <td background=images/m09.gif width=1></td>
        <td width=116 class=ch2 valign=top>
         <table cellspacing=0 cellpadding=0>
          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m10.gif','','','',' align=absmiddle')?> &nbsp;<a class=ch3 href="<?php echo tep_href_link('specials.php')?>"><?php echo BOX_HEADING_SPECIALS?></a></td></tr>
          <tr><td height=5></td></tr>
          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m10.gif','','','',' align=absmiddle')?> &nbsp;<a class=ch3 href="<?php echo tep_href_link('advanced_search.php')?>"><?php echo BOX_SEARCH_ADVANCED_SEARCH?></a></td></tr>

          <tr><td height=5></td></tr>
          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m10.gif','','','',' align=absmiddle')?> &nbsp;<a class=ch3 href="<?php echo tep_href_link('reviews.php')?>"><?php echo BOX_HEADING_REVIEWS?></a></td></tr>
          <tr><td height=5></td></tr>
          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m10.gif','','','',' align=absmiddle')?> &nbsp;<?php if (tep_session_is_registered('customer_id')) { 
?><a class=ch3 href="<?php echo tep_href_link('account.php')?>"><?php echo HEADER_TITLE_MY_ACCOUNT?></a><?php } else 
{ ?><a class=ch3 href="<?php echo tep_href_link('create_account.php')?>"><?php echo HEADER_TITLE_CREATE_ACCOUNT?></a><?php } 
?></td></tr>
          <tr><td height=5></td></tr>
          <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m10.gif','','','',' align=absmiddle')?> &nbsp;<?php if (tep_session_is_registered('customer_id')) { 
?><a class=ch3 href="<?php echo tep_href_link('logoff.php')?>"><?php echo HEADER_TITLE_LOGOFF?></a><?php } else 
{ ?><a class=ch3 href="<?php echo tep_href_link('login.php')?>"><?php echo HEADER_TITLE_LOGIN?></a><?php } 
?></td></tr>
         </table>        </td>
        <td background=images/m09.gif width=1></td>
        <td class=ch2><table cellspacing=0 cellpadding=0>
          <tr>
            <td><a href="<?php echo tep_href_link('shopping_cart.php')?>">
              <?php echo tep_image(DIR_WS_IMAGES.'m11.gif')?>
            </a></td>
            <td class=ch4><br style="line-height:1px;" />
                <br style="line-height:5px;" />&nbsp;<b><?php echo BOX_HEADING_SHOPPING_CART?></b></td>
          </tr>
          <tr>
            <td colspan=2 class=ch4><?php echo tep_draw_separator('spacer.gif', '45', '1'); ?><a class=ch5 href="<?php echo tep_href_link('shopping_cart.php')?>">
              <?php echo $cart->count_contents()?>
              <?php echo BOX_SHOPPING_CART_EMPTY?>
            </a></td>
          </tr>
        </table></td>
        </tr>
       </table>
      </td></tr>
      <tr><td><?php echo tep_image(DIR_WS_IMAGES.'m03.gif')?></td></tr>
     </table>

    </td></tr>
   </table>
  </td></tr>
  <tr><td height=10></td></tr>
  <tr><td  width=647 height=48 valign=top style="border-right:0px solid #ffffff;background:url(images/m12.gif) left top no-repeat;">
   <table cellspacing=0 cellpadding=0>
    <tr><td height=7></td></tr>
    <tr><td width=13></td>
    <td valign=top><?php echo tep_draw_form('search',tep_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', 'NONSSL', false),'get') ?>
              <table cellspacing=0 cellpadding=0 style="width:172px;">
			  <tr><td height=6 colspan="4"></td></tr>
              <tr><td width="100%" align="center">&nbsp;<input type=text name="keywords" class=se2 value=""></td><td><?php echo tep_draw_separator('spacer.gif', '5', '1'); ?></td><td><?php echo tep_image_submit('button_search_prod.gif')?></td></tr>
              </table></form>


    </td>

    <td width=5></td>
    <td><a href="<?php echo tep_href_link('index.php')?>"><?php echo tep_image_button('b01.gif')?></a></td>
    <td><a href="<?php echo tep_href_link('products_new.php')?>"><?php echo tep_image_button('b02.gif')?></a></td>
    <td><a href="<?php echo tep_href_link('account.php')?>"><?php echo tep_image_button('b03.gif')?></a></td>
    <td><a href="<?php echo tep_href_link('shopping_cart.php')?>"><?php echo tep_image_button('b04.gif')?></a></td>
    <td><a href="<?php echo tep_href_link('contact_us.php')?>"><?php echo tep_image_button('b05.gif')?></a></td><td width="100%"><?php echo tep_draw_separator('spacer.gif', '1', '1'); ?></td>
    </tr>
   </table>
   <table cellpadding="0" cellspacing="0" border="0"><tr><td height="14"><?php echo tep_draw_separator('spacer.gif', '1', '1'); ?></td></tr></table>
<!-- end -->
<?php
  if (isset($HTTP_GET_VARS['error_message']) && tep_not_null($HTTP_GET_VARS['error_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerError">
    <td class="headerError"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['error_message']))); ?></td>
  </tr>
</table>
<?php
  }

  if (isset($HTTP_GET_VARS['info_message']) && tep_not_null($HTTP_GET_VARS['info_message'])) {
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr class="headerInfo">
    <td class="headerInfo"><?php echo htmlspecialchars(stripslashes(urldecode($HTTP_GET_VARS['info_message']))); ?></td>
  </tr>
</table>
<?php
  }
?>
<!-- start -->
</td></tr><tr><td>

<!-- end -->

<?php 
define(MAX_DESCR_1,'28');
define(MAX_DESCR_BESTS,'19');
define(MAX_DESCR_REVIEWS,'59');
?>