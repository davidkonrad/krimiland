<?php

function fix($from, $to) {
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
}

fix('www.estoniaferrydisaster.net/estoniaferrydisaster.net.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.fixed.html');

fix('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag.fixed.html');

fix('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html');

fix('www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.html', 
    'www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html');


//
$ chrome --headless --print-to-pdf="estoniaferrydisaster.net.bilag_101.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html

chromium-browser --headless --print-to-pdf="estoniaferrydisaster.net.bilag_101.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_101.fixed.html
chromium-browser --headless --print-to-pdf-no-header --print-to-pdf="estoniaferrydisaster.net.bilag_51.pdf" www.estoniaferrydisaster.net/estoniaferrydisaster.net.bilag_51.fixed.html




?>
