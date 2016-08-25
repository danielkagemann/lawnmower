<?php

$url = "http://admin:0812@worx.fritz.box/jsondata.cgi";

if ($_REQUEST['q'] == 'info') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    echo $data;
}
