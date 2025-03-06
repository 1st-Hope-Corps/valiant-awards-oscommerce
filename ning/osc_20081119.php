<?php
require("../includes/configure.php");

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("NING_DIR", DOC_ROOT."/ning");
define("NING_IMAGE_DIR", DIR_WS_IMAGES."ning");
define("NING_NETWORK_MYGIZMOZ", "mygizmoz.ning.com");
define("NING_NETWORK_GIZTER", "gizter.ning.com");

include(NING_DIR."/lib_db.php");

# Instanciate Database Connection
$oConn = new Connect;
$oConnDev = new Connect("localhost", "devstore", "devstore", "devstore");
?>