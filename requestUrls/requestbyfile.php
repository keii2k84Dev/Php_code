<?php
/**
 * Created by PhpStorm.
 * User: t-ckimu
 * Date: 2018/03/27
 * Time: 10:48
 */

$url_prefix = "";
$urls = file_get_contents('urls.txt');
$arr = preg_split('/$\R?^/m', $urls);
var_dump($arr);


foreach ($arr as $url) {
    $ch = curl_init();
// set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url_prefix . $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
}
