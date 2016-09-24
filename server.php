<?php

date_default_timezone_set("Europe/Berlin");

function L($txt) {
    $fp = @fopen("data/log.txt","at+");
     if ($fp) {
     fwrite($fp, date("Y-m-d H:i:s") . " " . $txt . "\n");
        fclose($fp);
     }
}

function getdata() {
    $credentials = json_decode(file_get_contents("data/init.json"), true);
    $url = "http://admin:" . $credentials["code"] . "@" . $credentials["url"] . "/jsondata.cgi";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    $res = array("battery"=>$buf['perc_batt'], "nickname"=>$credentials["nickname"], "action"=>"", "icon"=>"", "distance"=>$buf['distance']);
    if ($data !== false) {
        // check the data
        $buf = json_decode($data, true);
        $res = array("battery"=>$buf['perc_batt'], "nickname"=>$credentials["nickname"], "action"=>"", "icon"=>"", "distance"=>$buf['distance']);

        if ($buf['batteryChargerState'] != 'idle') {
            $res['action'] = "lädt auf";
            $res['icon'] = "idle";
        }
        if ($buf["state"] == 'home' && $buf["batteryChargerState"] == 'idle') {
            $res['action'] = "ist in Ladestation";
            $res['icon'] = "idle";
        }
        if ($buf["state"] == 'grass cutting') {
            $res['action'] = "mäht";
            $res['icon'] = "mowing";
        }
        if ($buf["state"] == 'following wire') {
            $res['action'] = "folgt der Begrenzung";
            $res['icon'] = "border";
        }
        if ($buf["state"] == 'trapped recovery') {
            $res['action'] = "ist gefangen";
            $res['icon'] = "trapped";
        }
        if ($buf["state"] == 'lift recovery') {
            $res['action'] = "wurde angehoben";
            $res['icon'] = "trapped";
        }
        if ($buf["message"] == 'outside wire') {
            $res['action'] = "außerhalb der Grenze";
            $res['icon'] = "outside";
        }
        if ($buf["message"] == 'lifted') {
            $res['action'] = "braucht Hilfe";
            $res['icon'] = "trapped";
        }
        if ($buf["message"] == 'error motor blade fault') {
            $res['action'] = "braucht Hilfe";
            $res['icon'] = "trapped";
        }

    }
    // check if something from above matched
    if ($res['icon'] == '') {
        $res['action'] = "Unbekannter Fehler: " . $buf["message"];
        $res['icon'] = "trapped";
    }

    echo json_encode($res);
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
} else if ($_REQUEST['q'] == 'data.getfile') {
    $data = file_get_contents('data/work.json');
    echo $data;
} else if ($_REQUEST['q'] == 'data.get') {
    getdata();
}

