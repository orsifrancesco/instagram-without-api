<?php 

require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

echo Fetch::fetch([
	"file" => "instagram-cache.json",		// <!-- optional, instagram-cache.json is by default
	"time" => 3600,							// <!-- optional, reload contents after 3600 seconds by default
	"pretty" => false,						// <!-- optional, prettyfy json true/false
	"id" => "orsifrancesco",				// <!-- id or tag is required
	//"tag" => "landscape"					// <!-- id or tag is required
]);

?>