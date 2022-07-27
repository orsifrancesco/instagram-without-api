# instagram-without-api, Instagram Scraping in August 2022, no credentials required

A simple PHP code to get **unlimited instagram public pictures** by **every user** without api, **without credentials** (just token from cookies), just Instagram Scraping in 2022 (with cookie and image data in base64).

## Installation

```bash
composer install

php test.php
```

## How to get Instagram Cookie

- Login to Instagram
- Open your Browser Console (on Windows Chrome just pressing F12)
  1. Select the "Network" tab
  2. Click to the first downloaded resource of the list (normally it will be https://instagram.com/yourusername), if it is empty just refresh the page 
  3. Select "Headers" bar
  4. Scroll down to "Request Headers" and Copy all the code after the word "cookie"
- Paste that string to the "cookie" parameter (as at the following example)
- That's it, enjoy :)

![follow this steps](https://user-images.githubusercontent.com/6490641/140643878-d96877a4-b8ac-402d-a977-681f6dda83f4.png "follow this steps")

## Images Base64
Although you can get the URLs of the images, Instagram doesn't give you the possibility to include and showing those images on your projects (they will be automatically blocked from their servers).\
To solve this problem you will get all the URLs and all the images data in base64.\
You can easily show the image data on your project with the following snippets of code:

```html
<img src="data:image/jpg;base64, hereYourBase64String.."/>
```
```css
.example { background-image: url('data:image/jpg;base64, hereYourBase64String..'); }
```

Check https://orsifrancesco.github.io/instagram-without-api/how-to-show-base64-images.html for Base64 example.

## Example
example on https://github.com/orsifrancesco/instagram-without-api/blob/master/test.php

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';  // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

echo Fetch::fetch([

  "header" =>                         // <!-- required!! please get your cookie from your browser console
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

  "maxImages" => 2,                   // <!-- optional, 12 is the max number
  "file" => "instagram-cache.json",   // <!-- optional, instagram-cache.json is by default
  "time" => 3600,                     // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                   // <!-- optional, prettyfy json true/false

  "id" => "orsifrancesco",            // <!-- id is required

]);

// or

echo Fetch::fetch([
  "cookie" => "sameLongStringAsBefore..",
  "id" => "yourUsername"
]); 

?>
```

## JSON output
output example on https://github.com/orsifrancesco/instagram-without-api/blob/master/instagram-cache.json

```json
[
  {
    "id": "2696840872190940431",
    "time": 1635708506,
    "imageUrl": "https://scontent-lcy1-1.cdninstagram.com/v/t51.2885-15/e35/p1080x1080/249938862_1214260935751176_32...",
    "likes": 18,
    "comments": 0,
    "link": "https://www.instagram.com/p/CVtGnwashUP/",
    "text": "#helloworld #domain #check",
    "image": "/9j/4AAQSkZJRgABAQAAAQABAAD/7QB8UGhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAGA............."
  },
  {
    "id": "2654027113529608497",
    "time": 1630604708,
    "imageUrl": "https://scontent-lcy1-1.cdninstagram.com/v/t51.2885-15/e35/p1080x1080/241221239_8640769...",
    "likes": 38,
    "comments": 0,
    "link": "https://www.instagram.com/p/CTU_5keMAkx/",
    "text": "#london #uk #unitedkingdom #tube #underground #overground #sunrise #morning #morningvibes #sky #metro #line #prospective",
    "image": "/9j/4AAQSkZJRgABAQAAAQABAAD/7QB8UGhvdG9zaG9wIDMuMAA4Qkl..........."
  }
]
```

## License

Licensed under MIT