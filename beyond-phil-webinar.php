<?php 
/**
*	Beyond Philosophy Citrix Webinar Intergration
*	
*	Wordpress Plugin intergrates Citirx Webinar Registration into 
* 	The Beyond Philosophy Site.
*
*
*	@package	BPCW
*	@author 	Eric Eliseo <eric@thinkgeneric.com>
*	@license	GLP-2.0+
*	@link 		http://thinkgeneric.com
*	@copyright	2014 Eric Eliseo
*
*	@wordpress-plugin
*	Plugin Name: Beyond Philosophy Citrix Webinar Intergartion 
*	Plugin URI: 
*	Description: Allows integration of Citrix Webinars Regisration for Beyond Philosophy Website
*	Version: 0.5
*	Author: Think Generic
*	Author URI: http://www.thinkgeneric.com
*/


//If this file is called directly, abort.
if(!defined('WPINC')){
	die;
}

define('BPCW_DIR', plugin_dir_path( __FILE__ ));


include_once( BPCW_DIR .'/models/BP_Citrix_Webinar.php');


BP_Citrix_Webinar::get_instance();

 ?>