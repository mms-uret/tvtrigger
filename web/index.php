<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() {
    $client = new Guzzle\Http\Client();
    $response = $client->get('http://tvprogramm.srf.ch/feed/q/query/Tagesschau/startd/2014-09-12/endd/2014-09-12/starth/00/endh/24');
    //echo $response->getStatusCode();
    return $response->getBody(true);
});

$app->run();