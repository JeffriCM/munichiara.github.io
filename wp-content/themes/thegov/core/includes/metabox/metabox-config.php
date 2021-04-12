<?php 


if (!class_exists( 'RWMB_Loader' )) {
	return;
}
class Thegov_Metaboxes{
	public function __construct(){
		// Team Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'team_meta_boxes' ) );

		// Portfolio Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_post_settings_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'portfolio_related_meta_boxes' ) );

		// Blog Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_settings_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'blog_related_meta_boxes' ));
		
		add_filter( 'rwmb_meta_boxes', array( $this, 'event_related_meta_boxes' ));
		add_filter( 'rwmb_meta_boxes', array( $this, 'location_related_meta_boxes' ));
		
		// Page Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_layout_meta_boxes' ) );
		// Colors Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_color_meta_boxes' ) );		
		// Logo Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_logo_meta_boxes' ) );		
		// Header Builder Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_header_meta_boxes' ) );
		// Title Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_title_meta_boxes' ) );
		// Side Panel Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_side_panel_meta_boxes' ) );		

		// Social Shares Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_soc_icons_meta_boxes' ) );	
		// Footer Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_footer_meta_boxes' ) );				
		// Copyright Fields Metaboxes
		add_filter( 'rwmb_meta_boxes', array( $this, 'page_copyright_meta_boxes' ) );		
		
	}

