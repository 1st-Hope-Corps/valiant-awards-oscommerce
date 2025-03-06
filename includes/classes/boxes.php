<?php
/*
  $Id: boxes.php,v 1.33 2003/06/09 22:22:50 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {
    var $table_border = '0';
    var $table_width = '100%';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox($contents, $direct_output = false) {
      $tableBox_string = '<table style="display:none;" border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }
  // ------------------ infoBoxHeading ----------
  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      if ($left_corner == true) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="box_header_txt"',
                                         'text' => ''.$contents[0]['text'].''));

      $this->tableBox($info_box_contents, true);
    }
  }  
  
  // ------------------ infoBox ----------
  class infoBox extends tableBox {
    function infoBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = '  style="border-bottom:3px solid #ffffff;background:url(images/m17.gif) left bottom no-repeat;padding-bottom:6px;"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = ' style="border:1px solid #b6b6b6; border-width:0px 1px 0px 1px;" class="box_body"';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(  array (  'align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => ' style="border:1px solid #ffffff; border-width:5px 7px 4px 7px;"',
                                           'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
      }

      return $this->tableBox($info_box_contents);
    }
  }
  // ------------------ infoBoxHeading1 ----------
  class infoBoxHeading1 extends tableBox {
    function infoBoxHeading1($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';

      if ($left_corner == true) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="box_header_txt1"',
                                         'text' => ''.$contents[0]['text'].''));

      $this->tableBox($info_box_contents, true);
    }
  } 
  // ------------------ infoBox1 ----------
  class infoBox1 extends tableBox {
    function infoBox1($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = '  style="border-bottom:8px solid #ffffff;"';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = ' style="background:#F4F6DE;border:1px solid #F4F6DE; border-width:0px 11px 15px 11px;" class="box_body"';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(  array (  'align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => '',
                                           'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
      }

      return $this->tableBox($info_box_contents);
    }
  }
  // ------------------ infoBox2 ----------
  class infoBox2 extends tableBox {
    function infoBox2($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(  array (  'align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => '',
                                           'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
      }

      return $this->tableBox($info_box_contents);
    }
  }
// -------------------------------------- infoBoxHeading0 ----------
  class infoBoxHeading0 extends tableBox {
    function infoBoxHeading1($contents, $left_corner = true, $right_corner = true, $right_arrow = false) {
      $this->table_cellpadding = '0';
      $this->table_parameters = ' class="box_header_txt"';
      if ($left_corner == true) {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_left.gif');
      } else {
        $left_corner = tep_image(DIR_WS_IMAGES . 'infobox/corner_right_left.gif');
      }
      if ($right_arrow == true) {
        $right_arrow = '<a href="' . $right_arrow . '">' . tep_image(DIR_WS_IMAGES . 'infobox/arrow_right.gif', ICON_ARROW_RIGHT) . '</a>';
      } else {
        $right_arrow = '';
      }
      if ($right_corner == true) {
        $right_corner = $right_arrow . tep_image(DIR_WS_IMAGES . 'infobox/corner_right.gif');
      } else {
        $right_corner = $right_arrow . tep_draw_separator('pixel_trans.gif', '11', '14');
      }

      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => '',
                                         'text' => ''.$contents[0]['text'].''));
      $this->tableBox($info_box_contents, true);
    }
  } 
// -------------------------------------- infoBox0 ----------
  class infoBox0 extends tableBox {
    function infoBox0($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = ' style="margin-bottom:0px;" class="box_body"';
      $info_box_contents = array();
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(  array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                             'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                             'params' => ' style="background:#FCCF29;"',
											 'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
										 
								
      }

      return $this->tableBox($info_box_contents);
    }
  }
  //----------------------------------------------- tableBox_output ----------------------------------
 class tableBox_output {
    var $table_border = '0';
    var $table_width = '';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = ' ';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox_output($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= '' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        
		 
		 if ($i != 0) { $tableBox_string .= '
			<tr><td class="bg_gg" colspan="'.(($x+$x)-1).'">'.tep_draw_separator('spacer.gif', '1', '19').'</td></tr>
			 
			';} 
					  
		$tableBox_string .= '   <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
			
                
				
				
				if ($x >= 1){
				
					if($i == 0) {$tableBox_string .= '
													<td class="bg_vv">'.tep_draw_separator('spacer.gif', '14', '1').'</td>
													';}
					else		{ 
				
				
				
				  $tableBox_string .= '				<td class="bg_vv">'.tep_draw_separator('spacer.gif', '14', '1').'</td>';} 
											}

				 
			  $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>  ' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr> 	' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

//---------------- tableBox_output --------------------- 
  class contentBox extends tableBox_output {
    function contentBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = ' class="box_width_cont product"';
      $this->tableBox_output($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = ' class="tableBox_output"';
      return $this->tableBox_output($contents);
    }
  }
// ------------------ contentBoxHeading ---------------------------------------------------------
  class contentBoxHeading extends tableBox {
    function contentBoxHeading($contents) {
	
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();
      $info_box_contents[] = array( array('params' => ' class="cont_header_txt"',
                                         'text' => ''.$contents[0]['text'].''));

      $this->tableBox($info_box_contents, true);
    }
  }

//---------------- contentBoxHeading_WHATS_NEW --------------------- 
  class contentBoxHeading_WHATS_NEW extends tableBox {
    function contentBoxHeading_WHATS_NEW($contents) {

      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="cont_header_txt"',
                                         'text' => ''.BOX_HEADING_WHATS_NEW.''));

      $this->tableBox($info_box_contents, true);
    }
  }
//---------------- contentBoxHeading_ProdNew --------------------- 
  class contentBoxHeading_ProdNew extends tableBox {
    function contentBoxHeading_ProdNew($contents) {

      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => ' class="cont_header_txt"',
                                         'text' => ''.HEADING_TITLE.''));

      $this->tableBox($info_box_contents, true);
    }
  }

// ------------------------------------------------------------ 
  function tep_draw_title_top()
  {
  return $table = '
			<table cellpadding="0" cellspacing="0" border="0">
				<tr><td style="width:100%" class="cont_header_txt">
  ';
  }
// -------------------------------------- --------------------------
  function  tep_draw_title_bottom()
  {
   return $table =  '
					</td></tr>
			</table>									
   ';
  }
// ------------------------------------------------------------  
// ------------------------------------------------------------
  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }


//---------------- tableBox_shopping_cart --------------------- 
class tableBox_shopping_cart {
    var $table_border = '0';
    var $table_width = '';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = '';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox_shopping_cart($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
       if ($i >= 1) { $tableBox_string .= '
	   

						<tr><td style="background:#ffffff;height:3px;" colspan="7">'.tep_draw_separator('spacer.gif', '1', '1').'</td></tr>							  
							  ';}
	   
	    $tableBox_string .= ' <tr';
		
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              if ($x >= 1) $tableBox_string .= '<td style="background:#ffffff;">'.tep_draw_separator('spacer.gif', '3', '1').'</td>';
			  
 			  $tableBox_string .= '     <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
			  
			    if ($i == 0)  $tableBox_string .= tep_draw_shop_top();
				else $tableBox_string .= tep_draw_shop_top();
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
			  if ($i == 0)  $tableBox_string .= tep_draw_shop_bottom();
			  else $tableBox_string .= tep_draw_shop_bottom();
			  
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>'.tep_draw_shop_top_1() . $contents[$i]['text'] . tep_draw_shop_bottom_1().'</td>' . "\n";
        }

        $tableBox_string .= '  </tr>' . "\n";
		
/*  if ($i >= 2) {  */
 $tableBox_string .= '
										 
 ';
 /*  }  */		
		
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }
  class productListingBox extends tableBox_shopping_cart {
    function productListingBox($contents) {
      $this->table_parameters = ' style=" background:#f1f1f1" class="box_width_cont product"';
      $this->tableBox_shopping_cart($contents, true);
    }
  }
  
