<?php
$from = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.html';
$to = 'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html';
$dom = new DOMDocument('1.0');
$dom->loadHTMLFile($from);
$images = $dom->getElementsByTagName('img');
foreach($images as $image) {
	$src = $image->getAttribute('src');
	echo $src.'<br>';
	$src = str_replace('../', 'estonia%20final%20report/', $src);
	if (substr($src, 0, 7 ) === 'images/') {
		$src = 'estonia%20final%20report/'.$src;
	}
	$image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
}
$dom->save($to);
?>

