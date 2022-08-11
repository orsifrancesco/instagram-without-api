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

		$base64images = true;
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

		$base64images = true;
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

			$instagramUrl = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $instagramId;

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

						for($i = 0; $i < $counter; $i++) {
							
							$newResult = array(
								'id' => @ $items[$i]['node']['id'],
								'time' => @ $items[$i]['node']['taken_at_timestamp'],
								'imageUrl' => @ $items[$i]['node']['display_url'],
								'likes' => @ $items[$i]['node']['edge_liked_by']['count'],
								'comments' => @ $items[$i]['node']['edge_media_to_comment']['count'],
								'link' => @ 'https://www.instagram.com/p/' . $items[$i]['node']['shortcode'] . '/',
								'text' => @ $items[$i]['node']['edge_media_to_caption']['edges'][0]['node']['text'],
							);

							if($base64images) $newResult['image'] = base64_encode(file_get_contents(@  $items[$i]['node']['display_url']));
							if(@ $items[$i]['node']['edge_sidecar_to_children']){
								$total_sidecars = count(@ $items[$i]['node']['edge_sidecar_to_children']['edges']);
								// start index @ 1 because 0 is the first image - aka imageUrl above.
								for($ii = 1; $ii < $total_sidecars; $ii++){
									if($items[$i]['node']['edge_sidecar_to_children']['edges'][$ii]['node']['__typename'] == 'GraphImage' 
										&& $items[$i]['node']['edge_sidecar_to_children']['edges'][$ii]['node']['display_url']){
										$newResult['morePics'][] = [
											'imageUrl' => @ $items[$i]['node']['edge_sidecar_to_children']['edges'][$ii]['node']['display_url'],
											'image' => $base64images ? base64_encode(file_get_contents(@ $items[$i]['node']['edge_sidecar_to_children']['edges'][$ii]['node']['display_url'])) : ''
										];
									}
								}
							}
							$temp[] = $newResult;
							
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
