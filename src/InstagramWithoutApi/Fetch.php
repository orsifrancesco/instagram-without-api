<?php 

namespace InstagramWithoutApi;

class Fetch {

    public static function fetch($array) {

		$base64images = true;
		@ $maxImages = $array['maxImages'] && is_numeric($array['maxImages']) && $array['maxImages'] <= 12 ? $array['maxImages'] : false;
		@ $header = $array['header'] ? $array['header'] : false;
		@ $file = $array['file'] ? $array['file'] : 'instagram-cache.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;
		@ $prettyfy = ($array['pretty']) ? JSON_PRETTY_PRINT : false;
		@ $instagramId = $array['id'];

		function magicCurl($instagramUrl, $header) {
			
			$options = array(
				'http' => array(
					'method' => "GET",
					'header' => $header
				)
			);
			
			$context = stream_context_create($options);
			$content = file_get_contents($instagramUrl, false, $context);
			
			return $content;
			
		}
		
		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			if(!isset($instagramId) || $instagramId == '') { $instagramId = 'orsifrancesco'; }

			$instagramUrl = 'https://i.instagram.com/api/v1/users/web_profile_info/?username=' . $instagramId;

			$content = magicCurl($instagramUrl, $header);

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