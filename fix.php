<?php

function fix_billeder($from, $to) {
	$dom = new DOMDocument('1.0');
	$dom->loadHTMLFile($from);
	$images = $dom->getElementsByTagName('img');
	foreach($images as $image) {
		$src = $image->getAttribute('src');
		$src = str_replace('../', 'estonia%20final%20report/', $src);
		if (substr($src, 0, 7 ) === 'images/') {
			$src = 'estonia%20final%20report/'.$src;
		}
		$image->setAttribute('src', 'https://www.estoniaferrydisaster.net/'.$src);
	}
	$dom->save($to);
}

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html');


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

fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html');
fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html');
fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html');


?>
