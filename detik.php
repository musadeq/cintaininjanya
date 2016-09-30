<?php

// KELAS GA GUNA, CLASS DETIK?
Class Detik
{
    private $base;

    public function __construct()
    {
        $this->base = 'http://127.0.0.1/agc/';
    }

    public function news()
    {
        $rss = 'http://rss.detik.com/index.php/detiknews';
        $c = file_get_contents($rss);
        $c = simplexml_load_string($c);

        foreach ($c->channel->item as $d) {
            $title = $d->title;
            $url = $d->link;
            $desc = $d->description;
            $content = $this->parsing($url);
            $images = $this->getImageLink($this->parsing($url));
            echo $d->enclosure['url'];
            foreach ($images as $image) {
                $imageName = md5($title) . '.jpg';
                $this->saveImage($image, $imageName);
                $content = $this->replaceImage($content, $image, $this->base . 'images/' . $imageName);
            }


        }

    }


    private function parsing($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);

        $awal = '<div class="detail_text">';
        $akhir = '</div>';

        $isi = explode($awal, $data);
        $isi2 = explode($akhir, $isi[1]);
        $hasil = $isi2[0];

        return $hasil;
    }

    private function getImageLink($html)
    {
        $linkArray = array();
        if (preg_match_all('/<img\s+.*?src=[\"\']?([^\"\' >]*)[\"\']?[^>]*>/i', $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $linkArray[] = $match[1];
            }
        }
        return $linkArray;

    }

    private function saveImage($url, $name)
    {
        copy($url, 'images/' . $name);
    }


    private function replaceImage($content, $src, $local)
    {
        return str_replace($src, $local, $content);
    }
}