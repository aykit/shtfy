<?php

define("DB_DSN", getenv("SHTFY_DB_DSN") ||
       "mysql:host=127.0.0.1;dbname=shtfy");
define("DB_USER", getenv("SHTFY_DB_USER") || "shtfy");
define("DB_PASSWORD", getenv("SHTFY_DB_PASSWORD") || "");

?>
