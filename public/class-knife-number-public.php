<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://twitter.com/tassawer_
 * @since      1.0.0
 *
 * @package    Knife_Number
 * @subpackage Knife_Number/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Knife_Number
 * @subpackage Knife_Number/public
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Knife_Number_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                
                add_shortcode( 'knife-production-code-finder', array($this, 'knife_production_code_locator_html') );
                add_shortcode( 'knife-production-code-finder-footer', array($this, 'knife_production_code_locator_html_footer') );
                
                /* Ajax Calls */
                add_action( 'wp_ajax_produce_knife_production_date', array( $this, 'produce_knife_production_date') );
                add_action( 'wp_ajax_nopriv_produce_knife_production_date', array( $this, 'produce_knife_production_date') );
        

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Knife_Number_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Knife_Number_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/knife-number-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Knife_Number_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Knife_Number_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/knife-number-public.js', array( 'jquery' ), $this->version, false );
                
                wp_localize_script( $this->plugin_name, 'frontend_ajax_object',
			array(
                            'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);

	}

        
        
        public function knife_production_code_locator_html() {
                
            $pt_output = '';
            $pt_output .= '<div class="pt_knife_locator">';
            $pt_output .= '<p>Find The Production Date Of Your Knife</p>';
            $pt_output .= '<input class="knife_production_date" type="text" name="knife_number" value="" placeholder="Enter the Control Number Here">';
            $pt_output .= '<input type="submit" class="button button_js slider_calltoaction" value="Search" id="fetch_result">';
            $pt_output .= '<p id="knife_result"></p>';
            $pt_output .= '</div>';
            
            return $pt_output;
            
        }
        
        public function knife_production_code_locator_html_footer() {
                
            $pt_output = '';
            $pt_output .= '<div class="pt_knife_locator_footer">';
            $pt_output .= '<p>Find the Production Date</p>';
            $pt_output .= '<input class="knife_production_date" type="text" name="knife_number_footer" value="" placeholder="Enter the Control Number Here">';
			$pt_output .= '<div class="knife-date-action">';
            $pt_output .= '<input type="submit" class="button button_js slider_calltoaction" value="Search" id="fetch_result_footer">';
            $pt_output .= '</div>';
			$pt_output .= '<p id="knife_result_footer"></p>';
            $pt_output .= '</div>';
            
            return $pt_output;
            
        }
        
        public static function produce_knife_production_date() {
            // Prepare query to retrieve knife production date from database
            global $wpdb;
            $pt_query = 'select knife_description from ' . $wpdb->get_blog_prefix();
            $pt_query .= 'knife_number where knife_number = ';
            $pt_query .= intval($_REQUEST['k_number']) ;
            
            $pt_items = $wpdb->get_results($wpdb->prepare( $pt_query , ''), ARRAY_A );

            if($pt_items) {
                echo "Your knife was made in the '<strong>" . $pt_items[0]['knife_description'] . "</strong>'";
            } else {
                echo "Control Number Not Found";
            }
            
            wp_die();
        }
}