	public function team_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Team Options', 'thegov' ),
	        'post_types' => array( 'team' ),
	        'context'    => 'advanced',
	        'fields'     => array(      
	        	array(
		            'name' => esc_html__( 'Member Department', 'thegov' ),
		            'id'   => 'department',
		            'type' => 'text',
		            'class' => 'field-inputs'
				),	        	
				array(
					'name' => esc_html__( 'Member Info', 'thegov' ),
		            'id'   => 'info_items',
		            'type' => 'social',
		            'clone' => true,
		            'sort_clone'     => true,
		            'options' => array(
						'name'    => array(
							'name' => esc_html__( 'Name', 'thegov' ),
							'type_input' => 'text'
						),
						'description' => array(
							'name' => esc_html__( 'Description', 'thegov' ),
							'type_input' => 'text'
						),
						'link' => array(
							'name' => esc_html__( 'Link', 'thegov' ),
							'type_input' => 'text'
						),
					),
		        ),		
		        array(
					'name'     => esc_html__( 'Social Icons', 'thegov' ),
					'id'          => "soc_icon",
					'type'        => 'select_icon',
					'options'     => WglAdminIcon()->get_icons_name(),
					'clone' => true,
					'sort_clone'     => true,
					'placeholder' => esc_html__( 'Select an icon', 'thegov' ),
					'multiple'    => false,
					'std'         => 'default',
				),
		        array(
					'name'             => esc_html__( 'Info Background Image', 'thegov' ),
					'id'               => "mb_info_bg",
					'type'             => 'file_advanced',
					'max_file_uploads' => 1,
					'mime_type'        => 'image',
				), 
	        ),
	    );
	    return $meta_boxes;
	}
	
	public function portfolio_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Portfolio Options', 'thegov' ),
	        'post_types' => array( 'portfolio' ),
	        'context'    => 'advanced',
	        'fields'     => array(
				array(
					'name'    => esc_html__( 'Featured Image', 'thegov' ),
					'id'      => "mb_portfolio_featured_image_conditional",
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				), 
				array(
					'name'    => esc_html__( 'Featured Image Settings', 'thegov' ),
					'id'      => "mb_portfolio_featured_image_type",
					'type'    => 'button_group',
					'options' => array(
						'off'  => esc_html__( 'Off', 'thegov' ),
						'replace'  => esc_html__( 'Replace', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'off',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_portfolio_featured_image_conditional','=','custom')
							),
						),
					),
				),
				array(
					'name' => esc_html__( 'Featured Image Replace', 'thegov' ),
					'id'   => "mb_portfolio_featured_image_replace",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_portfolio_featured_image_conditional','=','custom'),
							array( 'mb_portfolio_featured_image_type', '=', 'replace' ),
						)),
					),
				),

				array(
					'id'   => 'mb_portfolio_title',
					'name' => esc_html__( 'Show Title on single', 'thegov' ),
					'type' => 'switch',
					'std'  => 'true',
				),	
				array(
					'id'   => 'mb_portfolio_link',
					'name' => esc_html__( 'Add Custom Link for Portfolio Grid', 'thegov' ),
					'type' => 'switch',
				),
				array(
                    'name' => esc_html__( 'Custom Url for Portfolio Grid', 'thegov' ),
                    'id'   => 'portfolio_custom_url',
                    'type' => 'text',
					'class' => 'field-inputs',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_link','=','1')
						), ),
					),
                ),
                array(
                    'id'   => 'portfolio_custom_url_target',
                    'name' => esc_html__( 'Open Custom Url in New Window', 'thegov' ),
                    'type' => 'switch',
                    'std' => 'true',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_link','=','1')
						), ),
					),
                ),
				array(
					'name' => esc_html__( 'Info', 'thegov' ),
					'id'   => 'mb_portfolio_info_items',
					'type' => 'social',
					'clone' => true,
					'sort_clone' => true,
					'desc' => esc_html__( 'Description', 'thegov' ),
					'options' => array(
						'name'    => array(
							'name' => esc_html__( 'Name', 'thegov' ),
							'type_input' => 'text'
							),
						'description' => array(
							'name' => esc_html__( 'Description', 'thegov' ),
							'type_input' => 'text'
							),
						'link' => array(
							'name' => esc_html__( 'Url', 'thegov' ),
							'type_input' => 'text'
							),
					),
		        ),		
		        array(
					'name'     => esc_html__( 'Info Description', 'thegov' ),
					'id'       => "mb_portfolio_editor",
					'type'     => 'wysiwyg',
					'multiple' => false,
					'desc' => esc_html__( 'Info description is shown in one row with a main info', 'thegov' ),
				),			
		        array(
					'name'     => esc_html__( 'Categories On/Off', 'thegov' ),
					'id'       => "mb_portfolio_single_meta_categories",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'yes'     => esc_html__( 'On', 'thegov' ),
						'no'      => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),			
		        array(
					'name'     => esc_html__( 'Date On/Off', 'thegov' ),
					'id'       => "mb_portfolio_single_meta_date",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'yes'     => esc_html__( 'On', 'thegov' ),
						'no'      => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),			
		        array(
					'name'     => esc_html__( 'Tags On/Off', 'thegov' ),
					'id'       => "mb_portfolio_above_content_cats",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'yes'     => esc_html__( 'On', 'thegov' ),
						'no'      => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),		
		        array(
					'name'     => esc_html__( 'Share Links On/Off', 'thegov' ),
					'id'       => "mb_portfolio_above_content_share",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'yes'     => esc_html__( 'On', 'thegov' ),
						'no'      => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),	
	        ),
	    );
	    return $meta_boxes;
	}

	public function portfolio_post_settings_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Portfolio Post Settings', 'thegov' ),
	        'post_types' => array( 'portfolio' ),
	        'context'    => 'advanced',
	        'fields'     => array(
				array(
					'name'     => esc_html__( 'Post Layout', 'thegov' ),
					'id'       => "mb_portfolio_post_conditional",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),        
				array(
					'name'     => esc_html__( 'Post Layout Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom')
						)),
					),
				),
				array(
					'name'    => esc_html__( 'Post Content Layout', 'thegov' ),
					'id'      => "mb_portfolio_single_type_layout",
					'type'    => 'button_group',
					'options' => array(
						'1' => esc_html__( 'Title First', 'thegov' ),
						'2' => esc_html__( 'Image First', 'thegov' ),
						'3' => esc_html__( 'Overlay Image', 'thegov' ),
						'4' => esc_html__( 'Overlay Image with Info', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => '1',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_post_conditional','=','custom')
						), ),
					),
				), 
				array(
					'name'     => esc_html__( 'Alignment', 'thegov' ),
					'id'       => "mb_portfolio_single_align",
					'type'     => 'button_group',
					'options'  => array(
						'left' => esc_html__( 'Left', 'thegov' ),
						'center' => esc_html__( 'Center', 'thegov' ),
						'right' => esc_html__( 'Right', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => 'left',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_portfolio_post_conditional','=','custom')
						), ),
					),
				), 
				array(
					'name' => esc_html__( 'Spacing', 'thegov' ),
					'id'   => 'mb_portfolio_single_padding',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
						)),
					),
					'std' => array(
						'padding-top' => '250',
						'padding-bottom' => '200'
					)
				),
				array(
					'id'   => 'mb_portfolio_parallax',
					'name' => esc_html__( 'Add Portfolio Parallax', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Prallax Speed', 'thegov' ),
					'id'   => "mb_portfolio_parallax_speed",
					'type' => 'number',
					'std'  => 0.3,
					'step' => 0.1,
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
							array('mb_portfolio_parallax','=',true),
						)),
					),
				),
				array(
					'id'   => 'mb_portfolio_single_resp',
					'name' => esc_html__( 'Responsive Layout', 'thegov' ),
					'type' => 'switch',
					'std'  => 'true',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
						)),
					)
				),	

				array(
					'name' => esc_html__( 'Screen breakpoint', 'thegov' ),
					'id'   => 'mb_portfolio_single_resp_breakpoint',
					'type' => 'number',
					'std'  => 768,
					'min'  => 1,
					'step' => 1,
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
							array('mb_portfolio_single_resp','=',true),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Padding Top/Bottom', 'thegov' ),
					'id'   => 'mb_portfolio_single_resp_padding',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'std' => array(
						'padding-top'    => '100',
						'padding-bottom' => '150',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_post_conditional','=','custom'),
							array('mb_portfolio_single_type_layout','!=','1'),
							array('mb_portfolio_single_type_layout','!=','2'),
							array('mb_portfolio_single_resp','=',true),
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function portfolio_related_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Related Portfolio', 'thegov' ),
	        'post_types' => array( 'portfolio' ),
	        'context'    => 'advanced',
	        'fields'     => array(
				array(
					'id'      => 'mb_portfolio_related_switch',
					'name'    => esc_html__( 'Portfolio Related', 'thegov' ),
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'on' => esc_html__( 'On', 'thegov' ),
						'off' => esc_html__( 'Off', 'thegov' ),
					),
					'inline'   => true,
					'multiple' => false,
					'std'      => 'default'
				),
				array(
					'name'     => esc_html__( 'Portfolio Related Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				),
	        	array(
					'id'   => 'mb_pf_carousel_r',
					'name' => esc_html__( 'Display items carousel for this portfolio post', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Title', 'thegov' ),
					'id'   => "mb_pf_title_r",
					'type' => 'text',
					'std'  => esc_html__( 'Related Portfolio', 'thegov' ),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				), 			
				array(
					'name' => esc_html__( 'Categories', 'thegov' ),
					'id'   => "mb_pf_cat_r",
					'multiple'    => true,
					'type' => 'taxonomy_advanced',
					'taxonomy' => 'portfolio-category',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				),     
				array(
					'name'    => esc_html__( 'Columns', 'thegov' ),
					'id'      => "mb_pf_column_r",
					'type'    => 'button_group',
					'options' => array(
						'2' => esc_html__( '2', 'thegov' ),
						'3' => esc_html__( '3', 'thegov' ),
						'4' => esc_html__( '4', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => '3',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				),  
				array(
					'name' => esc_html__( 'Number of Related Items', 'thegov' ),
					'id'   => "mb_pf_number_r",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 3,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_portfolio_related_switch','=','on')
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function blog_settings_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Post Settings', 'thegov' ),
	        'post_types' => array( 'post', 'event', 'location' ),
	        'context'    => 'advanced',
	        'fields'     => array(       
				array(
					'name'     => esc_html__( 'Post Layout Settings', 'thegov' ),
					'type'     => 'wgl_heading',
				),
				array(
					'name'    => esc_html__( 'Post Layout', 'thegov' ),
					'id'      => "mb_post_layout_conditional",
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),   	    
				array(
					'name'    => esc_html__( 'Post Layout Type', 'thegov' ),
					'id'      => "mb_single_type_layout",
					'type'    => 'button_group',
					'options' => array(
						'1' => esc_html__( 'Title First', 'thegov' ),
						'2' => esc_html__( 'Image First', 'thegov' ),
						'3' => esc_html__( 'Overlay Image', 'thegov' ),
					),
					'multiple' => false,
					'std'      => '1',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_post_layout_conditional','=','custom')
							),
						),
					),
				), 
				array(
					'name' => esc_html__( 'Spacing', 'thegov' ),
					'id'   => 'mb_single_padding_layout_3',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_post_layout_conditional','=','custom'),
							array('mb_single_type_layout','=','3'),
						)),
					),
					'std' => array(
						'padding-top' => '120',
						'padding-bottom' => '0'
					)
				),
				array(
					'id'   => 'mb_single_apply_animation',
					'name' => esc_html__( 'Apply Animation', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_post_layout_conditional','=','custom'),
							array('mb_single_type_layout','=','3'),
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Featured Image Settings', 'thegov' ),
					'type'     => 'wgl_heading',
				),  
				array(
					'name'    => esc_html__( 'Featured Image', 'thegov' ),
					'id'      => "mb_featured_image_conditional",
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				), 
				array(
					'name'    => esc_html__( 'Featured Image Settings', 'thegov' ),
					'id'      => "mb_featured_image_type",
					'type'    => 'button_group',
					'options' => array(
						'off'  => esc_html__( 'Off', 'thegov' ),
						'replace'  => esc_html__( 'Replace', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'off',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_featured_image_conditional','=','custom')
							),
						),
					),
				),
				array(
					'name' => esc_html__( 'Featured Image Replace', 'thegov' ),
					'id'   => "mb_featured_image_replace",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_featured_image_conditional','=','custom'),
							array( 'mb_featured_image_type', '=', 'replace' ),
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function blog_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = array(
			'title'      => esc_html__( 'Post Format Layout', 'thegov' ),
			'post_types' => array( 'post' ),
			'context'    => 'advanced',
			'fields'     => array(
				// Standard Post Format
				array(
					'name'  => esc_html__( 'Standard Post( Enabled only Featured Image for this post format)', 'thegov' ),
					'id'    => "post_format_standard",
					'type'  => 'static-text',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('formatdiv','=','0')
						), ),
					),
				),
				// Gallery Post Format  
				array(
					'name'  => esc_html__( 'Gallery Settings', 'thegov' ),
					'type'  => 'wgl_heading',
				),  
				array(
					'name'  => esc_html__( 'Add Images', 'thegov' ),
					'id'    => "post_format_gallery",
					'type'  => 'image_advanced',
					'max_file_uploads' => '',
				),
				// Video Post Format
				array(
					'name' => esc_html__( 'Video Settings', 'thegov' ),
					'type' => 'wgl_heading',
				), 
				array(
					'name' => esc_html__( 'Video Style', 'thegov' ),
					'id'   => "post_format_video_style",
					'type' => 'select',
					'options' => array(
						'bg_video' => esc_html__( 'Background Video', 'thegov' ),
						'popup' => esc_html__( 'Popup', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'bg_video',
				),	
				array(
					'name' => esc_html__( 'Start Video', 'thegov' ),
					'id'   => "start_video",
					'type' => 'number',
					'std'  => '0',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('post_format_video_style','=','bg_video'),
						), ),
					),
				),				
				array(
					'name' => esc_html__( 'End Video', 'thegov' ),
					'id'   => "end_video",
					'type' => 'number',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('post_format_video_style','=','bg_video'),
						), ),
					),
				),	
				array(
					'name' => esc_html__( 'oEmbed URL', 'thegov' ),
					'id'   => "post_format_video_url",
					'type' => 'oembed',
				),
				// Quote Post Format
				array(
					'name' => esc_html__( 'Quote Settings', 'thegov' ),
					'type' => 'wgl_heading',
				), 
				array(
					'name' => esc_html__( 'Quote Text', 'thegov' ),
					'id'   => "post_format_qoute_text",
					'type' => 'textarea',
				),
				array(
					'name' => esc_html__( 'Author Name', 'thegov' ),
					'id'   => "post_format_qoute_name",
					'type' => 'text',
				),			
				array(
					'name' => esc_html__( 'Author Position', 'thegov' ),
					'id'   => "post_format_qoute_position",
					'type' => 'text',
				),
				array(
					'name' => esc_html__( 'Author Avatar', 'thegov' ),
					'id'   => "post_format_qoute_avatar",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
				),
				// Audio Post Format
				array(
					'name' => esc_html__( 'Audio Settings', 'thegov' ),
					'type' => 'wgl_heading',
				), 
				array(
					'name' => esc_html__( 'oEmbed URL', 'thegov' ),
					'id'   => "post_format_audio_url",
					'type' => 'oembed',
				),
				// Link Post Format
				array(
					'name' => esc_html__( 'Link Settings', 'thegov' ),
					'type' => 'wgl_heading',
				), 
				array(
					'name' => esc_html__( 'URL', 'thegov' ),
					'id'   => "post_format_link_url",
					'type' => 'url',
				),
				array(
					'name' => esc_html__( 'Text', 'thegov' ),
					'id'   => "post_format_link_text",
					'type' => 'text',
				),
			)
		);
		return $meta_boxes;
	}

	public function blog_related_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Related Blog Post', 'thegov' ),
	        'post_types' => array( 'post' ),
	        'context'    => 'advanced',
	        'fields'     => array(   

	        	array(
					'name'    => esc_html__( 'Related Options', 'thegov' ),
					'id'      => "mb_blog_show_r",
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
						'off'  	  => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),        	
				array(
					'name' => esc_html__( 'Related Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_blog_show_r','=','custom')
						)),
					),
				), 
				array(
					'name' => esc_html__( 'Title', 'thegov' ),
					'id'   => "mb_blog_title_r",
					'type' => 'text',
					'std'  => esc_html__( 'Related Posts', 'thegov' ),
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r','=','custom')
						), ),
					),
				), 			
				array(
					'name' => esc_html__( 'Categories', 'thegov' ),
					'id'   => "mb_blog_cat_r",
					'multiple'    => true,
					'type' => 'taxonomy_advanced',
					'taxonomy' => 'category',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r','=','custom')
						), ),
					),
				),     
				array(
					'name' => esc_html__( 'Columns', 'thegov' ),
					'id'   => "mb_blog_column_r",
					'type' => 'button_group',
					'options' => array(
						'12' => esc_html__( '1', 'thegov' ),
						'6'  => esc_html__( '2', 'thegov' ),
						'4'  => esc_html__( '3', 'thegov' ),
						'3'  => esc_html__( '4', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => '6',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_blog_show_r','=','custom')
						), ),
					),
				),  
				array(
					'name' => esc_html__( 'Number of Related Items', 'thegov' ),
					'id'   => "mb_blog_number_r",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 2,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_blog_show_r','=','custom')
							),
						),
					),
				),
	        	array(
					'id'   => 'mb_blog_carousel_r',
					'name' => esc_html__( 'Display items carousel for this blog post', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_blog_show_r','=','custom')
							),
						),
					),
				),  
	        ),
	    );
	    return $meta_boxes;
	}

	public function page_layout_meta_boxes( $meta_boxes ) {

	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Page Layout', 'thegov' ),
	        'post_types' => array( 'page' , 'post', 'team', 'practice','portfolio', 'product', 'event', 'location' ),
	        'context'    => 'advanced',
	        'fields'     => array(
				array(
					'name'    => esc_html__( 'Page Sidebar Layout', 'thegov' ),
					'id'      => "mb_page_sidebar_layout",
					'type'    => 'wgl_image_select',
					'options' => array(
						'default' => get_template_directory_uri() . '/core/admin/img/options/1c.png',
						'none'    => get_template_directory_uri() . '/core/admin/img/options/none.png',
						'left'    => get_template_directory_uri() . '/core/admin/img/options/2cl.png',
						'right'   => get_template_directory_uri() . '/core/admin/img/options/2cr.png',
					),
					'std'     => 'default',
				),
				array(
					'name'     => esc_html__( 'Sidebar Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_sidebar_layout','!=','default'),
							array('mb_page_sidebar_layout','!=','none'),
						)),
					),
				),
				array(
					'name'        => esc_html__( 'Page Sidebar', 'thegov' ),
					'id'          => "mb_page_sidebar_def",
					'type'        => 'select',
					'placeholder' => 'Select a Sidebar',
					'options'     => thegov_get_all_sidebar(),
					'multiple'    => false,
					'attributes'  => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_sidebar_layout','!=','default'),
							array('mb_page_sidebar_layout','!=','none'),
						)),
					),
				),			
				array(
					'name'    => esc_html__( 'Page Sidebar Width', 'thegov' ),
					'id'      => "mb_page_sidebar_def_width",
					'type'    => 'button_group',
					'options' => array(	
						'9' => esc_html( '25%' ),
						'8' => esc_html( '33%' ),
					),
					'std'  => '9',
					'multiple'   => false,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_sidebar_layout','!=','default'),
							array('mb_page_sidebar_layout','!=','none'),
						)),
					),
				),
				array(
					'id'   => 'mb_sticky_sidebar',
					'name' => esc_html__( 'Sticky Sidebar On?', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_sidebar_layout','!=','default'),
							array('mb_page_sidebar_layout','!=','none'),
						)),
					),
				),
				array(
					'name'  => esc_html__( 'Sidebar Side Gap', 'thegov' ),
					'id'    => "mb_sidebar_gap",
					'type'  => 'select',
					'options' => array(	
						'def' => 'Default',
	                    '0'  => '0',     
	                    '15' => '15',     
	                    '20' => '20',     
	                    '25' => '25',     
	                    '30' => '30',     
	                    '35' => '35',     
	                    '40' => '40',     
	                    '45' => '45',     
	                    '50' => '50', 
					),
					'std'        => 'def',
					'multiple'   => false,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_sidebar_layout','!=','default'),
							array('mb_page_sidebar_layout','!=','none'),
						)),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_color_meta_boxes( $meta_boxes ) {

	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Page Colors', 'thegov' ),
	        'post_types' => array( 'page' , 'post', 'team', 'practice','portfolio', 'event', 'location' ),
	        'context'    => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Page Colors', 'thegov' ),
					'id'       => "mb_page_colors_switch",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'inline'   => true,
					'multiple' => false,
					'std'      => 'default',
				),
				array(
					'name' => esc_html__( 'Colors Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'General Theme Color', 'thegov' ),
	                'id'   => 'mb_page_theme_color',
	                'type' => 'color',
	                'std'  => '#00aa55',
					'js_options' => array( 'defaultColor' => '#00aa55' ),
	                'validate' => 'color',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
				),						
				array(
					'name' => esc_html__( 'Body Background Color', 'thegov' ),
	                'id'   => 'mb_body_background_color',
	                'type' => 'color',
	                'std'  => '#ffffff',
					'js_options' => array( 'defaultColor' => '#ffffff' ),
	                'validate'  => 'color',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),
				array(
					'name' => esc_html__( 'Scroll Up Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Button Background Color', 'thegov' ),
	                'id'   => 'mb_scroll_up_bg_color',
	                'type' => 'color',
	                'std'  => '#ff9e21',
					'js_options' => array( 'defaultColor' => '#ff9e21' ),
	                'validate'  => 'color',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),				
	            array(
					'name' => esc_html__( 'Button Arrow Color', 'thegov' ),
	                'id'   => 'mb_scroll_up_arrow_color',
	                'type' => 'color',
	                'std'  => '#ffffff',
					'js_options' => array( 'defaultColor' => '#ffffff' ),
	                'validate'  => 'color',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_colors_switch', '=', 'custom'),
						)),
					),
	            ),
	        )
	    );
	    return $meta_boxes;
	}

	public function page_logo_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Logo', 'thegov' ),
	        'post_types' => array( 'page', 'post', 'event', 'location' ),
	        'context'    => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Logo', 'thegov' ),
					'id'       => "mb_customize_logo",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'inline'   => true,
					'std'      => 'default',
				),
				array(
					'name' => esc_html__( 'Logo Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Header Logo', 'thegov' ),
					'id'   => "mb_header_logo",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'id'   => 'mb_logo_height_custom',
					'name' => esc_html__( 'Enable Logo Height', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
					    	array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Set Logo Height', 'thegov' ),
					'id'   => "mb_logo_height",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 50,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_logo','=','custom'),
							array('mb_logo_height_custom','=',true)
						)),
					),
				),
				array(
					'name' => esc_html__( 'Sticky Logo', 'thegov' ),
					'id'   => "mb_logo_sticky",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'id'   => 'mb_sticky_logo_height_custom',
					'name' => esc_html__( 'Enable Sticky Logo Height', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
					    	array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Set Sticky Logo Height', 'thegov' ),
					'id'   => "mb_sticky_logo_height",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom'),
							array('mb_sticky_logo_height_custom','=',true),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Mobile Logo', 'thegov' ),
					'id'   => "mb_logo_mobile",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'id'   => 'mb_mobile_logo_height_custom',
					'name' => esc_html__( 'Enable Mobile Logo Height', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
					    	array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Set Mobile Logo Height', 'thegov' ),
					'id'   => "mb_mobile_logo_height",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom'),
							array('mb_mobile_logo_height_custom','=',true),
						)),
					),
				),				
				array(
					'name' => esc_html__( 'Mobile Menu Logo', 'thegov' ),
					'id'   => "mb_logo_mobile_menu",
					'type' => 'image_advanced',
					'max_file_uploads' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'id'   => 'mb_mobile_logo_menu_height_custom',
					'name' => esc_html__( 'Enable Mobile Logo Height', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
					    	array('mb_customize_logo','=','custom')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Set Mobile Logo Height', 'thegov' ),
					'id'   => "mb_mobile_logo_menu_height",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_logo','=','custom'),
							array('mb_mobile_logo_menu_height_custom','=',true),
						)),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}	

	public function page_header_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Header', 'thegov' ),
	        'post_types' => array( 'page', 'post', 'portfolio', 'product', 'event', 'location' ),
	        'context'    => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Header Settings', 'thegov' ),
					'id'       => "mb_customize_header_layout",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'default', 'thegov' ),
						'custom'  => esc_html__( 'custom', 'thegov' ),
						'hide'    => esc_html__( 'hide', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),
	        	array(
					'name'     => esc_html__( 'Header Builder', 'thegov' ),
					'id'       => "mb_customize_header",
					'type'     => 'select',
					'options'  => thegov_get_custom_preset(),
					'multiple' => false,
					'std'      => 'default',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_header_layout','!=','hide')
						)),
					),
				),
				// It is works 
				array(
					'id'   => 'mb_menu_header',
					'name' => esc_html__( 'Menu ', 'thegov' ),
					'type' => 'select',
					'options'     => thegov_get_custom_menu(),
					'multiple'    => false,
					'std'         => 'default',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_header_layout','=','custom')
						)),
					),
				),
				array(
					'id'   => 'mb_header_sticky',
					'name' => esc_html__( 'Sticky Header', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array('mb_customize_header_layout', '=', 'custom')
						)),
					),
				),
	        )
		);
		return $meta_boxes;
	}

	public function page_title_meta_boxes( $meta_boxes ) {
		$meta_boxes[] = array(
			'title'      => esc_html__( 'Page Title', 'thegov' ),
			'post_types' => array( 'page', 'post', 'team', 'practice', 'portfolio', 'product', 'event', 'location' ),
			'context'    => 'advanced',
			'fields'     => array(
				array(
					'id'      => 'mb_page_title_switch',
					'name'    => esc_html__( 'Page Title', 'thegov' ),
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'on'      => esc_html__( 'On', 'thegov' ),
						'off'     => esc_html__( 'Off', 'thegov' ),
					),
					'std'      => 'default',
					'inline'   => true,
					'multiple' => false
				),
				array(
					'name' => esc_html__( 'Page Title Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array( 
					'id'   => 'mb_page_title_bg_switch',
					'name' => esc_html__( 'Use Background?', 'thegov' ),
					'type' => 'switch',
					'std'  => true,
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=' ,'on' )
						)),
					),
				),
				array(
					'id'         => 'mb_page_title_bg',
					'name'       => esc_html__( 'Background', 'thegov' ),
					'type'       => 'wgl_background',
				    'image'      => '',
				    'position'   => 'center bottom',
				    'attachment' => 'scroll',
				    'size'       => 'cover',
				    'repeat'     => 'no-repeat',
					'color'      => '#00aa55',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' ),
							array( 'mb_page_title_bg_switch', '=', true ),
						)),
					),
				),			
				array( 
					'name' => esc_html__( 'Height', 'thegov' ),
					'id'   => 'mb_page_title_height',
					'type' => 'number',
					'std'  => 360,
					'min'  => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' ),
							array( 'mb_page_title_bg_switch', '=', true ),
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Title Alignment', 'thegov' ),
					'id'       => 'mb_page_title_align',
					'type'     => 'button_group',
					'options'  => array(
						'left'   => esc_html__( 'left', 'thegov' ),
						'center' => esc_html__( 'center', 'thegov' ),
						'right'  => esc_html__( 'right', 'thegov' ),
					),
					'std'      => 'center',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=' ,'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Paddings Top/Bottom', 'thegov' ),
					'id'   => 'mb_page_title_padding',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'std' => array(
						'padding-top'    => '80',
						'padding-bottom' => '80',
					),
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=' ,'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Margin Bottom', 'thegov' ),
					'id'   => "mb_page_title_margin",
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'margin',
						'top'    => false,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'std' => array( 'margin-bottom' => '40' ),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'id'   => 'mb_page_title_border_switch',
					'name' => esc_html__( 'Border Top Switch', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Border Top Color', 'thegov' ),
					'id'   => 'mb_page_title_border_color',
					'type' => 'color',
					'std'  => '#e5e5e5',
					'js_options' => array(
						'defaultColor' => '#e5e5e5',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(						
							array('mb_page_title_border_switch','=',true)
						)),
					),
				),
				array(
					'id'   => 'mb_page_title_parallax',
					'name' => esc_html__( 'Parallax Switch', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Prallax Speed', 'thegov' ),
					'id'   => 'mb_page_title_parallax_speed',
					'type' => 'number',
					'std'  => 0.3,
					'step' => 0.1,
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_parallax','=',true ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'id'   => 'mb_page_change_tile_switch',
					'name' => esc_html__( 'Custom Page Title', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title', 'thegov' ),
					'id'   => 'mb_page_change_tile',
					'type' => 'text',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array( 'mb_page_change_tile_switch','=','1' ),
							array( 'mb_page_title_switch', '=', 'on' ),
						)),
					),
				),
				array(
					'id'   => 'mb_page_title_breadcrumbs_switch',
					'name' => esc_html__( 'Show Breadcrumbs', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Breadcrumbs Alignment', 'thegov' ),
					'id'       => 'mb_page_title_breadcrumbs_align',
					'type'     => 'button_group',
					'options'  => array(
						'left' => esc_html__( 'left', 'thegov' ),
						'center' => esc_html__( 'center', 'thegov' ),
						'right' => esc_html__( 'right', 'thegov' ),
					),
					'std'      => 'center',
					'multiple' => false,
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch','=','on' ),
							array( 'mb_page_title_breadcrumbs_switch', '=', '1' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title Typography', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title Font', 'thegov' ),
					'id'   => 'mb_page_title_font',
					'type' => 'wgl_font',
					'options' => array(
						'font-size' => true,
						'line-height' => true,
						'font-weight' => false,
						'color' => true,
					),
					'std' => array(
						'font-size' => '52',
						'line-height' => '52',
						'color' => '#fefefe',
					),
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch', '=', 'on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title Breadcrumbs Font', 'thegov' ),
					'id'   => 'mb_page_title_breadcrumbs_font',
					'type' => 'wgl_font',
					'options' => array(
						'font-size' => true,
						'line-height' => true,
						'font-weight' => false,
						'color' => true,
					),
					'std' => array(
						'font-size' => '16',
						'line-height' => '24',
						'color' => '#ffffff',
					),
					'attributes' => array(
					    'data-conditional-logic' => array( array(
							array( 'mb_page_title_switch','=','on' )
						)),
					),
				),
				array(
					'name' => esc_html__( 'Responsive Layout', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on')
						)),
					),
				),
				array(
					'id'   => 'mb_page_title_resp_switch',
					'name' => esc_html__( 'Responsive Layout On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Screen breakpoint', 'thegov' ),
					'id'   => 'mb_page_title_resp_resolution',
					'type' => 'number',
					'std'  => 768,
					'min'  => 1,
					'step' => 1,
				    'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Height', 'thegov' ),
					'id'   => 'mb_page_title_resp_height',
					'type' => 'number',
					'std'  => 230,
					'min'  => 0,
					'step' => 1,
				    'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Padding Top/Bottom', 'thegov' ),
					'id'   => 'mb_page_title_resp_padding',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'std' => array(
						'padding-top'    => '15',
						'padding-bottom' => '40',
					),
				    'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title Font', 'thegov' ),
					'id'   => 'mb_page_title_resp_font',
					'type' => 'wgl_font',
					'options' => array(
						'font-size' => true,
						'line-height' => true,
						'font-weight' => false,
						'color' => true,
					),
					'std' => array(
						'font-size' => '42',
						'line-height' => '60',
						'color' => '#fefefe',
					),
				    'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
						)),
					),
				),
				array(
					'id'   => 'mb_page_title_resp_breadcrumbs_switch',
					'name' => esc_html__( 'Show Breadcrumbs', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
				    'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
						)),
					),
				),
				array(
					'name' => esc_html__( 'Page Title Breadcrumbs Font', 'thegov' ),
					'id'   => 'mb_page_title_resp_breadcrumbs_font',
					'type' => 'wgl_font',
					'options' => array(
						'font-size' => true,
						'line-height' => true,
						'font-weight' => false,
						'color' => true,
					),
					'std' => array(
						'font-size' => '16',
						'line-height' => '24',
						'color' => '#ffffff',
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_page_title_switch','=','on'),
							array('mb_page_title_resp_switch','=','1'),
							array('mb_page_title_resp_breadcrumbs_switch','=','1'),
						)),
					),
				),
	        ),
	    );
	    return $meta_boxes;
	}

	public function page_side_panel_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Side Panel', 'thegov' ),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Side Panel', 'thegov' ),
					'id'       => "mb_customize_side_panel",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom' => esc_html__( 'Custom', 'thegov' ),
					),
					'multiple' => false,
					'inline'   => true,
					'std'      => 'default',
				),
				array(
					'name'     => esc_html__( 'Side Panel Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Content Type', 'thegov' ),
					'id'       => 'mb_side_panel_content_type',
					'type'     => 'button_group',
					'options'  => array(
						'widgets' => esc_html__( 'Widgets', 'thegov' ),
						'pages'   => esc_html__( 'Page', 'thegov' )		
					),
					'multiple' => false,
					'std'      => 'widgets',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),
				array(
	        		'name'        => 'Select a page',
					'id'          => 'mb_side_panel_page_select',
					'type'        => 'post',
					'post_type'   => 'side_panel',
					'field_type'  => 'select_advanced',
					'placeholder' => 'Select a page',
					'query_args'  => array(
					    'post_status'    => 'publish',
					    'posts_per_page' => - 1,
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_side_panel','=','custom'),
							array('mb_side_panel_content_type','=','pages')
						)),
					),
	        	),
				array(
					'name' => esc_html__( 'Paddings', 'thegov' ),
					'id'   => 'mb_side_panel_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => true,
						'bottom' => true,
						'left'   => true,
					),
					'std' => array(
						'padding-top'    => '105',
						'padding-right'  => '90',
						'padding-bottom' => '105',
						'padding-left'   => '90'
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),	

				array(
					'name' => esc_html__( 'Title Color', 'thegov' ),
					'id'   => "mb_side_panel_title_color",
					'type' => 'color',
					'std'  => '#ffffff',
					'js_options' => array(
						'defaultColor' => '#ffffff',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(						
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),						
				array(
					'name' => esc_html__( 'Text Color', 'thegov' ),
					'id'   => "mb_side_panel_text_color",
					'type' => 'color',
					'std'  => '#313538',
					'js_options' => array(
						'defaultColor' => '#313538',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(						
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),				
				array(
					'name' => esc_html__( 'Background Color', 'thegov' ),
					'id'   => "mb_side_panel_bg",
					'type' => 'color',
					'std'  => '#ffffff',
					'alpha_channel' => true,
					'js_options' => array(
						'defaultColor' => '#ffffff',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(						
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Text Align', 'thegov' ),
					'id'       => "mb_side_panel_text_alignment",
					'type'     => 'button_group',
					'options'  => array(
						'left' => esc_html__( 'Left', 'thegov' ),
						'center' => esc_html__( 'Center', 'thegov' ),
						'right' => esc_html__( 'Right', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => 'center',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_customize_side_panel','=','custom')
						), ),
					),
				),
				array(
					'name' => esc_html__( 'Width', 'thegov' ),
					'id'   => "mb_side_panel_width",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 480,
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(						
							array('mb_customize_side_panel','=','custom')
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Position', 'thegov' ),
					'id'          => "mb_side_panel_position",
					'type'        => 'button_group',
					'options'     => array(
						'left' => esc_html__( 'Left', 'thegov' ),
						'right' => esc_html__( 'Right', 'thegov' ),
					),
					'multiple'    => false,
					'std'         => 'right',
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_customize_side_panel','=','custom')
							),
						),
					),
				),
	        )
	    );
	    return $meta_boxes;
	}	

	public function page_soc_icons_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Social Shares', 'thegov' ),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Social Shares', 'thegov' ),
					'id'          => "mb_customize_soc_shares",
					'type'        => 'button_group',
					'options'     => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'on' => esc_html__( 'On', 'thegov' ),
						'off' => esc_html__( 'Off', 'thegov' ),
					),
					'multiple'    => false,
					'inline'    => true,
					'std'         => 'default',
				),
				array(
					'name'     => esc_html__( 'Choose your share style.', 'thegov' ),
					'id'          => "mb_soc_icon_style",
					'type'        => 'button_group',
					'options'     => array(
						'standard' => esc_html__( 'Standard', 'thegov' ),
						'hovered' => esc_html__( 'Hovered', 'thegov' ),
					),
					'multiple'    => false,
					'std'         => 'standard',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),				
				array(
					'id'   => 'mb_soc_icon_position',
					'name' => esc_html__( 'Fixed Position On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),
				array( 
					'name' => esc_html__( 'Offset Top(in percentage)', 'thegov' ),
					'id'   => 'mb_soc_icon_offset',
					'type' => 'number',
					'std'  => 50,
					'min'  => 0,
					'step' => 1,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
					'desc' => esc_html__( 'Measurement units defined as "percents" while position fixed is enabled, and as "pixels" while position is off.', 'thegov' ),
				),
				array(
					'id'   => 'mb_soc_icon_facebook',
					'name' => esc_html__( 'Facebook Share On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),				
				array(
					'id'   => 'mb_soc_icon_twitter',
					'name' => esc_html__( 'Twitter Share On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),				
				array(
					'id'   => 'mb_soc_icon_linkedin',
					'name' => esc_html__( 'Linkedin Share On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),						
				array(
					'id'   => 'mb_soc_icon_pinterest',
					'name' => esc_html__( 'Pinterest Share On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),				
				array(
					'id'   => 'mb_soc_icon_tumblr',
					'name' => esc_html__( 'Tumblr Share On/Off', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_customize_soc_shares','=','on')
						)),
					),
				),
				
	        )
	    );
	    return $meta_boxes;
	}

	public function page_footer_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Footer', 'thegov' ),
	        'post_types' => array( 'page' ),
	        'context'    => 'advanced',
	        'fields'     => array(
	        	array(
					'name'     => esc_html__( 'Footer', 'thegov' ),
					'id'       => "mb_footer_switch",
					'type'     => 'button_group',
					'options'  => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'on'      => esc_html__( 'On', 'thegov' ),
						'off'     => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),
				array(
					'name'     => esc_html__( 'Footer Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				), 
				array(
					'id'   => 'mb_footer_add_wave',
					'name' => esc_html__( 'Add Wave', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Set Wave Height', 'thegov' ),
					'id'   => "mb_footer_wave_height",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 158,
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
					    	array('mb_footer_switch','=','on'),
							array('mb_footer_add_wave','=','1')
						)),
					),
				),
				array(
					'name'     => esc_html__( 'Content Type', 'thegov' ),
					'id'       => 'mb_footer_content_type',
					'type'     => 'button_group',
					'options'  => array(
						'widgets' => esc_html__( 'Default', 'thegov' ),
						'pages'   => esc_html__( 'Page', 'thegov' )		
					),
					'multiple' => false,
					'std'      => 'widgets',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				),
				array(
	        		'name'        => 'Select a page',
					'id'          => 'mb_footer_page_select',
					'type'        => 'post',
					'post_type'   => 'footer',
					'field_type'  => 'select_advanced',
					'placeholder' => 'Select a page',
					'query_args'  => array(
					    'post_status'    => 'publish',
					    'posts_per_page' => - 1,
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on'),
							array('mb_footer_content_type','=','pages')
						)),
					),
	        	),
				array(
					'name' => esc_html__( 'Paddings', 'thegov' ),
					'id'   => 'mb_footer_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => true,
						'bottom' => true,
						'left'   => true,
					),
					'std' => array(
						'padding-top'    => '0',
						'padding-right'  => '0',
						'padding-bottom' => '0',
						'padding-left'   => '0'
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				),	
				array(
					'name'       => esc_html__( 'Background', 'thegov' ),
					'id'         => "mb_footer_bg",
					'type'       => 'wgl_background',
				    'image'      => '',
				    'position'   => 'center center',
				    'attachment' => 'scroll',
				    'size'       => 'cover',
				    'repeat'     => 'no-repeat',			
					'color'      => '#ffffff',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				),
				array(
					'id'   => 'mb_footer_add_border',
					'name' => esc_html__( 'Add Border Top', 'thegov' ),
					'type' => 'switch',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_footer_switch','=','on')
						)),
					),
				),	
				array(
					'name' => esc_html__( 'Border Color', 'thegov' ),
					'id'   => "mb_footer_border_color",
					'type' => 'color',
					'std'  => '#e5e5e5',
					'alpha_channel' => true,
					'js_options' => array(
						'defaultColor' => '#e5e5e5',
					),
					'attributes' => array(
						'data-conditional-logic'  =>  array( array(		
							array('mb_footer_switch','=','on'),
							array('mb_footer_add_border','=','1'),
						)),
					),
				),			
	        ),
	     );
	    return $meta_boxes;
	}	

	public function page_copyright_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Copyright', 'thegov' ),
	        'post_types' => array( 'page' ),
	        'context' => 'advanced',
	        'fields'     => array(
				array(
					'name'     => esc_html__( 'Copyright', 'thegov' ),
					'id'          => "mb_copyright_switch",
					'type'        => 'button_group',
					'options'     => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'on' => esc_html__( 'On', 'thegov' ),
						'off' => esc_html__( 'Off', 'thegov' ),
					),
					'multiple'    => false,
					'std'         => 'default',
				),
				array(
					'name'     => esc_html__( 'Copyright Settings', 'thegov' ),
					'type'     => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_copyright_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Editor', 'thegov' ),
					'id'   => "mb_copyright_editor",
					'type' => 'textarea',
					'cols' => 20,
					'rows' => 3,
					'std'  => 'Copyright © 2019 Thegov by WebGeniusLab. All Rights Reserved',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(						
							array('mb_copyright_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Text Color', 'thegov' ),
					'id'   => "mb_copyright_text_color",
					'type' => 'color',
					'std'  => '#838383',
					'js_options' => array(
						'defaultColor' => '#838383',
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(						
							array('mb_copyright_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Background Color', 'thegov' ),
					'id'   => "mb_copyright_bg_color",
					'type' => 'color',
					'std'  => '#171a1e',
					'js_options' => array(
						'defaultColor' => '#171a1e',
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(						
							array('mb_copyright_switch','=','on')
						)),
					),
				),
				array(
					'name' => esc_html__( 'Paddings', 'thegov' ),
					'id'   => 'mb_copyright_spacing',
					'type' => 'wgl_offset',
					'options' => array(
						'mode'   => 'padding',
						'top'    => true,
						'right'  => false,
						'bottom' => true,
						'left'   => false,
					),
					'std' => array(
						'padding-top'    => '10',
						'padding-bottom' => '10',
					),
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_copyright_switch','=','on')
						)),
					),
				),
	        ),
	     );
	    return $meta_boxes;
	}

	public function event_related_meta_boxes( $meta_boxes ) {
	    $meta_boxes[] = array(
	        'title'      => esc_html__( 'Related Event', 'thegov' ),
	        'post_types' => array( 'event' ),
	        'context'    => 'advanced',
	        'fields'     => array(   

	        	array(
					'name'    => esc_html__( 'Related Options', 'thegov' ),
					'id'      => "mb_events_show_r",
					'type'    => 'button_group',
					'options' => array(
						'default' => esc_html__( 'Default', 'thegov' ),
						'custom'  => esc_html__( 'Custom', 'thegov' ),
						'off'  	  => esc_html__( 'Off', 'thegov' ),
					),
					'multiple' => false,
					'std'      => 'default',
				),        	
				array(
					'name' => esc_html__( 'Related Settings', 'thegov' ),
					'type' => 'wgl_heading',
					'attributes' => array(
					    'data-conditional-logic'  =>  array( array(
							array('mb_events_show_r','=','custom')
						)),
					),
				), 
				array(
					'name' => esc_html__( 'Title', 'thegov' ),
					'id'   => "mb_events_title_r",
					'type' => 'text',
					'std'  => esc_html__( 'Recent Events', 'thegov' ),
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_events_show_r','=','custom')
						), ),
					),
				), 			 
				array(
					'name' => esc_html__( 'Categories', 'thegov' ),
					'id'   => "mb_events_cat_r",
					'multiple'    => true,
					'type' => 'taxonomy_advanced',
					'taxonomy' => 'event-categories',
					'attributes' => array(
					   	'data-conditional-logic' => array( array(
							array('mb_events_show_r','=','custom')
						), ),
					),
				), 


				array(
					'name' => esc_html__( 'Columns', 'thegov' ),
					'id'   => "mb_events_column_r",
					'type' => 'button_group',
					'options' => array(
						'12' => esc_html__( '1', 'thegov' ),
						'6'  => esc_html__( '2', 'thegov' ),
						'4'  => esc_html__( '3', 'thegov' ),
						'3'  => esc_html__( '4', 'thegov' ),
					),
					'multiple'   => false,
					'std'        => '6',
					'attributes' => array(
						'data-conditional-logic' => array( array(
							array('mb_events_show_r','=','custom')
						), ),
					),
				),  
				array(
					'name' => esc_html__( 'Number of Related Items', 'thegov' ),
					'id'   => "mb_events_number_r",
					'type' => 'number',
					'min'  => 0,
					'step' => 1,
					'std'  => 2,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_events_show_r','=','custom')
							),
						),
					),
				),
	        	array(
					'id'   => 'mb_events_carousel_r',
					'name' => esc_html__( 'Display items carousel for this events post', 'thegov' ),
					'type' => 'switch',
					'std'  => 1,
					'attributes' => array(
						'data-conditional-logic' => array(
							array(
								array('mb_events_show_r','=','custom')
							),
						),
					),
				),  
	        ),
	    );
	    return $meta_boxes;
	}		

    public function location_related_meta_boxes( $meta_boxes ) {
        $meta_boxes[] = array(
            'title'      => esc_html__( 'Related Location', 'thegov' ),
            'post_types' => array( 'location' ),
            'context'    => 'advanced',
            'fields'     => array(   

                array(
                    'name'    => esc_html__( 'Related Options', 'thegov' ),
                    'id'      => "mb_locations_show_r",
                    'type'    => 'button_group',
                    'options' => array(
                        'default' => esc_html__( 'Default', 'thegov' ),
                        'custom'  => esc_html__( 'Custom', 'thegov' ),
                        'off'     => esc_html__( 'Off', 'thegov' ),
                    ),
                    'multiple' => false,
                    'std'      => 'default',
                ),          
                array(
                    'name' => esc_html__( 'Related Settings', 'thegov' ),
                    'type' => 'wgl_heading',
                    'attributes' => array(
                        'data-conditional-logic'  =>  array( array(
                            array('mb_locations_show_r','=','custom')
                        )),
                    ),
                ), 
                array(
                    'name' => esc_html__( 'Title', 'thegov' ),
                    'id'   => "mb_locations_title_r",
                    'type' => 'text',
                    'std'  => esc_html__( 'Recent Locations', 'thegov' ),
                    'attributes' => array(
                        'data-conditional-logic' => array( array(
                            array('mb_locations_show_r','=','custom')
                        ), ),
                    ),
                ),           
                array(
                    'name' => esc_html__( 'Categories', 'thegov' ),
                    'id'   => "mb_locations_cat_r",
                    'multiple'    => true,
                    'type' => 'taxonomy_advanced',
                    'taxonomy' => 'event-categories',
                    'attributes' => array(
                        'data-conditional-logic' => array( array(
                            array('mb_locations_show_r','=','custom')
                        ), ),
                    ),
                ), 

                array(
                    'name' => esc_html__( 'Columns', 'thegov' ),
                    'id'   => "mb_locations_column_r",
                    'type' => 'button_group',
                    'options' => array(
                        '12' => esc_html__( '1', 'thegov' ),
                        '6'  => esc_html__( '2', 'thegov' ),
                        '4'  => esc_html__( '3', 'thegov' ),
                        '3'  => esc_html__( '4', 'thegov' ),
                    ),
                    'multiple'   => false,
                    'std'        => '6',
                    'attributes' => array(
                        'data-conditional-logic' => array( array(
                            array('mb_locations_show_r','=','custom')
                        ), ),
                    ),
                ),  
                array(
                    'name' => esc_html__( 'Number of Related Items', 'thegov' ),
                    'id'   => "mb_locations_number_r",
                    'type' => 'number',
                    'min'  => 0,
                    'step' => 1,
                    'std'  => 2,
                    'attributes' => array(
                        'data-conditional-logic' => array(
                            array(
                                array('mb_locations_show_r','=','custom')
                            ),
                        ),
                    ),
                ),
                array(
                    'id'   => 'mb_locations_carousel_r',
                    'name' => esc_html__( 'Display items carousel for this Locations post', 'thegov' ),
                    'type' => 'switch',
                    'std'  => 1,
                    'attributes' => array(
                        'data-conditional-logic' => array(
                            array(
                                array('mb_locations_show_r','=','custom')
                            ),
                        ),
                    ),
                ),  
            ),
        );
        return $meta_boxes;
    }	


}
new Thegov_Metaboxes();

?>