<?php
/**
#######################################
#    Crawler kompas by NYX i-Tech     #
#              Rifal, 2016            #
#######################################
**/
class Kompas{


	public function get(){
		require_once 'function.php';
        require_once '../cus.php';
        include_once('../wp-load.php');
     	$src = 'http://indeks.kompas.com/indeks/index/news/nasional';		
     	$html = $this->getHTML($src);
     	$links = $this->getLinks($html);

     	foreach ($links as $link) {
        $wadootitle = str_replace('  ', ' ', $d->title);
        global $wpdb;
            $data = $this->extractContent($this->getHTML($link));
            $title = str_replace('  ', ' ', $data['title']);
            $img = $data['img'];
            $content = $data['content'];

            $post_if = $wpdb->get_var("SELECT count(post_title) FROM $wpdb->posts WHERE post_title = '$title'");
            if($post_if > 0){
                break;
            }
           

            $thumbName = md5($img) . '.jpg';
            echo $content;
            saveImage($img, '../' . $kompasPath . $thumbName);
           
            $xml = new zzz();
            $xml->posting($title,$content, $content,$thumbName,$kompasPath);
     	}
	}

    private function getHTML($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
		return $data;

    }

    private function getLinks($html){
		preg_match_all('/<div class="kcm-main-list">(.*)<!-- Paginasi -->/is', $html, $wrap);
       	preg_match_all('/<a\s+href=["\']([^"\']+)["\']/siU', $wrap[0][0], $links);
	    return $links[1];
    }

    private function extractContent($html){
    	preg_match_all('/<div class="kcm-read">(.*)<!-- e: halaman baca -->/is', $html,$wrap);
    	preg_match_all('/<!-- e: breadcrumb -->(.*)<h2>(.*)<\/h2>/is', $html,$title);
    	preg_match_all('/<div class="kcm-read-text">(.*)<!-- e: copyright -->/is', $wrap[1][0],$content);
        preg_match_all('/<img data-width="780px" data-aligment=""  src="(.*)" alt="" \/>/siU', $html, $img);
        $img = (strlen($img[1][0]) > 0 ? $img[1][0] : 'bacod');
        // if(preg_match_all('/<iframe\s+src=["\']([^"\']+)["\']/is',$content[0][0])){
    	// 	echo '<h1>ada video</h1>';
    	// }
        $content_fix = preg_replace('/<p>([<strong>])?\(Baca(.*)([<\/strong>])?<\/p>/isU', '', $content[0][0]);
        $content_fix = preg_replace('/[<strong>]?\(Baca(.*)<\/strong>/isU', '', $content[0][0]);
        $content_fix = preg_replace('/<div id="btn-share-bawah"(.*)<!-- e: copyright -->/isU', '', $content_fix);
        $content_fix = preg_replace('/<a\s+href=["\'](.*)kompas(.*)["\']>/isU', '', $content_fix);
        $content_fix = preg_replace('/<!-- s: topik berita -->(.*)<!-- e: topik berita -->/isU', '', $content_fix);
        $content_fix = preg_replace('/<p><br><br><\/p>/isU', '', $content_fix);
        $content_fix = str_replace("<strong<br />", "<br />", $content_fix);
    	$data['title'] = $title[2][0];
    	$data['content'] = $content_fix;
        $data['img'] = $img;
    	return $data;
    }


}

?>