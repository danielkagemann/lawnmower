<?php

if ($_REQUEST['q'] == 'setup') {
    if (!file_exists('data')) {
        mkdir('data', 0777, true);
    }
    file_put_contents("data/init.json",json_encode($_POST));
}

