<?php 

require_once __DIR__ . '/vendor/autoload.php';	// Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

$cookie = 'mid=YYcwjgAL....8765"';              // <!-- required!! please get your cookie from your browser console (6)
$userAgent = 'Mozilla/5.0...Chrome/537.36';     // <!-- required!! please get your user-agent from your browser console (7)
$xIgAppId = '9366197...';                       // <!-- required!! please get your x-ig-app-id from your browser console (8)

echo Fetch::fetch([

	"header" =>                                   
		'cookie: ' . $cookie . "\r\n" .
		'user-agent: ' . $userAgent . "\r\n" .
		'x-ig-app-id: ' . $xIgAppId . "\r\n" .
		'',

	"maxImages" => 4,							// <!-- optional, 12 is the max number
	"file" => "instagram-cache.json",			// <!-- optional, instagram-cache.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => true,							// <!-- optional, prettyfy json true/false

	"id" => "orsifrancesco",					// <!-- id is required

]);

?>