# instagram-without-api

Just a simple php code to get instagram public pictures by user or by tag without api.

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
  4. Scroll down and Copy all the code after the word "cookie"
- Paste that string to the "cookie" parameter (as at the following example)
- That's it, enjoy :)

![follow this steps](https://user-images.githubusercontent.com/6490641/140643878-d96877a4-b8ac-402d-a977-681f6dda83f4.png "follow this steps")

## Images Base64
Although you can get the URLs of the images, Instagram doesn't give you the possibility to include and showing those images on your projects (they will be automatically blocked from their servers).\
You can solve the problem enabling `"base64images" => true` (check the following example).\
You will get all the URLs and all the images data.\
You can easily show the image data on your project with the following snippets of code:

```html
<img src="data:image/jpg;base64, hereYourBase64String.."/>
```
```css
.example { background-image: url(data:image/jpg;base64, hereYourBase64String..); }
```

## Example

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';  // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

echo Fetch::fetch([

  "cookie" =>                         // <!-- required!! please get your cookie from your browser console
  'somethingSuperLongLikeThis=fromYourBrowserConsole; csrftoken=1234xcj5; fbm_124024574287414=base_domain=.instagram.com; shbid="12383\0543952162074\054166446......."',

  "maxImages" => 12,                  // <!-- optional, 12 is the max number
  "base64images" => false,            // <!-- optional, false is by default, includes images base64 in the json output, it is pretty heavy
  "file" => "instagram-cache.json",   // <!-- optional, instagram-cache.json is by default
  "time" => 3600,                     // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                   // <!-- optional, prettyfy json true/false

  "id" => "orsifrancesco",            // <!-- id or tag is required
  //"tag" => "landscape"              // <!-- id or tag is required

]);

// or

echo Fetch::fetch([
  "cookie" => "sameLongStringAsBefore..",
  "id" => "yourUsername"
]); 

// or

echo Fetch::fetch([
  "cookie" => "sameLongStringAsBefore..",
  "tag" => "yourFavoriteTag"
]);

?>
```

## License

Licensed under MIT