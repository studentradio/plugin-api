<?php

	/**
	 * SRAConnect class.
	 */
	class SRAConnect {
		public $loggedin = false;
		private $http = false;
		private $base = false;
		public $user_id = false;
		
		/**
		 * __construct function.
		 * 
		 * @access public
		 * @return void
		 */
		function __construct($api_url) {
			$url = parse_url($api_url);
			
			$this->http = $url["scheme"]."://".$url["host"];
			$this->base = $url["path"];
			
			if (isset($_GET['destroy'])) {
				session_destroy();
			}
			
			if (isset($_COOKIE['sra_cookie'])) {
				$result = $this->auth_check($_COOKIE['sra_cookie']);
				if ($result->status=="ok" && $result->valid===true) {
					$this->loggedin = true;
				}
			}
		}
		
		function get_stations() {
			$result = $this->api("/stations/hello_world/");
			return $result;
		}
		
		/**
		 * error function.
		 * 
		 * @access public
		 * @param mixed $result
		 * @return void
		 */
		function error($result) {
			error_log($result->error);
			return $result->error;
		}
		
		
		/**
		 * api function.
		 * 
		 * @access public
		 * @param mixed $endpoint
		 * @param array $params
		 * @return void
		 */
		function api($endpoint, array $params=array()) {
			$default_params = array(
				"cookie" => "none"
			);
			$params = array_merge($default_params, $params);

			$query = http_build_query($params);

			$form_url = $this->http.
					$this->base.
					$endpoint
					."?"
					.$query;
			
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $form_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
			$data = curl_exec($ch);
			$api_response = curl_getinfo($ch);
			curl_close($ch);
			 
			$json = json_decode($data);
			
			if ($api_response['http_code'] == "404") {
				$json = array("status" => "error", "error" => "404 API Not Found");
				$json = (object)$json;
				error_log("Could not connect to API");
			}
			
			return $json;
		}
		
		
		/**
		 * get_user function.
		 * 
		 * @access public
		 * @param mixed $cookie (default: null)
		 * @return void
		 */
		function get_user($cookie=null) {
			if ($cookie===null) {
				$cookie = $_COOKIE['sra_cookie'];
			}
			
			$api = $this->api("/user/get_user_meta/", array("cookie" => $cookie));

			return $api;
		}
		
		
		/**
		 * get_user_by_auth function.
		 * 
		 * @access public
		 * @param mixed $cookie (default: null)
		 * @return void
		 */
		function get_user_by_auth($cookie=null) {
			if ($cookie===null) {
				$cookie = $_COOKIE['sra_cookie'];
			}
			
			$api = $this->api("/user/get_currentuserinfo/", array("cookie" => $cookie));
			$this->user_id = $api->user->id;
		
			return $api;
		}
		
		
		/**
		 * auth_check function.
		 * 
		 * @access public
		 * @param mixed $cookie
		 * @return void
		 */
		function auth_check($cookie) {
			$api = $this->api("/auth/validate_auth_cookie/", array("cookie"=>$cookie));
			return $api;
		}
		
		
		/**
		 * save_cookie function.
		 * 
		 * @access public
		 * @param mixed $cookie
		 * @return void
		 */
		function save_cookie($cookie) {
			$_COOKIE['sra_cookie'] = $cookie;
		}
		
		
		/**
		 * login function.
		 * 
		 * @access public
		 * @param mixed $username
		 * @param mixed $password
		 * @return void
		 */
		function login($username, $password) {
			$result = $this->api("/auth/generate_auth_cookie/", array("username"=>$username, "password" => $password));
			
			if ($result->status == "ok"): 
				$this->save_cookie($result->cookie);
				$this->loggedin = true;
				$user = $result->user;
				
				
				$new_user['username'] = $user->username;
				$new_user['email'] = $user->email;
				$new_user['first_name'] = $user->firstname;
				$new_user['surname'] = $user->lastname;
				
				echo "Your name is: ".$new_user['first_name']." ".$new_user['surname'];
			else :
				echo $this->error($result);
			endif;
			
		}
		
		
	}
