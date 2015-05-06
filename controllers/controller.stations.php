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
				"page_name" => $slug
			);
			$query = new WP_Query($args);
			return $query;
		} else {
			$json_api->error("You must include either an 'id' or a 'slug' variable.");
		}

	}
	
}