// -------------------------------------- --------------------------  
  function tep_draw_heading_top()
  {
 			 require(DIR_WS_BOXES . 'panel_top.php'); 
  echo ' ';
  }
// -------------------------------------- --------------------------  
  function tep_draw_heading_top_()
  {
 			
  echo ' ';
  }
// -------------------------------------- --------------------------  
function tep_draw_heading_bottom()
{

echo '';
}
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------  
  function tep_draw_heading_top_1()
  {
  echo '
				<table cellpadding="0" cellspacing="0" border="0">
					<tr><td style="border-right:4px solid #ffffff;">
							
						
						<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid #b6b6b6;border-width:0px 1px 0px 1px;">
							<tr><td style="border:0px solid #ffffff;border-width:10px 7px 10px 7px;">
  ';
 			
  }
// -------------------------------------- --------------------------  
function tep_draw_heading_bottom_1()
{

echo ' 
								
							</td></tr>
						</table>
					</td></tr>
					<tr><td>'.tep_image(DIR_WS_IMAGES.'m30.gif').'</td></tr>
				</table>
';
}
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------   
  function tep_draw_heading_top_2()
  {
 			
  echo '
<table cellpadding="0" cellspacing="0" border="0">  
	<tr><td>'.tep_draw_separator('spacer.gif', '15', '1').'</td>
		<td width="100%">

 ';
  }
