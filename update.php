<?php

if (!file_exists("data/init.json")) {
    echo "lawnmower: please run setup on webapp first");
    return;
}


$credentials = json_decode(file_get_contents("data/init.json"), true);

$url = "http://admin:" . $credentials["code"] . "@" . $credentials["url"] . "/jsondata.cgi";

function notifyUser(msg) {
// todo
}

while(1) {
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
        file_put_contents("data/work.json", $data);

        // todo: check state for errors
        // notifyUser("error");
    }
    sleep(10);
}
