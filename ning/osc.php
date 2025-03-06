<?php
require("../includes/configure.php");

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("NING_DIR", DOC_ROOT."/ning");
define("NING_IMAGE_DIR", DIR_WS_IMAGES."ning");
define("HOPE_WWW", "www.hopecybrary.org");
define("HOPE_DRUPAL", "drupal.hopecybrary.org");

include(NING_DIR."/lib_db.php");

# RFC822 Email Parser
function is_valid_email_address($email){
	$qtext = '[^\\x0d\\x22\\x5c\\x80-\\xff]';
	$dtext = '[^\\x0d\\x5b-\\x5d\\x80-\\xff]';
	$atom = '[^\\x00-\\x20\\x22\\x28\\x29\\x2c\\x2e\\x3a-\\x3c'.
			'\\x3e\\x40\\x5b-\\x5d\\x7f-\\xff]+';
	$quoted_pair = '\\x5c[\\x00-\\x7f]';
	$domain_literal = "\\x5b($dtext|$quoted_pair)*\\x5d";
	$quoted_string = "\\x22($qtext|$quoted_pair)*\\x22";
	$domain_ref = $atom;
	$sub_domain = "($domain_ref|$domain_literal)";
	$word = "($atom|$quoted_string)";
	$domain = "$sub_domain(\\x2e$sub_domain)*";
	$local_part = "$word(\\x2e$word)*";
	$addr_spec = "$local_part\\x40$domain";
	return (preg_match("!^$addr_spec$!", $email)) ? true:false;
}

# Instanciate Database Connection
$oConn = new Connect;
//$oConnDev = new Connect("localhost", "devstore", "devstore", "devstore");
?>