// -------------------------------------- --------------------------  
function tep_draw_heading_bottom_2()
{

echo '
	
	</td><td>'.tep_draw_separator('spacer.gif', '25', '1').'</td></tr>
</table> ';
} 
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------  
  function tep_draw_heading_top_3()
  {
  echo '
				<table cellpadding="0" cellspacing="0" border="0">
					<tr><td style="border-right:4px solid #ffffff;">
							
						
						<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid #b6b6b6;border-width:0px 1px 0px 1px;">
							<tr><td style="border:0px solid #ffffff;border-width:12px 7px 12px 7px;">
  ';
 			
  }
// -------------------------------------- --------------------------  
function tep_draw_heading_bottom_3()
{

echo ' 
								
							</td></tr>
						</table>
					</td></tr>
					<tr><td>'.tep_image(DIR_WS_IMAGES.'m30.gif').'</td></tr>
				</table>
';
}
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------  
  function tep_draw_heading_top_4()
  {
  echo '
				<table cellpadding="0" cellspacing="0" border="0">
					<tr><td style="border-right:4px solid #ffffff;">
							
						
						<table cellpadding="0" cellspacing="0" border="0" style="border:1px solid #b6b6b6;border-width:0px 1px 0px 1px;">
							<tr><td style="border:0px solid #ffffff;border-width:0px 0px 0px 0px;">
  ';
 			
  }
// -------------------------------------- --------------------------  
function tep_draw_heading_bottom_4()
{

echo ' 
								
							</td></tr>
						</table>
					</td></tr>
					<tr><td>'.tep_image(DIR_WS_IMAGES.'m303.gif').'</td></tr>
				</table>
';
}
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------
  function  tep_draw_infoBox_top()
  {
  return $table = '
                    <table cellpadding="0" cellspacing="0" border="0" class="pic_2">
                        <tr><td class="image">';
  }
// -------------------------------------- --------------------------
  function  tep_draw_infoBox_bottom()
  {
   return $table = '</td>
                        </tr>
                    </table>
   ';
  }
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------  
  function tep_draw_result_top()
  {
  echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td class="bg_gg">'.tep_draw_separator('spacer.gif', '1', '10').'</td></tr></table>';
  }
// -------------------------------------- --------------------------  
function tep_draw_result_bottom()
	{
	echo '<table cellpadding="0" cellspacing="0" border="0"><tr><td class="bg_gg">'.tep_draw_separator('spacer.gif', '1', '20').'</td></tr></table>';
	}

// -------------------------------------- --------------------------
// -------------------------------------- --------------------------   
  function tep_draw_result_top_1()
  {
  echo '
       
	   ';
  }
// -------------------------------------- --------------------------  
function tep_draw_result_bottom_1()
	{
	echo '
        
		';
  }
// -------------------------------------- -------------------------- 
// -------------------------------------- --------------------------  
  function tep_draw_result_top_2()
  {
  echo '
       
	   ';
  }
// -------------------------------------- --------------------------  
function tep_draw_result_bottom_2()
	{
	echo '
        
		';
  }
// -------------------------------------- -------------------------- 
// -------------------------------------- --------------------------
  function  tep_draw_prod_pic_top()
  {
  return $table = '
                    <table cellpadding="0" cellspacing="0" border="0" class="pic table_pic_width">
                        <tr><td class="image">';
  }
// -------------------------------------- --------------------------
  function  tep_draw_prod_pic_bottom()
  {
   return $table = '</td>
                        </tr>
                    </table>
   ';
  }
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------
  function  tep_draw_prod_top()
  {
  return $table = '<table cellpadding="0" cellspacing="0" border="0" class="tep_draw_prod_top">
						<tr><td>
						';
  }
// -------------------------------------- --------------------------
  function  tep_draw_prod_bottom()
  {
   return $table = '</td></tr>
					</table>
   ';
  }
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------
  function  tep_draw_box_prod_top()
  {
  return $table = '<table cellpadding="0" cellspacing="0" border="0" class="pic table_pic_width">
									<tr><td class="image">';
  }
