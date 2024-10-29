<?php
/* main class for all settings & declaration */
if ( ! class_exists('WC_Cmetric_Aptc')){
   
class WC_Cmetric_Aptc{

  	private static $instance;
 		const TEXT_DOMAIN = 'cmetric-aptc';	

		public static function get_instance() {

				if ( ! self::$instance ) {
					self::$instance = new WC_Cmetric_Aptc();
				}

			return self::$instance;
		}


		public function __construct() {

				add_action( 'plugins_loaded', array($this,'WC_load_cmetric_aptc' ));
		
				$this->setup_constants();
				$this->includes();
			
		}

		/* define all global variable for plugin here */
		private function setup_constants() {

					
			if ( ! defined( 'WC_CMETRIC_APTC_PLUGIN_FILE' ) ) {
				define( 'WC_CMETRIC_APTC_PLUGIN_FILE', __FILE__ );
			}
			if ( ! defined( 'WC_CMETRIC_APTC_PLUGIN_DIR_SSL' ) ) {
				define( 'WC_CMETRIC_APTC_PLUGIN_DIR_SSL', dirname( __FILE__ ) );
			}	
			if ( ! defined( 'WC_CMETRIC_APTC_FRONT_ASSET_DIR' ) ) {
			 		define( 'WC_CMETRIC_APTC_FRONT_ASSET_DIR', plugin_dir_url( __FILE__ ) );
			}
		}

		/* include allr required files here */ 
		private function includes() {

			require_once WC_CMETRIC_APTC_PLUGIN_DIR_SSL . '/includes/cmetric_aptc_settings_functions.php';			
			$this->init();
		}

		private function init() {

		  	add_action( 'init', array( $this, 'load_translation' ) );
				add_action( 'admin_enqueue_scripts',array($this, 'aptc_enqueue_scripts_func_admin')); 
				new WP_Class_Cmetric_Aptc_Settings();				
				return true;
				// $GLOBAL['WP_Class_Cmetric_Sbcfw'] = new WP_Class_Cmetric_Sbcfw();
		}


		public function load_translation()
		{
			load_plugin_textdomain( self::TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}


		/* Enqueue admin CSS here */    
         public function aptc_enqueue_scripts_func_admin() {
                wp_enqueue_style('sbcfwaptc-admin-styles', plugin_dir_url( __FILE__ ).'includes/css/admin.css');
    }

		/* check for woocomerce plugin exitst or not */
		public function WC_load_cmetric_aptc(){

				$is_wc_active = class_exists( 'woocommerce' ) ? is_plugin_active( 'woocommerce/woocommerce.php' ) : false;
		    
		    	if ( current_user_can( 'activate_plugins' ) && ! $is_wc_active ) {
		    
		    		add_action( 'admin_notices', array($this,'woocommerce_cmetric_aptc_activation_notice' ));
		    
		    		//Don't let this plugin activate
		    			 
		    		deactivate_plugins( plugin_basename( __FILE__ ) );
		    		if ( isset( $_GET['activate'] ) ) {
		    			unset( $_GET['activate'] );			
		    		}
		    		return false;
		    	} 
		}

		/* custom notice added for plugin */
		public function woocommerce_cmetric_aptc_activation_notice() {
		
				echo '<div class="error"><p>' . __( '<strong>Activation Error:</strong> You must have the <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> plugin installed and activated for the Automatically add product to cart plug-in to activate.',  self::TEXT_DOMAIN ) . '</p></div>';
    }

	}

}

?>