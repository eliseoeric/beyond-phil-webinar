<?php 

class BP_Meta_Box_Handler{
	private static $instance;

	private function __construct(){
		add_filter( 'cmb_meta_boxes', array($this, 'register_webinar_metaboxes') );
		add_action( 'init', array($this, 'be_initialize_cmb_meta_boxes'), 9999 );
		
		
	}

	public static function init(){
		if(null == self::$instance ){
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function register_webinar_metaboxes($meta_boxes){
		$prefix = '_BPCW_'; // Prefix for all fields
	    $meta_boxes['details'] = array(
	        'id' => 'webinar_details',
	        'title' => 'Webinar Details',
	        'pages' => array('citrix_webinar'), // post type
	        'context' => 'normal',
	        'priority' => 'high',
	        'show_names' => true, // Show field names on the left
	        'fields' => array(
	            array(
	                'name' => 'Subheading',
	                'desc' => 'Subheading for Webinar',
	                'id' => $prefix . 'subheading',
	                'type' => 'text'
	            ),
	            array(
	            	'name' => 'Preamble',
	            	'desc' => 'Featured introduction to the webinar.',
	            	'id' => $prefix . 'preamble',
	            	'type' => 'textarea_small',
	            	),
	            array(
	            	'name' => 'Progam Details',
	            	'desc' => 'Progam details for the webinar.',
	            	'id' => $prefix . 'progam_details',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'Learing Objectives',
	            	'desc' => 'What are the learning objectives of this webinar?',
	            	'id' => $prefix . 'learning_objectives',
	            	'type' => 'wysiwyg'
	            	),
	            array(
	            	'name' => 'Registration Details',
	            	'desc' => 'Registration Details for the webinar.',
	            	'id' => $prefix . 'registeration_details',
	            	'type' => 'wysiwyg'
	            	),
	            array(
					'name' => 'Header Image',
					'desc' => 'This is the background image displayed behind the counter/tite',
					'id' => $prefix . 'header_image',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' )
					),
	        ),
	    );
		
		
		$staff_posts = get_posts(array('post_type' => 'oxy_staff','posts_per_page'   => -1,));
		$staff_array = array('0'=>'None');
		foreach ($staff_posts as $staff) {
				
				$staff_array[$staff->ID] = $staff->post_title;
			}

		

				

		$meta_boxes['sidebar_details'] = array(
			'id' => 'webinar_sidebar_details',
			'title' => 'Webinar Siebar Details',
			'pages' => array('citrix_webinar'),
			'context' => 'side',
			'priority' => 'default',
			'show_names' => true,
			'fields' => array(
				array(
					'name' => 'Register Link',
					'desc' => 'Register Link for Webinar',
					'id' => $prefix . 'register_link',
					'type' => 'text'
					),
				array(
					'name' => 'Webinar',
					'desc' => 'The webinar',
					'id' => $prefix . 'webinar_id',
					'type' => 'text',
					),
				array(
	            	'name' => 'Price',
	            	'desc' => 'The price of the webinar',
	            	'id' => $prefix . 'price',
	            	'type' => 'text'
	            	),
				array(
					'name' => 'PDF File',
					'desc' => 'File used for download link',
					'id' => $prefix . 'pdf_download',
					'type' => 'file',
					'allow' => array( 'url', 'attachment' )
					),
				array(
					'name' => 'Video Link',
					'desc' => 'Link to hosted video of webinar',
					'id' => $prefix . 'video_link',
					'type' => 'text'
					),
				array(
				    'name'    => 'Key Presenter',
				    'desc'    => 'Select a speaker',
				    'id'      => $prefix . 'key_presenter',
				    'type'    => 'select',
				    'options' => $staff_array,
				    'default' => '0',
					),
				array(
				    'name'    => 'Secondary Presenter',
				    'desc'    => 'Select a speaker',
				    'id'      => $prefix . 'second_presenter',
				    'type'    => 'select',
				    'options' => $staff_array,
				    'default' => '0',
					),
				),
			);

	    return $meta_boxes;
		
	}

	public function be_initialize_cmb_meta_boxes() {
	    if ( !class_exists( 'cmb_Meta_Box' ) ) {
	        include_once( BPCW_DIR . '/library/CMB/init.php' );
    	}
	}
	
}

 ?>