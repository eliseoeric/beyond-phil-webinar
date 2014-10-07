<?php 
/**
*	Beyond Philosophy Citirix Webinar Intergration
*
*	@package	BPCW
*	@author 	Eric Eliseo
*	@license	GPL-2.0+
*	@link 		http://thinkgeneric.com
*	@copyright	2014 Eric Eliseo
*/

/**
*	The core plugin class for BPCW
*
*	@package BPCW
*	@author Eric Eliseo <eric@thinkgeneric.com>
*/
class BP_Citrix_Webinar {
	private static $instance;

	private function __construct(){
		$this->init();
		$this->resister_admin();
		
	}

	public static function get_instance(){
		if(null == self::$instance ){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function init(){
		// let's get language support going, if you need it
  		load_theme_textdomain( 'BPCW', plugin_dir_url( __FILE__ ) . '/library/translation' );
  		// let's add our custom query variables to the wp_query list
  		add_filter('query_vars', array($this, 'add_query_vars_filter'));

  		// Include Classes
		include_once('BP_Post_Handler.php');
		include_once('BP_Meta_Box_Handler.php');
		include_once('BP_Webinar_Admin.php');
		include_once('BP_Shortcode_Manager.php');

		//Init Classes
		BP_Post_Handler::init();
		BP_Meta_Box_Handler::init();
		BP_Shortcode_Manager::init();
	}


	/*
	* expose custom query variable to WP_Query
	*/
	public function add_query_vars_filter($vars){
		$vars[] = "webinar_id";
		return $vars;
	}

	public function resister_admin(){
		// add the admin options page
		add_action('admin_menu', array($this, 'bp_citrix_cred_menu'));

		// add the admin settings and such
		add_action('admin_init', array($this, 'plugin_admin_init'));
	}

	/*
	* Add the plugin settings to the admin menu.
	*/
	function plugin_admin_init(){
		register_setting('bpcw_options', 'bpcw_options', array($this, 'bpcw_options_validate'));
		add_settings_section('bpcw_main', 'Main Settings', array($this, 'bpcw_sections_text'), 'bpcw-settings');
		add_settings_field('bpcw_webinar_login', 'GotoWebinar Username', array($this, 'bpcw_webinar_login'), 'bpcw-settings', 'bpcw_main');
		add_settings_field('bpcw_webinar_password', 'GotoWebinar Password', array($this, 'bpcw_webinar_password'), 'bpcw-settings', 'bpcw_main');
		add_settings_field('bpcw_webinar_client_id', 'GotoWebinar Client API ID', array($this, 'bpcw_webinar_client_id'), 'bpcw-settings', 'bpcw_main');
		add_settings_field('bpcw_webinar_client_id', 'GotoWebinar Access Token', array($this, 'bpcw_webinar_access_token'), 'bpcw-settings', 'bpcw_main');
		add_settings_field('bpcw_webinar_organizer_id', 'GotoWebinar Organizer ID', array($this, 'bpcw_webinar_organizer_id'), 'bpcw-settings', 'bpcw_main');

	}

	/*
	* Input field validation callback
	*/
	function bpcw_options_validate($input){
		$newinput['bpcw_webinar_login'] = trim($input['bpcw_webinar_login']);
		$newinput['bpcw_webinar_password'] = trim($input['bpcw_webinar_password']);
		$newinput['bpcw_webinar_client_id'] = trim($input['bpcw_webinar_client_id']);
		$newinput['bpcw_webinar_access_token'] = trim($input['bpcw_webinar_access_token']);
		$newinput['bpcw_webinar_organizer_id'] = trim($input['bpcw_webinar_organizer_id']);

		return $newinput;
	}

	/*
	* Creates the meu item within the settings top level menu
	*/
	function bp_citrix_cred_menu(){
		add_menu_page('Citrix Webinar Login', 'Citrix Webinar', 'manage_options', 'bpcw-settings', array($this, 'bpcw_options'), 'dashicons-admin-network');
	}

	/*
	* Renders the plugin options and settings screen
	*/
	function bpcw_options(){
		if(!current_user_can('manage_options')){
			wp_die(__('You do not have sufficent permissions to access this page.'));
		}
		?>
		<div class="wrap">
			<form action="options.php" method="post">
				<?php settings_fields('bpcw_options'); ?>
				<?php do_settings_sections('bpcw-settings'); ?>
				<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes"></p>
				<?php 
				// $options = get_option('bpcw_options');
				// echo $options['bpcw_webinar_access_token'];
				// $registrants = BP_Webinar_Admin::get_registrants(778810480); 
				// echo count($registrants);

				var_dump(BP_Webinar_Admin::create_registrant(360078592));
				?>
			</form>
		</div>
		<?php

	}

	/*
	* Renders the instruction text
	*/

	function bpcw_sections_text(){
		echo '<p>Please enter the Citrix Goto Webinar username and password below.';
	}

	/*
	* Renders the username field
	*/

	function bpcw_webinar_login(){
		$options = get_option('bpcw_options');
		echo "<input id='bpcw_webinar_login' name='bpcw_options[bpcw_webinar_login]' size='30' type='text' value='{$options['bpcw_webinar_login']}' />";
	}

	/*
	* Renders the client api id field
	*/

	function bpcw_webinar_client_id(){
		$options = get_option('bpcw_options');
		echo "<input id='bpcw_webinar_client_id' name='bpcw_options[bpcw_webinar_client_id]' size='30' type='text' value='{$options['bpcw_webinar_client_id']}' />";
	}

	/*
	*  Renders the password field
	*/
	function bpcw_webinar_password(){
		$options = get_option('bpcw_options');
		echo "<input id='bpcw_webinar_password' name='bpcw_options[bpcw_webinar_password]' size='30' type='password' value='{$options['bpcw_webinar_password']}' />";
	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_webinar_access_token(){
		$options = get_option('bpcw_options');
		if($options['bpcw_webinar_access_token']==null){
			$oauth = BP_Webinar_Admin::get_oAuth();
			$options['bpcw_webinar_access_token'] = $oauth['access_token'];
		}
		
		echo "<input id='bpcw_webinar_access_token' name='bpcw_options[bpcw_webinar_access_token]' size='30' type='text' value='{$options['bpcw_webinar_access_token']}' />";
	}

	/*
	* Gets the access token for citrix api and stores it as wp_option
	*/
	function bpcw_webinar_organizer_id(){
		$options = get_option('bpcw_options');
		if($options['bpcw_webinar_organizer_id'] == null){
			$oauth = BP_Webinar_Admin::get_oAuth();
			$options['bpcw_webinar_organizer_id'] = $oauth['organizer_key'];
		}
		
		echo "<input id='bpcw_webinar_organizer_id' name='bpcw_options[bpcw_webinar_organizer_id]' size='30' type='text' value='{$options['bpcw_webinar_organizer_id']}' />";
	}


	public static function isWebinarSet($the_webinar){
		if(empty($the_webinar)){
			return false;
		} else {
			return true;
		}
	}
	// What are we actually sending?  The is webinar set function is not working.
	public static function formatWebinarDate($the_webinar){
		if(BP_Citrix_Webinar::isWebinarSet($the_webinar)){
			$when = date_create($the_webinar['times'][0]['startTime']);
			$formated = date_format($when, 'Y-m-d H:i:s');
			return $formated;
		} else{
			return false;
		}
		
	}

	public static function isWebinarOver($the_webinar){
		$now = date('Y-m-d H:i:s');
		$when = BP_Citrix_Webinar::formatWebinarDate($the_webinar);
		if( BP_Citrix_Webinar::isWebinarSet($the_webinar) && $now > $when ){
			return true;
		} else {
			return false;
		}
	}





}

 ?>