<?php 

// require_once __DIR__ . '/vendor/autoload.php';	// Autoload files using Composer autoload

include 'src/InstagramWithoutApi/Fetch.php';

use InstagramWithoutApi\Fetch;

$cookie = 'mid=YYcwjgAL....8765"';              // <!-- required!! please get your cookie from your browser console (6)
$userAgent = 'Mozilla/5.0...Chrome/537.36';     // <!-- required!! please get your user-agent from your browser console (7)
$xIgAppId = '9366197...';                       // <!-- required!! please get your x-ig-app-id from your browser console (8)



// get the latest 12 feeds from a tag (example https://instagram.com/explore/tags/love)

$fetchByTag = Fetch::fetchByTag([

	"group" => 'recent',						// <!-- "recent" images or "top" images; "recent" is by default 
	"base64images" => true,						// <!-- optional, but without you will be not able to save images.. it increases the size of the json file
	"base64imagesCarousel" => false,			// <!-- optional but not recommended, it increases the size of the json file
	"base64videos" => false,					// <!-- optional but not recommended, it increases the size of the json file

	"header" =>                                   
		'cookie: ' . $cookie . "\r\n" .
		'user-agent: ' . $userAgent . "\r\n" .
		'x-ig-app-id: ' . $xIgAppId . "\r\n" .
		'',

	"maxImages" => 4,							// <!-- optional, 12 is the max number
	"file" => "instagram-cache-bytag.json",		// <!-- optional, instagram-cache.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => true,							// <!-- optional, prettyfy json true/false
	"id" => "love",								// <!-- id is required

]);



// get the latest 12 feeds from an account (example https://www.instagram.com/orsifrancesco/)

$fetch = Fetch::fetch([

	"base64images" => true,						// <!-- optional, but without you will be not able to save images.. it increases the size of the json file
	"base64imagesCarousel" => false,			// <!-- optional but not recommended, it increases the size of the json file
	"base64videos" => false,					// <!-- optional but not recommended, it increases the size of the json file

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



// get picture and info from instagram id url (example https://www.instagram.com/p/Cgczi6qMuh1/)

$fetchByIdUrl = Fetch::fetchByIdUrl([

	"header" =>                                   
		'cookie: ' . $cookie . "\r\n" .
		'user-agent: ' . $userAgent . "\r\n" .
		'x-ig-app-id: ' . $xIgAppId . "\r\n" .
		'',

	"file" => "instagram-cache-byidurl.json",	// <!-- optional, instagram-cache-byidurl-{id}.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => true,							// <!-- optional, prettyfy json true/false

	"id" => "Cgczi6qMuh1",						// <!-- id is required

]);



// get picture and info from instagram id (2890411760684296309 is the id of https://www.instagram.com/p/Cgczi6qMuh1/)

$fetchById = Fetch::fetchById([

	"base64images" => true,						// <!-- optional, but without you will be not able to save images.. it increases the size of the json file
	"base64videos" => false,					// <!-- optional but not recommended, it increases the size of the json file

	"header" =>                                   
		'cookie: ' . $cookie . "\r\n" .
		'user-agent: ' . $userAgent . "\r\n" .
		'x-ig-app-id: ' . $xIgAppId . "\r\n" .
		'',

	"file" => "instagram-cache-byid.json",		// <!-- optional, instagram-cache-byid-{id}.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => true,							// <!-- optional, prettyfy json true/false

	"id" => "2890411760684296309",				// <!-- id is required

]);



// output

echo json_encode(
	array(
		'fetchByTag' => json_decode($fetchByTag),
		'fetch' => json_decode($fetch),
		'fetchByIdUrl' => json_decode($fetchByIdUrl),
		'fetchById' => json_decode($fetchById)
	),
	JSON_PRETTY_PRINT
);

?>