// -------------------------------------- --------------------------
  function  tep_draw_box_prod_bottom()
  {
   return $table = '</td>
                                    </tr>
                                </table>';
  }
// -------------------------------------- --------------------------

// -------------------------------------- --------------------------
  function  tep_draw_shop_top()
  {
  return $table = '';
  }
// -------------------------------------- --------------------------
  function  tep_draw_shop_bottom()
  {
   return $table =  '';
  }
  // -------------------------------------- --------------------------
  function  tep_draw_shop_top_1()
  {
  return $table = '';
  }
// -------------------------------------- --------------------------
  function  tep_draw_shop_bottom_1()
  {
   return $table =  '';
  }
// -------------------------------------- --------------------------  
// -------------------------------------- --------------------------  
function tep_draw_separate()
	{
	echo '
			<table cellpadding="0" cellspacing="0"><tr><td class="tep_draw_separate">'.tep_draw_separator('spacer.gif', '1', '1').'</td></tr></table>';
	}
// -------------------------------------- --------------------------

  	// ------------------ infoBox_77 ----------
	class infoBox_77 extends tableBox {
	function infoBox_77($contents) {
	  $info_box_contents = array();
	  $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
	  $this->table_cellpadding = '0';
	  $this->table_parameters = '';
	  $this->tableBox($info_box_contents, true);
	}
	
	function infoBoxContents($contents) {
	  $this->table_cellpadding = '0';
	  $this->table_parameters = '';
	  $info_box_contents = array();
	  for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
		$info_box_contents[] = array(  array (  'align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
										   'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
										   'params' => '',
										   'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
	  }
	
	  return $this->tableBox($info_box_contents);
	}
	}
	// ------------------ infoBox_78 ----------
	class infoBox_78 extends tableBox {
	function infoBox_78($contents) {
	  $info_box_contents = array();
	  $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
	  $this->table_cellpadding = '0';
	  $this->table_cellspacing = '15';
	  $this->table_parameters = '';
	  $this->tableBox($info_box_contents, true);
	}
	
	function infoBoxContents($contents) {
	  $this->table_cellpadding = '0';
	   $this->table_cellspacing = '0';
	  $this->table_parameters = '';
	  $info_box_contents = array();
	  for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
		$info_box_contents[] = array(  array (  'align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
										   'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
										   'params' => ' class="main"',
										   'text' => ''.(isset($contents[$i]['text']) ? $contents[$i]['text'] : '').''));
	  }
	
	  return $this->tableBox($info_box_contents);
	}
	}	
  //----------------------------------------------- tableBox_output1 ----------------------------------
 class tableBox_output1 {
    var $table_border = '0';
    var $table_width = '';
    var $table_cellspacing = '0';
    var $table_cellpadding = '0';
    var $table_parameters = ' ';
    var $table_row_parameters = '';
    var $table_data_parameters = '';

// class constructor
    function tableBox_output1($contents, $direct_output = false) {
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= '' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        
		 
		 if ($i != 0) { $tableBox_string .= '
		
					<tr><td colspan="'.(($x+$x)-1).'" class="bg_line_x">'.tep_draw_separator('spacer.gif', '1', '3').'</td></tr>			 
					 ';} 
					  
		$tableBox_string .= '   <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
			
                
				
				
				if ($x >= 1){
				
					if($i == 0) {$tableBox_string .= '
											<td class="bg_line_y">'.tep_draw_separator('spacer.gif', '23', '1').'</td>
											';}
					else		{ 
				
				
				
				  $tableBox_string .= '
											<td class="bg_line_y">'.tep_draw_separator('spacer.gif', '23', '1').'</td>
											';} 
											}
				 
			  $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
			  if ($i >= 1)	{
              $tableBox_string .= '>'.tep_draw_separator('spacer.gif', '1', '9').'<br />';
			  }else{
			  $tableBox_string .= '>';
			  }
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
			  $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>  ' . "\n";
            }
          }
        } else {
          $tableBox_string .= '    <td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>' . "\n";
        }

        $tableBox_string .= '  </tr> 	' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }
//---------------- tableBox_output1 --------------------- 
  class contentBox1 extends tableBox_output1 {
    function contentBox1($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_parameters = ' class="box_width_cont product"';
      $this->tableBox_output1($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_parameters = '';
      return $this->tableBox_output1($contents);
    }
  }  
// -------------------------------------- --------------------------
// -------------------------------------- --------------------------  
?>
