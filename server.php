<?php

date_default_timezone_set("Europe/Berlin");

function L($txt) {
    $fp = @fopen("data/log.txt","at+");
     if ($fp) {
     fwrite($fp, date("Y-m-d H:i:s") . " " . $txt . "\n");
        fclose($fp);
     }
}


if ($_REQUEST['q'] == 'setup.set') {
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }
    $data['nickname'] = $_REQUEST['nickname'];
    $data['code'] = $_REQUEST['code'];
    $data['url'] = $_REQUEST['url'];
    file_put_contents("data/init.json",json_encode($data));
} else if ($_REQUEST['q'] == 'setup.get') {
    $data = file_get_contents('data/init.json');
    echo $data;
} else if ($_REQUEST['q'] == 'data.get') {
    $data = file_get_contents('data/work.json');
    echo $data;
}

