<?php 

namespace InstagramWithoutApi;

class Fetch {

	// private static function getSslPage($instagramUrl, $header) {
	// 	$ch = curl_init();
	// 	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	// 	curl_setopt($ch, CURLOPT_HEADER, false);
	// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	// 	curl_setopt($ch, CURLOPT_URL, $url);
	// 	curl_setopt($ch, CURLOPT_REFERER, $url);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	// 	$result = curl_exec($ch);
	// 	curl_close($ch);
	// 	return $result;
	// }
	
	private static function magicCurl($instagramUrl, $header) {
	
		$options = array(
			// "ssl"=>array(
			// 	"verify_peer"=>false,
			// 	"verify_peer_name"=>false,
			// ),
			'http' => array(
				'method' => "GET",
				'header' => $header
			)
		);
		
		$context = stream_context_create($options);
		$content = file_get_contents($instagramUrl, false, $context);
		
		return $content;
		
	}

    public static function fetchById($array) {
		
		@ $instagramId = $array['id'];
		if(!isset($instagramId) || $instagramId == '') { $instagramId = '2890411760684296309'; }

		@ $base64images = $array['base64images'] ? $array['base64images'] : false;
		@ $base64videos = $array['base64videos'] ? $array['base64videos'] : false;

		@ $header = $array['header'] ? $array['header'] : false;
		@ $file = $array['file'] ? $array['file'] : 'instagram-cache-byid-' . $instagramId . '.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;
		@ $prettyfy = ($array['pretty']) ? JSON_PRETTY_PRINT : false;
		
		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			$instagramUrl = 'https://i.instagram.com/api/v1/media/' . $instagramId . '/info/';

			$content = self::magicCurl($instagramUrl, $header);

			if($content) {

				$json = json_decode($content, TRUE);
				
				$item = $json['items'][0];
				$image = false;

				if(
					$json &&
					$json['items'] &&
					$json['items'][0] &&
					$json['items'][0]['image_versions2'] &&
					$json['items'][0]['image_versions2']['candidates'] &&
					$json['items'][0]['image_versions2']['candidates'][0] &&
					$json['items'][0]['image_versions2']['candidates'][0]['url']
				) {
					
					$image = $json['items'][0]['image_versions2']['candidates'][0];
					
				} else if(
					$json &&
					$json['items'] &&
					$json['items'][0] &&
					$json['items'][0]['carousel_media'] &&
					$json['items'][0]['carousel_media'][0] &&
					$json['items'][0]['carousel_media'][0]['image_versions2'] &&
					$json['items'][0]['carousel_media'][0]['image_versions2']['candidates'] &&
					$json['items'][0]['carousel_media'][0]['image_versions2']['candidates'][0] &&					
					$json['items'][0]['carousel_media'][0]['image_versions2']['candidates'][0]['url']
				) {
					
					$image = $json['items'][0]['carousel_media'][0]['image_versions2']['candidates'][0];
					
				}

				if($item && $image) {

					$temp = array();
					
					$comments = false;
					if($item['comments'] && count($item['comments'])) {
						for($i = 0; $i < count($item['comments']); $i++) {
							$value = $item['comments'][$i];
							$comments[] = array(
								'time' => @ $value['created_at_utc'],
								'text' => @ $value['text'],
								'user' => array(
									'username' => @ $value['user']['username'],
									'fullName' => @ $value['user']['full_name'],
									'imageUrl' => @ $value['user']['profile_pic_url'],
								)
							);
						}
					}

					$newResult = array(
						'id' => @ $instagramId,
						'width' => @ $image['width'],
						'height' => @ $image['height'],
						'imageUrl' => @ $image['url'],
						'time' => @ $item['taken_at'],
						'topLikers' => @ $item['top_likers'],
						'likes' => @ $item['like_count'],
						'commentCount' => @ $item['comment_count'] ? $item['comment_count'] : 0,
						'comments' => @ $comments,
						'link' => @ 'https://www.instagram.com/p/' . $item['code'] . '/',
						'text' => @ $item['caption']['text'],
						'user' => array(
							'username' => @ $item['user']['username'],
							'fullName' => @ $item['user']['full_name'],
							'imageUrl' => @ $item['user']['profile_pic_url'],
						)
					);

					if($base64images && $image['url']) $newResult['image'] = base64_encode(file_get_contents(@ $image['url']));

					$temp[] = $newResult;

					if(
						$temp &&
						count($temp)
					) {
					
						$temp = json_encode($temp, $prettyfy);
						
						$fp = fopen($file, 'w');
						fwrite($fp, $temp);
						fclose($fp);
					
					}
				
				}

			}

		}
		
