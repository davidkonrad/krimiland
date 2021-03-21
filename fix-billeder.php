<?php
$from = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.html';
$to = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html';
$dom = new DOMDocument('1.0');
$dom->loadHTMLFile($from);
$images = $dom->getElementsByTagName('img');
foreach($images as $image) {
	$src = $image->getAttribute('src'); 
	$image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
}
$dom->save($to);
?>
