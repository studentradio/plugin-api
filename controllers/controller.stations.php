<?php

class JSON_API_stations_Controller {

  public function hello_world() {
	  global $json_api;
	  //$json_api->error("You must include a 'cookie' authentication cookie. Use the `create_auth_cookie` Auth API method.");

    return array(
      "message" => "Hello, world, you are".get_current_user_id()
    );
  }

}
