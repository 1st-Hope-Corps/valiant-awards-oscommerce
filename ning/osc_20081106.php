<?php
require("../includes/configure.php");

define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("NING_DIR", DOC_ROOT."/ning");
define("NING_IMAGE_DIR", DIR_WS_IMAGES."ning");
define("NING_NETWORK", "steelcity.ning.com");

include(NING_DIR."/lib_db.php");

# Instanciate Database Connection
$oConn = new Connect;
?>