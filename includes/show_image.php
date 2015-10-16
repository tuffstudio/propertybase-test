<?php

require_once '../vendor/autoload.php';

$fileName = (isset($_GET['file'])) ? urldecode($_GET['file']) : null;

if ($fileName == null )
{
     // handle missing images however you want... perhaps show a default image??  Up to you...
	$thumb = new PHPThumb\GD('http://www.dawnchopp.webfactional.com/proto/propertybase-test/img/thumb.png');

}else{

$fileName = str_replace(' ', '%20', $fileName); 
$thumb = new PHPThumb\GD( $fileName );

}

$thumb->resize(100, 60);
$thumb->show();

?>