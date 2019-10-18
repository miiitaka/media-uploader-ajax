<?php
require dirname(__FILE__).'/../../../wp-load.php' ;
$delNum = $_POST['delNum'];
delete_option( $delNum );
