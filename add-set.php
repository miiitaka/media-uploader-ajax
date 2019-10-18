<?php
require dirname(__FILE__).'/../../../wp-load.php' ;
$setNum = $_POST['setNum'];
$setName = $_POST['setName'];
require dirname(__FILE__).'/banner-set.php';
delete_option( 'mt_banners'.$setNum );
new Banner($setNum, $setName);
