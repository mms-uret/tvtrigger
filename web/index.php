<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->get('/', function() {
    $client = new Guzzle\Http\Client();
    $response = $client->get('http://tvprogramm.srf.ch/feed/q/query/simpsons')->send();

    $xml = $response->xml();
    $times = array();
    foreach ($xml->channel->item as $item) {
        $title = (string)$item->title;
        $date = explode(',', $title)[0];
        $times[] = strtotime($date);
    }

    $next = min($times);

    $tolerance = 5 * 60;

    $now = time();
    if (isset($_GET['now'])) {
        $now = strtotime($_GET['now']);
    }
    $trigger = false;
    if ($now > $next - $tolerance && $now < $next) {
        $trigger = true;
        $client->put('http://requestb.in/p7ne8hp7')->send();
    }

    $result = "Now: " . date('r', $now) . "<br>Next show: " . date('r', $next) . "<br>Trigger: " . (($trigger) ? "true" : "false");

    return $result;
});

$app->run();