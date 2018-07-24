<?php

class json_api_stations_controller {

	public function hello_world() {
		global $json_api;
		$json_api->error("You must include a 'cookie' authentication cookie. Use the `create_auth_cookie` Auth API method.");

		return array(
			"message" => "Hello, world, you are".get_current_user_id()
		);
	}
	public function members() {
		global $json_api;
		$args = array(
			"order" => "ASC",
			"orderby" => "title",
			"post_type"=>"station",
			"posts_per_page" => "-1",
			"meta_query" => array(
				"relation" => "OR",
				array(
					"key" => "sra_meta_station_membership_status",
					"value" => "paid"
				),
				array(
					"key" => "sra_meta_station_membership_status",
					"value"=>"invoiced"
				)
			)
		);
		$query = new WP_Query($args);
		if ($query->have_posts()):
			$x=0;
			while ($query->have_posts()):
				$query->the_post();
				$result[$x] = $query->post;
				$result[$x]->membership_status = get_post_meta(get_the_ID(), "sra_meta_station_membership_status", true);
				$result[$x]->uni = get_post_meta(get_the_ID(), "institution", true);
				$unsets = array("post_status", "post_author", "post_date", "post_date_gmt", "post_content", "post_excerpt", "comment_status", "ping_status", "post_password", "to_ping", "pinged", "post_modified", "post_modified_gmt", "post_parent", "guid", "menu_order", "post_mime_type", "comment_count", "post_content_filtered", "filter");
				foreach ($unsets as $unset):
					unset($result[$x]->$unset);
				endforeach;
				$x++;
			endwhile;
			return array(
				"status"=>"ok",
				"num_stations" => count($result),
				"stations" => $result
			);
		endif;
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
