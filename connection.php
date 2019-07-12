<?php

include("basicDB.php");

try {
     $db = new BasicDB('localhost', '', '', '');
} catch ( PDOException $e ){
     print $e->getMessage();
}
