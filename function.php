<?php
$base = 'http://grab.adtival.com/';
$detikPath = 'wp-content/uploads/detik/';
// grab image dari html
function getImageLink($html)
{
    $linkArray = array();
    if (preg_match_all('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $html, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $linkArray[] = $match[1];
        }
    }
    return $linkArray;

}

//save image ke folder bot
function saveImage($url, $name)
{
	 copy($url,$name );
}

//replace link image source ke domain local
function replaceImage($content, $src, $local)
{
	echo '<br>replace<br>';
    return str_replace($src, $local, $content);
}

function testing(){
	return '<h1> ini testing </h1>';
}

?>