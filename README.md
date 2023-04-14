<p align="center">
  <img src="https://user-images.githubusercontent.com/6490641/182688224-3730f63d-0428-49d6-a909-5a31fc3a38b9.png" width="128" height="128" alt="instagram-without-api" />
</p>
<h2 align="center">Instagram Without APIs</h2>
<h3 align="center">Instagram Scraping (@users and #tags) in April 2023, no credentials required</h3>

<br/>

This is a PHP library, are you looking for the same in Node.js? go to https://orsi.me/instagram-without-api/.

A simple PHP code to get **unlimited instagram public pictures** by **every user** without api, **without credentials** (just token from cookies), just Instagram Scraping in 2022 (with cookie and image data in base64).

You can get the latest pictures/information from an account or a single picture/information by id.

### [🛕 Cool Project Example](https://orsi.me/sniffagram)
### [🎮 Demo / Example](https://orsifrancesco.github.io/instagram-without-api/how-to-show-base64-images.html)
### ⚖️ Licensed under MIT
### 🤓 Author [@orsifrancesco](https://twitter.com/orsifrancesco)
### ☕ [Coffees are welcome](https://www.paypal.com/donate/?business=5EL4L2LDYVH96) <small>(in particular if you plan to contact me)</small>
<!--### ☕ [Offer me a coffee](https://paypal.me/orsifrancesco)-->

<hr/>

## 📦 Installation

```bash
composer install

# download the file https://github.com/orsifrancesco/instagram-without-api/blob/master/test.php
# add your cookie, user-agent and x-ig-app-id following the next step "How to get Instagram Cookie"

php test.php
```

## 🍪 How to get Instagram Cookie

- Login to Instagram
- Go to your https://instagram/yourUsername
- Open your Browser Console (on Chrome just pressing F12)
  1. Select the "Network" tab
  2. Search and click on "timeline/" file; if it is empty just refresh the page
  3. Select "Headers" bar
  4. Be sure the file is Request Method "POST" (if it is "OPTIONS" search the other "timeline/" file in the list)
  5. Scroll down and select "Request Headers" tab
  6. Copy all the code after the word "cookie: " and paste it on `$cookie` variable
  7. Copy all the code after the word "user-agent: " and paste it on `$userAgent` variable
  8. Copy all the code after the word "x-ig-app-id: " and paste it on `$xIgAppId` variable
```diff
- don't share your cookie code with anyone!!! it is the same of your credentials
```
- That's it, enjoy :)

![follow this steps](https://user-images.githubusercontent.com/6490641/181632823-42fb2308-4c3f-421a-848a-58cefcf98915.png "follow this steps")

## 💻 Images Base64
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

## 🛕 Cool Project Example
<a href="https://orsi.me/sniffagram" rel="sniffagram">![sniffagram](https://user-images.githubusercontent.com/6490641/232155875-ce2ea2ec-eeb5-4bcc-9af7-8c8d82887420.svg "sniffagram logo")</a>

## 🎮 Demo / Example
example on https://github.com/orsifrancesco/instagram-without-api/blob/master/test.php

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';  // Autoload files using Composer autoload

use InstagramWithoutApi\Fetch;

$cookie = 'mid=YYcwjgAL....8765"';              // <!-- required!! please get your cookie from your browser console (6)
$userAgent = 'Mozilla/5.0...Chrome/537.36';     // <!-- required!! please get your user-agent from your browser console (7)
$xIgAppId = '9366197...';                       // <!-- required!! please get your x-ig-app-id from your browser console (8)



// get the latest 12 feeds from a tag (example https://instagram.com/explore/tags/love)

$fetch = Fetch::fetchByTag([

  "group" => 'recent',                      // <!-- "recent" images or "top" images; "recent" is by default 
  "base64images" => true,                   // <!-- optional, but without you will be not able to save images.. it increases the size of the json file
  "base64videos" => false,                  // <!-- optional but not recommended, it increases the size of the json file

  "header" =>                                   
    'cookie: ' . $cookie . "\r\n" .
    'user-agent: ' . $userAgent . "\r\n" .
    'x-ig-app-id: ' . $xIgAppId . "\r\n" .
    '',

  "maxImages" => 4,                         // <!-- optional, 12 is the max number
  "file" => "instagram-cache.json",         // <!-- optional, instagram-cache.json is by default
  "time" => 3600,                           // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                         // <!-- optional, prettyfy json true/false

  "id" => "love",                           // <!-- id is required

]);



// get the latest 12 pictures from an account (example https://www.instagram.com/orsifrancesco/)

echo Fetch::fetch([

  "base64images" => true,                   // <!-- optional, but without you will be not able to save images.. it increases the size of the json file
  "base64videos" => false,                  // <!-- optional but not recommended, it increases the size of the json file

  "header" =>                                   
    'cookie: ' . $cookie . "\r\n" .
    'user-agent: ' . $userAgent . "\r\n" .
    'x-ig-app-id: ' . $xIgAppId . "\r\n" .
    '',

  "maxImages" => 4,                           // <!-- optional, 12 is the max number
  "file" => "instagram-cache.json",           // <!-- optional, instagram-cache.json is by default
  "time" => 3600,                             // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                           // <!-- optional, prettyfy json true/false

  "id" => "orsifrancesco",                    // <!-- id is required

]);



// get picture and info from instagram id url (example https://www.instagram.com/p/Cgczi6qMuh1/)

echo Fetch::fetchByIdUrl([

  "header" =>                                   
    'cookie: ' . $cookie . "\r\n" .
    'user-agent: ' . $userAgent . "\r\n" .
    'x-ig-app-id: ' . $xIgAppId . "\r\n" .
    '',

  "file" => "instagram-cache-byidurl.json",   // <!-- optional, instagram-cache-byidurl-{id}.json is by default
  "time" => 3600,                             // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                           // <!-- optional, prettyfy json true/false

  "id" => "Cgczi6qMuh1",                      // <!-- id is required

]);



// get picture and info from instagram id (2898553675947377985 is the id of https://www.instagram.com/p/Cgczi6qMuh1/)

echo Fetch::fetchById([

  "base64images" => true,                   // <!-- optional, but without you will be not able to save images.. it increases the size of the json file
  "base64videos" => false,                  // <!-- optional but not recommended, it increases the size of the json file

  "header" =>                                   
    'cookie: ' . $cookie . "\r\n" .
    'user-agent: ' . $userAgent . "\r\n" .
    'x-ig-app-id: ' . $xIgAppId . "\r\n" .
    '',

  "file" => "instagram-cache-byid.json",    // <!-- optional, instagram-cache-byid-{id}.json is by default
  "time" => 3600,                           // <!-- optional, reload contents after 3600 seconds by default
  "pretty" => true,                         // <!-- optional, prettyfy json true/false

  "id" => "2890411760684296309",            // <!-- id is required

]);

?>
```

## 🕹️ JSON outputs
output example for `Fetch::fetchByTag`
```json
[
 {
    "id": "3056995843878120981",
    "time": 1678642360,
    "imageUrl": "https://scontent.cdninstagram.com/v/t51.2885-15/335624371_158386035875...",
    "likes": 11063,
    "comments": 87,
    "link": "https://www.instagram.com/p/CpsoYX6M24V/",
    "text": "#ad oh hi, just your...om",
    "videoUrl": "https://scontent.cdninstagram.com/v/t50.2886-16/335573547_655944973077...",
    "videoViewCount": 126891
  },
  {
      "id": "2968084867929508835",
      "time": 1668043310,
      "imageUrl": "https://scontent.cdninstagram.com/v/t51.2885-15/314902884_370847155226...",
      "likes": 27989,
      "comments": 170,
      "link": "https://www.instagram.com/p/CkwwXY4Jovj/",
      "text": "Went to the Grand Canyon. Judging from my..",
      "location": "Grand Canyon",
      "carousel": [
          "https://scontent.cdninstagram.com/v/t51.2885-15/314902884_..",
          "https://scontent.cdninstagram.com/v/t51.2885-15/314674373_678631710324..."
      ]
  }
]
```


output example for `Fetch::fetch` on https://github.com/orsifrancesco/instagram-without-api/blob/master/instagram-cache.json
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
    "image": "/9j/4AAQSkZJRgABAQAAAQABAAD/7QB8UGhvdG9zaG9wIDMuMAA4QklNBAQAAAAAAGA.............",
    "location": "Liverpool Cathedral",
    "carousel": [
        "https://scontent.cdninstagram.com/v/t51.2885-15/314902884_370847155226583_8126....",
        "https://scontent.cdninstagram.com/v/t51.2885-15/314674373_678631710324..."
    ]
  },
  {
    "id": "2654027113529608497",
    "time": 1630604708,
    "imageUrl": "https://scontent-lcy1-1.cdninstagram.com/v/t51.2885-15/e35/p1080x1080/241221239_8640769...",
    "videoUrl": "https://scontent-lcy1-1.cdninstagram.com/v/t51.2885-15/e35/p1080x1080/241221239_8640769...",
    "likes": 38,
    "comments": 0,
    "link": "https://www.instagram.com/p/CTU_5keMAkx/",
    "text": "#london #uk #unitedkingdom #tube #underground #overground #sunrise #morning #morningvibes #sky #metro #line #prospective",
    "image": "/9j/4AAQSkZJRgABAQAAAQABAAD/7QB8UGhvdG9zaG9wIDMuMAA4Qkl...........",
    "location": "Eiffle Tower, Paris France."
  }
]
```

output example for `Fetch::fetchByIdUrl` or `Fetch::fetchById`
```json
[
  {
    "id": "289855367...",
    "width": 1385,
    "height": 1731,
    "imageUrl": "https:\/\/scontent-lhr8-1.cdnin...",
    "time": 1659754546,
    "topLikers": [
        "franko"
    ],
    "likes": 32,
    "commentCount": 2,
    "comments": [
      {
        "time": 1659756069,
        "text": "This is a comment...",
        "user": {
          "username": "test",
          "fullName": "DearTest",
          "imageUrl": "https:\/\/scontent-lhr8-1.cdninstagram.com..."
        }
      }
    ],
    "link": "https:\/\/www.instagram.com\/p\/Cgczi6qMuh1\/",
    "text": "If you know it, you know it...",
    "user": {
      "username": "orsifrancesco",
      "fullName": "Frank",
      "imageUrl": "https:\/\/scontent-lhr8-1.cd..."
    },
    "image": "\/9j\/4AAQSkZJR....Q=="
  }
]
```

## ⚖️ License

Licensed under MIT


## ☕ About

Any feedback to [@orsifrancesco](https://twitter.com/orsifrancesco) and [coffee](https://www.paypal.com/donate/?business=5EL4L2LDYVH96) are welcome :) 