		return file_get_contents($file);

	}

	public static function fetchByIdUrl($array) {
		
		@ $instagramId = $array['id'];
		if(!isset($instagramId) || $instagramId == '') { $instagramId = 'Cgczi6qMuh1'; }
		
		$instagramUrl = 'https://www.instagram.com/p/' . $instagramId . '/';
		@ $header = $array['header'] ? $array['header'] : '';
		$header .= 'sec-fetch-site: same-origin' . "\r\n";

		@ $file = $array['file'] ? $array['file'] : 'instagram-cache-byurlid-' . $instagramId . '.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;

		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			$content = self::magicCurl($instagramUrl, $header);
			
			@ $contentArray = (explode('instagram://media?id=',$content));
			@ $contentArray = (explode('"',$contentArray[1]));
			
			@ $id = $contentArray[0];

			$array['id'] = $id;
			$array['file'] = $file;
			
			return self::fetchById($array);

		} else {

			return file_get_contents($file);

		}
		
	}

    public static function fetch($array) {

		@ $base64images = $array['base64images'] ? $array['base64images'] : false;
		@ $base64imagesCarousel = $array['base64imagesCarousel'] ? $array['base64imagesCarousel'] : false;
		@ $base64videos = $array['base64videos'] ? $array['base64videos'] : false;

		@ $maxImages = $array['maxImages'] && is_numeric($array['maxImages']) && $array['maxImages'] <= 12 ? $array['maxImages'] : false;
		@ $header = $array['header'] ? $array['header'] : false;
		@ $file = $array['file'] ? $array['file'] : 'instagram-cache.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;
		@ $prettyfy = ($array['pretty']) ? JSON_PRETTY_PRINT : false;
		@ $instagramId = $array['id'];
		
		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			if(!isset($instagramId) || $instagramId == '') { $instagramId = 'orsifrancesco'; }

			$instagramUrl = 'https://www.instagram.com/api/v1/users/web_profile_info/?username=' . $instagramId;

			$content = self::magicCurl($instagramUrl, $header);

			if($content) {

				$json = json_decode($content, TRUE);

				if(
					$json &&
					$json['data'] &&
					$json['data']['user'] &&
					$json['data']['user']['edge_owner_to_timeline_media'] &&
					$json['data']['user']['edge_owner_to_timeline_media']['edges']
				) {
				
					$items = $json['data']['user']['edge_owner_to_timeline_media']['edges'];
					
					if(
						$items &&
						count($items)
					) {

						$temp = array();

						$counter = count($items);
						if($maxImages) $counter = $maxImages;

						$imageUrl = false;

						for($i = 0; $i < $counter; $i++) {

							$imageUrl = @ $items[$i]['node']['display_url'];
							
							$newResult = array(
								'id' => @ $items[$i]['node']['id'],
								'time' => @ $items[$i]['node']['taken_at_timestamp'],
								'imageUrl' => $imageUrl,
								'likes' => @ $items[$i]['node']['edge_liked_by']['count'],
								'comments' => @ $items[$i]['node']['edge_media_to_comment']['count'],
								'link' => @ 'https://www.instagram.com/p/' . $items[$i]['node']['shortcode'] . '/',
								'text' => @ $items[$i]['node']['edge_media_to_caption']['edges'][0]['node']['text'],
							);

							if(
								@ $items[$i]['node']['location'] &&
								@ $items[$i]['node']['location']['name']
							) {
								$newResult['location'] = $items[$i]['node']['location']['name'];
							}

							if(
								@ $items[$i]['node']['edge_sidecar_to_children'] &&
								@ $items[$i]['node']['edge_sidecar_to_children']['edges'] &&
								@ $items[$i]['node']['edge_sidecar_to_children']['edges'][0]
							) {
								$carousel = array();
								$carouselNodes = $items[$i]['node']['edge_sidecar_to_children']['edges'];
								for($k = 0; $k < count($carouselNodes); $k++) {
									$newItem = array('imageUrl' => $carouselNodes[$k]['node']['display_url']);
									if($base64imagesCarousel) $newItem['image'] = base64_encode(file_get_contents(@  $carouselNodes[$k]['node']['display_url']));
									$carousel[] = $newItem;
								}
								$newResult['carousel'] = $carousel;
							}

							if($base64images) $newResult['image'] = base64_encode(file_get_contents(@  $items[$i]['node']['display_url']));

							if(
								@ $items[$i]['node']['is_video'] &&
								@ $items[$i]['node']['video_url']
							) {
								$newResult['videoUrl'] = $items[$i]['node']['video_url'];
								$newResult['videoViewCount'] = $items[$i]['node']['video_view_count'];

								if($base64videos) $newResult['video'] = base64_encode(file_get_contents(@  $items[$i]['node']['video_url']));

							}

							if($imageUrl) $temp[] = $newResult;
							
						}
						
						if(
							$temp &&
							count($temp)
						) {
						
							$temp = json_encode($temp, $prettyfy);
							
							$fp = fopen($file, 'w');
							fwrite($fp, $temp);
							fclose($fp);
						
						}
					
					}
					
				}

			}

		}
		
		return file_get_contents($file);

	}

	public static function fetchByTag($array) {

		@ $base64images = $array['base64images'] ? $array['base64images'] : false;
		@ $base64imagesCarousel = $array['base64imagesCarousel'] ? $array['base64imagesCarousel'] : false;
		@ $base64videos = $array['base64videos'] ? $array['base64videos'] : false;

		@ $group = $array['group'] ? $array['group'] : false;
		if(!$group || $group != 'recent' && $group != 'top') $group = 'recent';
		@ $maxImages = $array['maxImages'] && is_numeric($array['maxImages']) && $array['maxImages'] <= 12 ? $array['maxImages'] : false;
		@ $header = $array['header'] ? $array['header'] : false;
		@ $file = $array['file'] ? $array['file'] : 'instagram-cache.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;
		@ $prettyfy = ($array['pretty']) ? JSON_PRETTY_PRINT : false;
		@ $instagramId = $array['id'];
		
		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			if(!isset($instagramId) || $instagramId == '') { $instagramId = 'love'; }

			$instagramUrl = 'https://www.instagram.com/api/v1/tags/web_info/?tag_name=' . $instagramId;

			$content = self::magicCurl($instagramUrl, $header);

			if($content) {

				$json = json_decode($content, TRUE);

				if(
					$json &&
					$json['data'] &&
					$json['data'][$group] &&
					$json['data'][$group]['sections']
				) {
				
					$sections = $json['data'][$group]['sections'];
					
					if(
						$sections &&
						count($sections)
					) {

						$temp = array();
						$counter = 0;

						for($i = 0; $i < count($sections); $i++) {

							$medias = $sections[$i]['layout_content']['medias'];

							for($j = 0; $j < count($medias); $j++) {

								if($counter < $maxImages) {

									$media = $medias[$j]['media'];

									$imageUrl = false;
									if(
										@ $media['image_versions2'] &&
										@ $media['image_versions2']['candidates'] &&
										@ $media['image_versions2']['candidates'][0] &&
										@ $media['image_versions2']['candidates'][0]['url']
									) {
										$imageUrl = $media['image_versions2']['candidates'][0]['url'];
									}

									$carousel = array();
									if(
										@ $media['carousel_media'] &&
										@ $media['carousel_media'][0] &&
										@ $media['carousel_media'][0]['image_versions2'] &&
										@ $media['carousel_media'][0]['image_versions2']['candidates'] &&
										@ $media['carousel_media'][0]['image_versions2']['candidates'][0] &&
										@ $media['carousel_media'][0]['image_versions2']['candidates'][0]['url']
									) {
										
										$imageUrl = $media['carousel_media'][0]['image_versions2']['candidates'][0]['url'];

										for($x = 0; $x < count($media['carousel_media']); $x++) {
											
											$newItem = array('imageUrl' => $media['carousel_media'][$x]['image_versions2']['candidates'][0]['url']);
											if($base64imagesCarousel) $newItem['image'] = base64_encode(file_get_contents(@  $media['carousel_media'][$x]['image_versions2']['candidates'][0]['url']));
											$carousel[] = $newItem;
										
										}

									}

									$newResult = array(
										'id' => @ $media['id'],
										'time' => @ $media['taken_at'],
										'imageUrl' => @ $imageUrl,
										'link' => @ 'https://www.instagram.com/p/' . $media['code'] . '/',
										'text' => @ $media['caption']['text'],
									);

									if(
										@ $media['location'] &&
										@ $media['location']['name']
									) {
										$newResult['location'] = $media['location']['name'];
									}

									if(count($carousel)) $newResult['carousel'] = $carousel;
		
									if($base64images) $newResult['image'] = base64_encode(file_get_contents(@ $imageUrl));
		
									if(
										@ $media['video_versions'] &&
										@ $media['video_versions'][0] &&
										@ $media['video_versions'][0]['url']
									) {
										$newResult['videoUrl'] = $media['video_versions'][0]['url'];
										if($base64videos) $newResult['video'] = base64_encode(file_get_contents($media['video_versions'][0]['url']));
									}

									if($imageUrl) $temp[] = $newResult;

									$counter++;

								}


							}
							
						}
						
						if(
							$temp &&
							count($temp)
						) {
						
							$temp = json_encode($temp, $prettyfy);
							
							$fp = fopen($file, 'w');
							fwrite($fp, $temp);
							fclose($fp);
						
						}
					
					}
					
				}

			}

		}
		
		return file_get_contents($file);

	}

}

?>