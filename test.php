<?php 

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

echo Fetch::fetch([

	"cookie" =>									// <!-- required!! please check https://github.com/orsifrancesco/instagram-without-api to see how to get your cookie
	'somethingSuperLongLikeThis=fromYourBrowserConsole; csrftoken=1234xcj5; fbm_124024574287414=base_domain=.instagram.com; shbid="12383\0543952162074\054166446......."',

	"maxImages" => 12,							// <!-- optional, 12 is the max number
	"base64images" => false,					// <!-- optional, false is by default, includes images base64 in the json output, it is pretty heavy
	"file" => "instagram-cache.json",			// <!-- optional, instagram-cache.json is by default
	"time" => 3600,								// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => false,							// <!-- optional, prettyfy json true/false

	"id" => "orsifrancesco",					// <!-- id or tag is required
	//"tag" => "landscape"						// <!-- id or tag is required

]);

?>