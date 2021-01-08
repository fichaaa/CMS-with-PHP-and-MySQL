<?php

require dirname(__DIR__) . '/classes/Database.php';

$db = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
return $db->getConn();