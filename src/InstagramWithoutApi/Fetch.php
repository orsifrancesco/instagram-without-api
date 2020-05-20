<?php 

namespace InstagramWithoutApi;

class Fetch {

    public static function fetch($array) {
	
		@ $file = $array['file'] ? $array['file'] : 'instagram-cache.json';
		@ $time = isset($array['time']) ? $array['time'] : 3600;
		@ $prettyfy = ($array['pretty']) ? JSON_PRETTY_PRINT : false;
		@ $instagramId = $array['id'];
		@ $instagramTag = $array['tag'];
		
		if(
			!isset($file) ||
			!is_readable($file) ||
			(filemtime($file) < (time() - $time))
		) {

			if(!isset($instagramId) || $instagramId == '') { $instagramId = 'orsifrancesco'; }

			$instagramUrl = 'https://www.instagram.com/' . $instagramId . '/';
			
			if(isset($instagramTag) && $instagramTag != '') { $instagramUrl = 'https://www.instagram.com/explore/tags/' . $instagramTag . '/?hl=en'; }

			$content = file_get_contents($instagramUrl);

			$page = explode('<script type="text/javascript">window._sharedData =',$content);
			$page = $page[1];
			$page = explode(';</script>',$page);
			$page = trim($page[0]);
			
			if(
				$page &&
				$page != ''
			) {

				$pageDecoded = json_decode($page, TRUE);
				
				if(
					$pageDecoded &&
					count($pageDecoded)
				) {
					
					if(
						isset($pageDecoded['entry_data']) &&
						isset($pageDecoded['entry_data']['ProfilePage']) &&
						isset($pageDecoded['entry_data']['ProfilePage'][0]) &&
						isset($pageDecoded['entry_data']['ProfilePage'][0]['graphql']) &&
						isset($pageDecoded['entry_data']['ProfilePage'][0]['graphql']['user']) &&
						isset($pageDecoded['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']) &&
						isset($pageDecoded['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'])
					) {

						$pageDecoded = $pageDecoded['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];

					} else if(
						isset($pageDecoded['entry_data']) &&
						isset($pageDecoded['entry_data']['TagPage']) &&
						isset($pageDecoded['entry_data']['TagPage'][0]) &&
						isset($pageDecoded['entry_data']['TagPage'][0]['graphql']) &&
						isset($pageDecoded['entry_data']['TagPage'][0]['graphql']['hashtag']) &&
						isset($pageDecoded['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']) &&
						isset($pageDecoded['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'])
					) {
					
						$pageDecoded = $pageDecoded['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
						
					}

					$temp = array();

					for($i = 0; $i < count($pageDecoded); $i++) {
						
						$temp[] = array(
							'id' => @ $pageDecoded[$i]['node']['id'],
							'time' => @ $pageDecoded[$i]['node']['taken_at_timestamp'],
							'image' => @ $pageDecoded[$i]['node']['display_url'],
							'likes' => @ $pageDecoded[$i]['node']['edge_liked_by']['count'],
							'comments' => @ $pageDecoded[$i]['node']['edge_media_to_comment']['count'],
							'link' => @ 'https://www.instagram.com/p/' . $pageDecoded[$i]['node']['shortcode'] . '/',
							'text' => @ $pageDecoded[$i]['node']['edge_media_to_caption']['edges'][0]['node']['text'],
						);
						
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
		
		return file_get_contents($file);

	}
}

?>