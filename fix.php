<?php

//Konverterer relative stier til absolutte
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

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_151.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_151.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_201.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_201.fixed.html');

fix_billeder('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_401.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_401.fixed.html');


//Fjerner det oprindelige, nu uanvendelige navigationssystem
function fix_fjern_pile($file) {
	$dom = new DOMDocument('1.0');
	libxml_use_internal_errors(true);
	$dom->loadHTMLFile($file);
	$maps = $dom->getElementsByTagName('map');
	foreach($maps as $map) {
		$map->nodeValue = ''; //!!?
	}
	$images = $dom->getElementsByTagName('img');
	foreach($images as $image) {
		$src = $image->getAttribute('src');
		if (in_array($src, array('https://www.estoniaferrydisaster.net/estonia%20final%20report/images/buttons%20clear.gif',
														'https://www.estoniaferrydisaster.net/estonia%20final%20report/images/buttons.jpg'))) {
			$image->setAttribute('src', 'data:image/gif;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=');
			$image->removeAttribute('usemap');
		}
	}
	$dom->save($file);
}
fix_fjern_pile('www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html');

//Trækker <title> ud, tømmer headeren og placerer en <h1> foran indholdet
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
fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_151.fixed.html');
fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_201.fixed.html');
fix_bilag('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_401.fixed.html');

?>
