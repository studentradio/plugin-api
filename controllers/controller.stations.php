<?php

class json_api_stations_controller {

	public function hello_world() {
		global $json_api;
		//$json_api->error("You must include a 'cookie' authentication cookie. Use the `create_auth_cookie` Auth API method.");

		return array(
			"message" => "Hello, world, you are".get_current_user_id()
		);
	}
	
	public function get_station() {
		global $json_api;
		extract($json_api->query->get(array('id', 'slug')));
		
		if ($id) {
			$post = get_post($id);
			return array(
				"status" => "ok",
				"station" => $post
			);
		} elseif ($slug) {
			$args = array(
				"post_type" => "station",
				"name" => $slug
			);
			$query = new WP_Query($args);
			if ($query->have_posts() && $query->post_count==1) { 
				$query->the_post();
				$result["station"] = $query->post;
				$result["post_count"] = $query->post_count;
				return $result;
			} else {
				$json_api->error("Could not find a station with the name '".$slug."'");
			}
		} else {
			$json_api->error("You must include either an 'id' or a 'slug' variable.");
		}

	}
	
}
