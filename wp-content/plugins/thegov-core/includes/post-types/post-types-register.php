<?php

class WglPostTypesRegister{
    private static $instance = null;

    private $postTypes = array();
    private $allShortcodes = array();
    
    public static function getInstance() {
        if(self::$instance == null) {
            return new self;
        }

        return self::$instance;
    }

    public function init(){
        if(!class_exists('Thegov_Theme_Helper')){
          return;
        }
        
        // Create post type
        new Team();
        new Footer();
        new SidePanel();
        new Portfolio();

        if(class_exists('Vc_Manager')) {
          // Set composer like default page builder
          vc_set_default_editor_post_types( array(
              'team',
              'portfolio',
              'page',
              'post',
              'footer'
          ));
        }
    }
}

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'post-types/team/class-pt-team.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'post-types/footer/class-pt-footer.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'post-types/side_panel/class-pt-side-panel.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'post-types/portfolio/class-pt-portfolio.php';

