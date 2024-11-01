<?php
/*
Plugin Name:  Web Stories Enhancer
Plugin URI:   https://wordpress.org/plugins/web-stories-enhancer/
Description:  Show Google Web Stories anywhere with the help of shortcode.
Version:      1.3
Author:       Magazine3 
Author URI:   https://magazine3.company/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  web-stories-enhancer
Domain Path:  languages
*/

// Exit if accessed directly.


if (!defined('ABSPATH')) exit;

 if (!class_exists('Web_Stories_Enhancer')) {

  class Web_Stories_Enhancer
  {

    public function __construct()
    {
        $this->defineConstants();
        $this->addHooks();
        $this->addShortcode();
        $this->addAjaxSupportForm();

    } 
    private function defineConstants() {
      define( 'WEBSTORIES_ENHANCER_VERSION', '1.3' );
      define( 'WEBSTORIES_ENHANCER_PATH', plugin_dir_path( __FILE__ ) );
      define( 'WEBSTORIES_ENHANCER_URL', plugins_url( '', __FILE__) );
      define( 'WEBSTORIES_ENHANCER_PLUGIN_FILE', __FILE__ );
      
		}
    private function addHooks() {
      add_action( 'admin_menu', array( $this, 'registerMenus' ) );
      add_filter( 'plugin_action_links_' . plugin_basename( WEBSTORIES_ENHANCER_PLUGIN_FILE ), array( $this, 'addSettingsPluginAction' ), 10, 4 );
      add_action( 'admin_enqueue_scripts', array( $this,'loadAdminStyles'));
      register_activation_hook(__FILE__, array( $this,'wseActivate'));
      add_action('admin_init',array( $this, 'wseRedirect'));
      add_action( 'admin_notices', array( $this, 'addAdminNotice'));
      add_action( 'wp_ajax_wse_save_settings', array($this,'wseSaveSetting'));
		}

    public function registerMenus() {
      add_options_page(
        __( 'Web Stories Enhancer', 'web-stories-enhancer' ),
        __( 'Web Stories Enhancer', 'web-stories-enhancer' ),
        'manage_options',
        'web-stories-enhancer',
        array( $this, 'displaySettingsPage' )
      );
    }


    public function addSettingsPluginAction( $actions, $plugin_file, $plugin_data, $context ) {
      $plugin_actions['settings'] = sprintf(
        '<a href="%s">' . _x( 'Settings', 'Web Stories Enhancer settings link', 'web-stories-enhancer' ) . '</a>',
        admin_url( 'options-general.php?page=web-stories-enhancer' )
      );
      $plugin_actions['support'] = sprintf(
        '<a href="%s">' . _x( 'Support', 'Web Stories Enhancer support link', 'web-stories-enhancer' ) . '</a>',
        admin_url( 'options-general.php?page=web-stories-enhancer&wse_tab=support' )
      );
      return array_merge($actions,$plugin_actions );
    }

    public function displaySettingsPage() {
      require_once( WEBSTORIES_ENHANCER_PATH . '/includes/helper.php' );
      include WEBSTORIES_ENHANCER_PATH . 'templates/settings.php';
    }

    public function addShortcode() {
      // add shortcode only if Web stories / Make story plugin is installed and activated
      if ($this->checkDependablePlugins()) {
        
      require_once( WEBSTORIES_ENHANCER_PATH . '/includes/shortcode.php' );
      $Web_Stories_Enhancer_Shortcode = new Web_Stories_Enhancer_Shortcode();
      }
		}

    public function addAjaxSupportForm() {

      require_once( WEBSTORIES_ENHANCER_PATH . '/includes/support-form.php' );
      $Web_Stories_Enhancer_Support = new Web_Stories_Enhancer_Support();
    
		}

    public function loadAdminStyles($hook_suffix ) {
      if($hook_suffix=="settings_page_web-stories-enhancer")
      {
        wp_enqueue_style('wse-admin-styles', WEBSTORIES_ENHANCER_URL .'/assets/css/wse-admin.css', array(),WEBSTORIES_ENHANCER_VERSION);
        wp_enqueue_script('wse-admin-script', WEBSTORIES_ENHANCER_URL . '/assets/js/wse-admin.js', array('jquery'), WEBSTORIES_ENHANCER_VERSION, 'true' );
        wp_localize_script('wse-admin-script', 'wse_script_vars', array(
          'nonce' => wp_create_nonce( 'wse-admin-nonce' ),
        )
        );
        wp_enqueue_media();
      }
    }

    public function addAdminNotice() {
      // Show notice if Web stories / Make story plugin is not active
      global $pagenow;

      if (!$this->checkDependablePlugins() && ($pagenow == 'plugins.php' || $pagenow == 'index.php' || ($pagenow=='options-general.php' && isset($_GET['page']) && $_GET['page']=='web-stories-enhancer') ) ) {
        $message=sprintf('<b>Web Stories Enhancer</b> will not work until <a href="%s" target="_blank">Web Stories by Google</a> or <a href="%s" target="_blank">MakeStories (for Web Stories) by MakeStories </a> is installed/activated.',"https://wordpress.org/plugins/web-stories/","https://wordpress.org/plugins/makestories-helper/");
        echo '<div class="notice notice-warning is-dismissible">
                    <p>'.__($message,'web-stories-enhancer').'</p>
                 </div>';
      } 
         
		}

    private function checkDependablePlugins()
    {
      include_once ABSPATH . 'wp-admin/includes/plugin.php';
      if (is_plugin_active( 'web-stories/web-stories.php' ) || is_plugin_active( 'makestories-helper/makestories.php' ) ) {
       return true; 
      }
      return false;
    }

    public function wseActivate() {
        add_option('wse_activation_redirect', true);
    }

    public function wseRedirect() {
        if (get_option('wse_activation_redirect', false)) {
            delete_option('wse_activation_redirect');
            if(!isset($_GET['activate-multi']) && $this->checkDependablePlugins())
            {
                wp_redirect("options-general.php?page=web-stories-enhancer");
            }
        }
    }

    public function wseSaveSetting()
    {
      if ( ! isset( $_POST['wse_security_nonce'] ) ){
        return; 
     }
     if ( !wp_verify_nonce( $_POST['wse_security_nonce'], 'wse-admin-nonce' ) ){
        return;  
     }
     
     $data=[];
     $data['cta_banner'] = sanitize_text_field($_POST['cta_banner']); 
     $data['cta_banner_slide'] = sanitize_text_field($_POST['cta_banner_slide']); 
     $data['cta_enable'] = sanitize_text_field($_POST['cta_enable']); 
     $data['cta_btn_text'] = sanitize_text_field($_POST['cta_btn_text']); 
     $data['cta_btn_link'] = sanitize_text_field($_POST['cta_btn_link']); 
      update_option('wse_settings',$data);
      echo json_encode(array('status'=>'t'));  
  
     wp_die();  
    }

}

$Web_Stories_Enhancer = new Web_Stories_Enhancer();
 }

