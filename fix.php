<?php

$files = array(
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_151.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_201.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_401.',
	'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_451.'
);
$report = $files[0];

function fix_billeder($from, $to) {
	$dom = new DOMDocument('1.0');
	$dom->loadHTMLFile($from);
	$images = $dom->getElementsByTagName('img');
	foreach($images as $image) {
		$src = $image->getAttribute('src');
		$src = str_replace('../../', 'estonia%20final%20report/', $src);
		$src = str_replace('../', 'estonia%20final%20report/', $src);
		if (substr($src, 0, 7) === 'images/') {
			$src = 'estonia%20final%20report/'.$src;
		}
		$image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
	}
	$dom->save($to);
}

foreach($files as $file) {
	fix_billeder($file.'html', $file.'fixed.html');
}

function fix_fjern_pile($file) {
	$dom = new DOMDocument('1.0');
	libxml_use_internal_errors(true);
	$dom->loadHTMLFile($file);
	$maps = $dom->getElementsByTagName('map');
	foreach($maps as $map) {
		$map->nodeValue = ''; //!!?
	}
	$images = $dom->getElementsByTagName('img');
	$remove = array('https://www.estoniaferrydisaster.net/estonia%20final%20report/images/buttons%20clear.gif',
									'https://www.estoniaferrydisaster.net/estonia%20final%20report/images/buttons.jpg',
									'https://www.estoniaferrydisaster.net/estonia%20final%20report/images/next.jpg');
	foreach($images as $image) {
		$src = $image->getAttribute('src');
		if (in_array($src, $remove)) {
			$image->setAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=');
			$image->removeAttribute('usemap');
		}
	}
	$dom->save($file);
}

foreach($files as $file) {
	fix_fjern_pile($file.'fixed.html');
}

function fix_bilag($file) {
	$dom = new DOMDocument('1.0');
	libxml_use_internal_errors(true);
	$dom->loadHTMLFile($file);
	$headers = $dom->getElementsByTagName('head');
	foreach($headers as $head) {
		$title = ucfirst($head->getElementsByTagName('title')->item(0)->textContent);
		$h1 = $dom->createElement('h1', $title); 
		$head->parentNode->insertBefore($h1, $head);
		$head->nodeValue = ''; //!!?
	}
	$dom->save($file);
}

foreach($files as $file) {
	if ($file !== $report) {
		fix_bilag($file.'fixed.html');
	}
}

?>
