<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://twitter.com/tassawer_
 * @since      1.0.0
 *
 * @package    Knife_Number
 * @subpackage Knife_Number/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Knife_Number
 * @subpackage Knife_Number/admin
 * @author     Tassawer Hussain <th.tassawer@gmail.com>
 */
class Knife_Number_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
                
                add_action( 'admin_menu', array($this, 'pt_knife_settings_menu') );
                add_action( 'admin_init', array($this, 'pt_knife_admin_init') );

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/knife-number-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/knife-number-admin.js', array( 'jquery' ), $this->version, false );

	}
        
        /**
	 * Register the option page for knife number under settings menu.
	 *
	 * @since    1.0.0
	 */
        public function pt_knife_settings_menu() {
            add_options_page( 
                    'Knife Production Date', // $page_title
                    'Knife Production Date', // $menu_title
                    'manage_options', // $capability
                    'pt-knife-number', //$menu_slug
                    array($this, 'pt_knife_config_page') ); // $function
        }
        
        public function pt_knife_config_page() {
            global $wpdb; ?>
            <!-- Top-level menu -->
            <div id="pt-general" class="wrap">
                <h2>Knife Production Dates <a class="add-new-h2" 
                                   href="<?php echo add_query_arg( array( 
                                       'page' => 'pt-knife-number',
                                       'id' => 'new' ),
                                           admin_url('options-general.php') ); ?>">
                Add New Knife Number</a></h2>

                <!-- Display bug list if no parameter sent in URL -->
                <?php if ( empty( $_GET['id'] ) ) {
                    $pt_query = 'select * from ';
                    $pt_query .= $wpdb->get_blog_prefix() . 'knife_number ';
                    //pt_query .= 'ORDER by bug_report_date DESC';
                    
                    $knife_items = $wpdb->get_results( $wpdb->prepare( $pt_query, '' ), ARRAY_A );
                ?>
                <h3>Manage Knife Numbers</h3>
                <form method="post" 
                      action="<?php echo admin_url( 'admin-post.php' ); ?>">
                    <input type="hidden" name="action" value="delete_knife_record" />
                    <!-- Adding security through hidden referrer field -->
                    <?php wp_nonce_field( 'knife_record_deletion' ); ?>

                <table class="wp-list-table widefat fixed" >
                    <thead>
                        <tr>
                            <th style="width: 50px"></th>
                            <th style="width: 200px">Knife Number</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <?php
                        // Display bugs if query returned results
                        if ($knife_items) {
                            foreach ( $knife_items as $knife_item ) {
                                echo '<tr style="background: #FFF">';
                                echo '<td><input type="checkbox" name="knives[]" value="';
                                echo esc_attr( $knife_item['knife_id'] ) . '" /></td>';
                                echo '<td><a href="';
                                echo add_query_arg( array('page' => 'pt-knife-number', 'id' => $knife_item['knife_id'] ), admin_url( 'options-general.php' ) );
                                echo '">' . $knife_item['knife_number'] . '</a></td>';
                                echo '<td>' . $knife_item['knife_description'] . '</td></tr>';
                            }
                        } else {
                            echo '<tr style="background: #FFF">';
                            echo '<td colspan=4>No Record Found</td></tr>';
                        }      
                    ?>
                </table>
                <br />
                    <input type="submit" value="Delete Selected" class="button-primary"/>
                </form>
                
                
                <!-- Form to upload new record in csv format -->
                <form method="post"
                      action="<?php echo admin_url( 'admin-post.php' ); ?>" 
                      enctype="multipart/form-data">
                    <input type="hidden" name="action" value="import_pt_knife" />

                    <!-- Adding security through hidden referrer field -->
                    <?php wp_nonce_field( 'pt_knife_import' ); ?>

                    <h3>Import Records</h3>
                        Import knife records from CSV File
                        (For reference see this <a href="<?php echo plugins_url( 'importtemplate.csv',__FILE__ ); ?>">Template</a> file)
                        <input name="importknifefile" type="file" /> <br /><br />
                    <input type="submit" value="Import" class="button-primary"/>
                </form>

            <?php } elseif ( isset($_GET['id']) && ($_GET['id']=='new' || is_numeric($_GET['id'])) ) {
                    $knife_id = $_GET['id'];
                    $knife_data = array();
                    $mode = 'new';

                    // Query database if numeric id is present
                    if ( is_numeric($knife_id) ) {
                        $pt_query = 'select * from ' . $wpdb->get_blog_prefix();
                        $pt_query .= 'knife_number where knife_id = ' . $knife_id;
                        $knife_data = $wpdb->get_row( $wpdb->prepare( $pt_query, '' ), ARRAY_A );
                        // Set variable to indicate page mode
                        if ( $knife_data ) 
                            $mode = 'edit';
                    } else {
                        $knife_data['knife_number'] = '';
                        $knife_data['knife_description'] = '';
                    }

                    // Display title based on current mode
                    if ( $mode == 'new' ) {
                        echo '<h3>Add New Knife Record</h3>';
                    } elseif ( $mode == 'edit' ) {
                        echo '<h3>Edit Knife # ' . $knife_data['knife_number'] . ' </h3> ';
                    } ?>
                    <form method="post"
                          action="<?php echo admin_url( 'admin-post.php' ); ?>">
                    <input type="hidden" name="action" value="save_edit_knife" />
                    <input type="hidden" name="knife_id"
                           value="<?php echo esc_attr( $knife_id ); ?>" />

                    <!-- Adding security through hidden referrer field -->
                    <?php wp_nonce_field( 'knife_add_edit' ); ?>

                    <!-- Display knife editing form -->
                    <table>
                        <tr>
                            <td style="width: 150px">Knife Number</td>
                            <td><input type="text" name="knife_number" size="60" 
                                       value="<?php echo esc_attr($knife_data['knife_number']); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <td>Description</td>
                            <td><textarea name="knife_description" cols="60" rows="10"><?php echo esc_textarea($knife_data['knife_description']); ?></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="submit" value="Submit" class="button-primary"/>
                    </form>
            </div>
        <?php }
        }
        
        
        
        public function pt_knife_admin_init() {
             add_action( 'admin_post_save_edit_knife', array($this, 'process_knife_number') );
             add_action( 'admin_post_delete_knife_record', array($this, 'delete_knife_number') );
             add_action( 'admin_post_import_pt_knife', array($this, 'import_knife_records') );
        }
        
        public function process_knife_number() {
    
            // Check if user has proper security level
            if ( !current_user_can( 'manage_options' ) )
                wp_die( 'Not allowed' );

            // Check if nonce field is present for security
            check_admin_referer( 'knife_add_edit' );

            global $wpdb;
            // Place all user submitted values in an array (or empty
            // strings if no value was sent)
            $knife_data = array();
            $knife_data['knife_number'] = ( isset($_POST['knife_number']) ? $_POST['knife_number'] : '' );
            $knife_data['knife_description'] = ( isset($_POST['knife_description']) ? $_POST['knife_description'] : '' );

            // Call the wpdb insert or update method based on value
            // of hidden bug_id field
            if ( isset($_POST['knife_id']) && $_POST['knife_id']=='new') {
                $wpdb->insert( $wpdb->get_blog_prefix() . 'knife_number', $knife_data );
            } elseif ( isset($_POST['knife_id']) && is_numeric($_POST['knife_id']) ) {
                $wpdb->update( $wpdb->get_blog_prefix() . 'knife_number', $knife_data, array('knife_id' => $_POST['knife_id']) );
            }

            // Redirect the page to the user submission form
            wp_redirect( add_query_arg('page', 'pt-knife-number', admin_url('options-general.php')) );
            exit;
        }

        public function delete_knife_number() {
            // Check that user has proper security level
            if ( !current_user_can( 'manage_options' ) )
                wp_die( 'Not allowed' );

            // Check if nonce field is present
            check_admin_referer( 'knife_record_deletion' );

            // If bugs are present, cycle through array and call SQL
            // command to delete entries one by one 
            if ( !empty( $_POST['knives'] ) ) {
                // Retrieve array of bugs IDs to be deleted
                $knives_to_delete = $_POST['knives'];

                global $wpdb;

                foreach ( $knives_to_delete as $knife_to_delete ) {
                    $query = 'DELETE from ' . $wpdb->get_blog_prefix();
                    $query .= 'knife_number ';
                    $query .= 'WHERE knife_id = ' . intval( $knife_to_delete );
                    $wpdb->query( $wpdb->prepare( $query ) );
                }
            }

            // Redirect the page to the user submission form
            wp_redirect( add_query_arg( 'page', 'pt-knife-number',
                                   admin_url( 'options-general.php' ) ) );
            exit; 
        }
        
        public function import_knife_records() {
            // Check that user has proper security level
            if ( !current_user_can( 'manage_options' ) )
                wp_die( 'Not allowed' );

            // Check if nonce field is present
            check_admin_referer( 'pt_knife_import' );

            // Check if file has been uploaded
            if( array_key_exists( 'importknifefile', $_FILES ) ) {
                // If file exists, open it in read mode
                $handle = fopen( $_FILES['importknifefile']['tmp_name'], 'r' );

                // If file is successfully open, extract a row of data
                // based on comma separator, and store in $data array
                if ( $handle ) {
                    while (( $data = fgetcsv($handle, 5000, ',') ) !== FALSE ) {
                        $row += 1;

                        // If row count is ok and row is not header row
                        // Create array and insert in database
                        if ( $row != 1 ) {
                            $new_record = array(
                                'knife_number' => $data[0],
                                'knife_description' => $data[1]
                                );

                            global $wpdb;
                            $wpdb->insert( $wpdb->get_blog_prefix() . 'knife_number', $new_record );
                        }
                    }
                }
            }

            // Redirect the page to the user submission form
            wp_redirect( add_query_arg( 'page', 'pt-knife-number',
                                   admin_url( 'options-general.php' ) ) );
            exit;
        }
}
