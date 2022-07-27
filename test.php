<?php 

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

echo Fetch::fetch([

	"header" =>									// <!-- required!! please check https://github.com/orsifrancesco/instagram-without-api to see how to get your cookie
		"accept: */*\r\n" .
		"accept-language: en-GB,en;q=0.9\r\n" .
		"cache-control: no-cache\r\n" .
		'cookie: 76d998fc6e27ab131b389988eb51b537..........db15b12cf7952"\r\n' .
		"dnt: 1\r\n" .
		"origin: https://www.instagram.com\r\n" .
		"pragma: no-cache\r\n" .
		"referer: https://www.instagram.com/\r\n" .
		'sec-ch-ua: ".Not/A)Brand";v="99", "Google Chrome";v="103", "Chromium";v="103"\r\n' .
		"sec-ch-ua-mobile: ?0\r\n" .
		'sec-ch-ua-platform: "Windows"\r\n' .
		"sec-fetch-dest: empty\r\n" .
		"sec-fetch-mode: cors\r\n" .
		"sec-fetch-site: same-site\r\n" .
		"user-agent: Mozilla/5.0 (Windows NT 10...........me/103.0.0.0 Safari/537.36\r\n" .
		"x-asbd-id: 198387\r\n" .
		"x-csrftoken: 4kvO.........qWLPyMz7sd5r2\r\n" .
		"x-ig-app-id: 936619743392459\r\n" .
		"x-ig-www-claim: hmAcPXDmhdpYA1\r\n",

	"maxImages" => 12,							// <!-- optional, 12 is the max number
	"file" => "instagram-cache.json",			// <!-- optional, instagram-cache.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => false,							// <!-- optional, prettyfy json true/false

	"id" => "orsifrancesco",					// <!-- id is required

]);

?>