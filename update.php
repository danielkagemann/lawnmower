<?php

print " _\n";
print "| |\n";
print "| | __ ___      ___ __  _ __ ___   _____      _____ _ __\n";
print "| |/ _` \\ \\ /\\ / / '_ \\| '_ ` _ \\ / _ \\ \\ /\\ / / _ \\ '__|\n";
print "| | (_| |\\ V  V /| | | | | | | | | (_) \\ V  V /  __/ |\n";
print "|_|\\__,_| \\_/\\_/ |_| |_|_| |_| |_|\\___/ \\_/\\_/ \\___|_|\n";
print "";


if (!file_exists("data/init.json")) {
    echo "lawnmower: please run setup on webapp first";
    return;
}

$credentials = json_decode(file_get_contents("data/init.json"), true);

$url = "http://admin:" . $credentials["code"] . "@" . $credentials["url"] . "/jsondata.cgi";

/**
 * send notification
 */
function notifyUser($msg) {
    // todo
}

while(1) {

    print "try getting data\n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    curl_close($ch);

    if ($data === false) {
        file_put_contents("data/work.json", "{}");
        notifyUser("Connection is lost");
    } else {

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

        file_put_contents("data/work.json", json_encode($res));

        // notifyUser("error");
    }
    sleep(10);
}
