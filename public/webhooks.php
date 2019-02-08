<?php

// configs
$deployToken = '2pzeV3EgWPhfiC2V1ZRPYeoqLeSEBdaj';
$deployPath = '/data/wwwroot/cis.coccuscc.cn';


// authentication (GitHub JSON Version)
$payload = file_get_contents('php://input');
if ( !isset( $_SERVER['HTTP_X_HUB_SIGNATURE'] ) || !isset($payload) ) {
    exit('input error');
}

$githubSignature = $_SERVER['HTTP_X_HUB_SIGNATURE'];
$payloadHash = 'sha1='.hash_hmac('sha1', $payload, $deployToken);
if ( $githubSignature != $payloadHash ) {
    exit('signature error');
}


// auto deploy
$command = "cd {$deployPath} && git pull 2>&1";
$result = shell_exec($command);

echo $result;