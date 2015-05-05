<?php
/*
	Plugin Name: SRA API
	Plugin URI: http://www.fredbradley.co.uk/?utm_source=client&utm_medium=wordpress-plugin&utm_content=META&utm_campaign=wordpress-plugin
	Description: A Custom built plugin that develops the SRA API
	Version: 1.0
	Author: Fred Bradley
	Author URI: http://www.fredbradley.co.uk/?utm_source=client&utm_medium=wordpress-plugin&utm_content=META&utm_campaign=wordpress-plugin
	License: GPL
	Copyright: Fred Bradley
*/

class SRAAPI {
	public	$new_controllers = array();
	
	function __construct() {
		
		$this->new_controllers = array("stations", "invoices");
		
		add_filter('json_api_controllers', array($this,'add_controllers'));
		add_filter('json_api_encode', array($this,'my_encode_kittens'));

		// Add Controllers Paths
//		add_filter('json_api_stations_controller_path', array($this,'stations_controller_path'));

		foreach ($this->new_controllers as $controller) {
			add_filter('json_api_'.$controller.'_path', dirname(__FILE__).'/controllers/controller.'.$controller.'.php');
		}
	}
	
	function add_controllers($controllers) {
		return array_merge($controllers, $this->new_controllers);
		$controllers[] = "stations";
		$controllers[] = "invoices";
		return $controllers;
	}

	function stations_controller_path($default_path) {
		return dirname(__FILE__).'/controller.stations.php';
	}
	
	
	function my_encode_kittens($response) {
		if (isset($response['posts'])) {
			foreach ($response['posts'] as $post) {
				$this->my_add_kittens($post); // Add kittens to each post
			}
		} else if (isset($response['post'])) {
			$this->my_add_kittens($response['post']); // Add a kittens property
		}
		return $response;
	}
	
	function my_add_kittens(&$post) {
		$post->kittens = 'Kittens!';
	}
}
$myapi = new SRAAPI();